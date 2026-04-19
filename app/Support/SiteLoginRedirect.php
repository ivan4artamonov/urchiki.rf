<?php

namespace App\Support;

/**
 * Куда отправить пользователя после успешного входа на сайт (OTP или соцсеть).
 */
final class SiteLoginRedirect
{
    /**
     * URL главной или «intended», если он безопасен (не ведёт в админку).
     */
    public static function urlAfterLogin(): string
    {
        $default = route('site.home');
        $intended = session()->pull('url.intended');

        if (! is_string($intended) || $intended === '') {
            return $default;
        }

        $path = parse_url($intended, PHP_URL_PATH);
        $path = is_string($path) ? $path : '';

        if (str_starts_with($path, '/admin')) {
            return $default;
        }

        return $intended;
    }
}
