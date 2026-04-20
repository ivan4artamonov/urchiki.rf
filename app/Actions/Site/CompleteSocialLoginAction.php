<?php

namespace App\Actions\Site;

use App\Enums\SocialLoginProvider;
use App\Models\SocialAccount;
use App\Models\User;
use App\Services\Site\SocialLoginService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Throwable;

/**
 * Завершение OAuth-входа на сайт: поиск привязки к провайдеру, объединение с учёткой по email, создание пользователя и сессия.
 *
 * Вызывается после успешного ответа провайдера в callback; редиректы, flash и ошибки HTTP/Socialite — вне этого класса.
 */
readonly class CompleteSocialLoginAction
{
    public function __construct(
        private SocialLoginService $socialLogin,
    ) {}

    /**
     * Находит или создаёт пользователя, создаёт при необходимости запись в social_accounts, выполняет вход в сессию.
     *
     * @param  SocialLoginProvider  $provider  Драйвер OAuth для сайта
     * @param  SocialiteUser  $socialUser  Данные профиля, полученные от провайдера
     * @return User|null Вошедший пользователь; null, если у провайдера нет непустого email
     *
     * @throws ModelNotFoundException Если после гонки по уникальному email запись пользователя не найдена (firstOrFail)
     * @throws QueryException|Throwable При сбое SQL внутри транзакции (создание пользователя, привязка)
     */
    public function handle(SocialLoginProvider $provider, SocialiteUser $socialUser): ?User
    {
        $providerUserId = $this->providerUserId($socialUser);

        $user = $this->completeLoginIfSocialAccountAlreadyLinked($provider, $providerUserId);
        if ($user !== null) {
            return $user;
        }

        $email = $this->normalizedEmailFromSocialite($socialUser);
        if ($email === null) {
            return null;
        }

        $user = $this->resolveUserAndEnsureSocialAccountInTransaction(
            $provider,
            $providerUserId,
            $email,
            $socialUser,
        );

        $this->socialLogin->loginWebUser($user);

        return $user;
    }

    /**
     * Идентификатор пользователя у провайдера в виде строки для БД.
     *
     * @param  SocialiteUser  $socialUser  Профиль провайдера
     * @return string
     */
    private function providerUserId(SocialiteUser $socialUser): string
    {
        return $socialUser->getId();
    }

    /**
     * Если привязка provider + id уже есть — входим в сессию и возвращаем пользователя.
     *
     * @param  SocialLoginProvider  $provider  Драйвер OAuth
     * @param  string  $providerUserId  Идентификатор у провайдера
     * @return User|null Учётная запись при существующей привязке; null, если записи social_accounts нет
     */
    private function completeLoginIfSocialAccountAlreadyLinked(SocialLoginProvider $provider, string $providerUserId): ?User
    {
        $link = SocialAccount::query()
            ->where('provider', $provider->value)
            ->where('provider_user_id', $providerUserId)
            ->first();

        if ($link === null) {
            return null;
        }

        $user = $link->user;
        $this->socialLogin->loginWebUser($user);

        return $user;
    }

    /**
     * Нормализованный email из профиля или null, если провайдер не передал непустую строку.
     *
     * @param  SocialiteUser  $socialUser  Профиль провайдера
     * @return string|null
     */
    private function normalizedEmailFromSocialite(SocialiteUser $socialUser): ?string
    {
        $emailRaw = $socialUser->getEmail();
        if (! is_string($emailRaw) || trim($emailRaw) === '') {
            return null;
        }

        return User::normalizeEmail($emailRaw);
    }

    /**
     * В транзакции находит или создаёт пользователя по email и гарантирует строку social_accounts.
     *
     * @param  SocialLoginProvider  $provider  Драйвер OAuth
     * @param  string  $providerUserId  Идентификатор у провайдера
     * @param  string  $email  Уже нормализованный email
     * @param  SocialiteUser  $socialUser  Профиль провайдера
     * @return User Учётная запись с привязкой к провайдеру
     *
     * @throws ModelNotFoundException Если после гонки по уникальному email запись пользователя не найдена (firstOrFail)
     * @throws QueryException|Throwable При сбое SQL внутри транзакции
     */
    private function resolveUserAndEnsureSocialAccountInTransaction(
        SocialLoginProvider $provider,
        string $providerUserId,
        string $email,
        SocialiteUser $socialUser,
    ): User {
        return DB::transaction(function () use ($provider, $providerUserId, $email, $socialUser): User {
            $user = $this->findOrCreateUserForNormalizedEmail($email, $socialUser);
            $this->ensureSocialAccountLinked($provider, $providerUserId, $user);

            return $user;
        });
    }

    /**
     * Блокирует строку по email, при необходимости создаёт пользователя, подтягивает имя из соцпрофиля.
     *
     * @param  string  $email  Нормализованный email
     * @param  SocialiteUser  $socialUser  Профиль провайдера
     * @return User
     *
     * @throws ModelNotFoundException Если после гонки по уникальному email запись пользователя не найдена (firstOrFail)
     * @throws QueryException При ошибке создания или сохранения пользователя
     */
    private function findOrCreateUserForNormalizedEmail(string $email, SocialiteUser $socialUser): User
    {
        $locked = User::query()
            ->whereEmailNormalized($email)
            ->lockForUpdate()
            ->first();

        if ($locked instanceof User) {
            $this->socialLogin->syncDisplayNameFromSocialite($locked, $socialUser);

            return $locked;
        }

        try {
            return User::query()->create([
                'email' => $email,
                'name' => $this->socialLogin->displayNameFromSocialiteUser($socialUser),
                'password' => null,
                'is_admin' => false,
            ]);
        } catch (UniqueConstraintViolationException) {
            $user = User::query()
                ->whereEmailNormalized($email)
                ->lockForUpdate()
                ->firstOrFail();
            $this->socialLogin->syncDisplayNameFromSocialite($user, $socialUser);

            return $user;
        }
    }

    /**
     * Создаёт при необходимости запись social_accounts для пары provider + внешний id.
     *
     * @param  SocialLoginProvider  $provider  Драйвер OAuth
     * @param  string  $providerUserId  Идентификатор у провайдера
     * @param  User  $user  Владелец привязки
     * @return void
     *
     * @throws QueryException При ошибке записи в БД
     */
    private function ensureSocialAccountLinked(SocialLoginProvider $provider, string $providerUserId, User $user): void
    {
        SocialAccount::query()->firstOrCreate(
            [
                'provider' => $provider->value,
                'provider_user_id' => $providerUserId,
            ],
            [
                'user_id' => $user->id,
            ],
        );
    }
}
