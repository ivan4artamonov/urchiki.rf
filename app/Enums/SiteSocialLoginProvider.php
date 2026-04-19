<?php

namespace App\Enums;

/**
 * Драйверы Laravel Socialite для входа на сайт (совпадают с ключами в config/services.php).
 */
enum SiteSocialLoginProvider: string
{
    case Vkontakte = 'vkontakte';
    case Yandex = 'yandex';
    case Mailru = 'mailru';

    /**
     * Имя драйвера для вызова Socialite::driver().
     */
    public function driverName(): string
    {
        return $this->value;
    }
}
