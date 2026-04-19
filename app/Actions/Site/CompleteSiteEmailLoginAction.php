<?php

namespace App\Actions\Site;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

/**
 * Проверка кода из письма и вход или регистрация пользователя на сайте.
 */
class CompleteSiteEmailLoginAction
{
    /**
     * Проверяет код, при успехе создаёт пользователя при необходимости и авторизует.
     *
     * @param  string  $email  Адрес электронной почты (нормализуется через {@see User::normalizeEmail()})
     * @param  string  $code  Четыре цифры без пробелов
     * @return User|null Модель пользователя при успехе; null при неверном коде или отсутствии кода
     */
    public function handle(string $email, string $code): ?User
    {
        $normalized = User::normalizeEmail($email);
        $key = 'site_email_otp:'.$normalized;
        $payload = Cache::get($key);

        if (! is_array($payload) || ! isset($payload['hash']) || ! is_string($payload['hash'])) {
            return null;
        }

        if (! Hash::check($code, $payload['hash'])) {
            $fails = (int) ($payload['fails'] ?? 0) + 1;

            if ($fails >= 5) {
                Cache::forget($key);
            } else {
                $payload['fails'] = $fails;
                Cache::put($key, $payload, now()->addMinutes(10));
            }

            return null;
        }

        $user = User::query()
            ->whereEmailNormalized($normalized)
            ->first();

        if (! $user instanceof User) {
            $user = User::query()->create([
                'email' => $normalized,
                'name' => null,
                'password' => null,
                'is_admin' => false,
            ]);
        }

        Auth::login($user, false);

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        Cache::forget($key);

        return $user;
    }
}
