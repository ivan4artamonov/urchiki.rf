<?php

namespace App\Support;

use App\Enums\NotificationType;

/**
 * Сервис для записи и чтения flash-уведомлений в сессии.
 */
final class Notification
{
	public const string FLASH_KEY = 'notification';

	/**
	 * Сохраняет уведомление в сессию на следующий запрос.
	 *
	 * @param NotificationType $type Тип уведомления.
	 * @param string $message Текст уведомления.
	 * @return void
	 */
	public static function flash(NotificationType $type, string $message): void
	{
		session()->flash(self::FLASH_KEY, [
			'type' => $type->value,
			'message' => $message,
		]);
	}

	/**
	 * Сохраняет уведомление об успешном результате.
	 *
	 * @param string $message Текст уведомления.
	 * @return void
	 */
	public static function success(string $message): void
	{
		self::flash(NotificationType::Success, $message);
	}

	/**
	 * Сохраняет уведомление об ошибке.
	 *
	 * @param string $message Текст уведомления.
	 * @return void
	 */
	public static function error(string $message): void
	{
		self::flash(NotificationType::Error, $message);
	}

	/**
	 * Сохраняет предупреждающее уведомление.
	 *
	 * @param string $message Текст уведомления.
	 * @return void
	 */
	public static function warning(string $message): void
	{
		self::flash(NotificationType::Warning, $message);
	}

	/**
	 * Сохраняет информационное уведомление.
	 *
	 * @param string $message Текст уведомления.
	 * @return void
	 */
	public static function info(string $message): void
	{
		self::flash(NotificationType::Info, $message);
	}

	/**
	 * Возвращает уведомление из сессии в нормализованном формате.
	 *
	 * @return array{type: NotificationType, message: string}|null
	 */
	public static function read(): ?array
	{
		$notification = session(self::FLASH_KEY);

		if (! is_array($notification)) {
			return null;
		}

		$message = data_get($notification, 'message');

		if (! is_string($message) || blank($message)) {
			return null;
		}

		$type = NotificationType::tryFrom((string) data_get($notification, 'type', NotificationType::Info->value))
			?? NotificationType::Info;

		return [
			'type' => $type,
			'message' => $message,
		];
	}

	/**
	 * Возвращает тип текущего уведомления.
	 *
	 * @return NotificationType|null
	 */
	public static function type(): ?NotificationType
	{
		return data_get(self::read(), 'type');
	}

	/**
	 * Возвращает текст текущего уведомления.
	 *
	 * @return string|null
	 */
	public static function message(): ?string
	{
		return data_get(self::read(), 'message');
	}

	/**
	 * Проверяет, есть ли текущее уведомление в сессии.
	 *
	 * @return bool
	 */
	public static function has(): bool
	{
		return self::read() !== null;
	}

	/**
	 * Проверяет, есть ли уведомление указанного типа.
	 *
	 * @param NotificationType $type Тип уведомления для проверки.
	 * @return bool
	 */
	public static function hasType(NotificationType $type): bool
	{
		return self::type() === $type;
	}
}
