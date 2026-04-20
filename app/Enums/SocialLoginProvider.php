<?php

namespace App\Enums;

/**
 * Драйверы Laravel Socialite для входа через соцсети (совпадают с ключами в config/services.php).
 */
enum SocialLoginProvider: string
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
