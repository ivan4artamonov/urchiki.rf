<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных пользователя между слоями приложения.
 */
class UserData extends Data
{
	/**
	 * @param string|null $name Имя пользователя.
	 * @param string $email Email пользователя.
	 * @param string|null $password Пароль в открытом виде для хеширования на уровне модели.
	 * @param bool $isAdmin Флаг административного доступа.
	 */
	public function __construct(
		public ?string $name,
		public string $email,
		public ?string $password,
		public bool $isAdmin,
	) {}

	/**
	 * Возвращает атрибуты для сохранения модели пользователя.
	 *
	 * @return array<string, string|bool|null>
	 */
	public function toModelAttributes(): array
	{
		$attributes = [
			'name' => $this->name,
			'email' => $this->email,
			'is_admin' => $this->isAdmin,
		];

		if ($this->password !== null) {
			$attributes['password'] = $this->password;
		}

		return $attributes;
	}
}
