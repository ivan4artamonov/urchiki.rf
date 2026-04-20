<?php

namespace App\Services\Site;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialiteUser;

/**
 * Вспомогательные операции для входа на сайт через OAuth (сессия и данные профиля провайдера).
 */
class SocialLoginService
{
    /**
     * Помещает пользователя в guard веб-сессии и при наличии сессии регенерирует её идентификатор.
     * Исключения фреймворка при работе сессии наружу не документируются.
     *
     * @param  User  $user  Учётная запись для входа
     * @return void
     */
    public function loginWebUser(User $user): void
    {
        Auth::login($user);

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }
    }

    /**
     * Если у пользователя не задано имя, подставляет его из профиля провайдера (имя или nickname).
     *
     * @param  User  $user  Существующая учётная запись
     * @param  SocialiteUser  $socialUser  Профиль провайдера
     * @return void
     *
     * @throws QueryException При ошибке сохранения модели
     */
    public function syncDisplayNameFromSocialite(User $user, SocialiteUser $socialUser): void
    {
        $current = trim($user->name ?? '');
        if ($current !== '') {
            return;
        }

        $name = $this->displayNameFromSocialiteUser($socialUser);
        if ($name === null) {
            return;
        }

        $user->forceFill(['name' => $name])->save();
    }

    /**
     * Выбирает отображаемое имя из полей профиля провайдера (полное имя либо nickname).
     * Исключения наружу не пробрасывает.
     *
     * @param  SocialiteUser  $socialUser  Профиль провайдера
     * @return string|null Непустая строка или null, если нечего подставить
     */
    public function displayNameFromSocialiteUser(SocialiteUser $socialUser): ?string
    {
        $name = $socialUser->getName();
        if (is_string($name) && trim($name) !== '') {
            return trim($name);
        }

        $nick = $socialUser->getNickname();
        if (is_string($nick) && trim($nick) !== '') {
            return trim($nick);
        }

        return null;
    }
}
