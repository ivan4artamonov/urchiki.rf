<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных вопроса-ответа между слоями приложения.
 */
class FaqItemData extends Data
{
	/**
	 * @param string $question Текст вопроса.
	 * @param string $answer Текст ответа.
	 * @param bool $isActive Флаг активности записи.
	 * @param int|null $position Ручная позиция в списке.
	 */
	public function __construct(
		public string $question,
		public string $answer,
		public bool $isActive,
		public ?int $position = null,
	) {}

	/**
	 * Возвращает атрибуты для сохранения модели вопроса-ответа.
	 *
	 * @return array<string, int|string|bool>
	 */
	public function toModelAttributes(): array
	{
		$attributes = [
			'question' => $this->question,
			'answer' => $this->answer,
			'is_active' => $this->isActive,
		];

		if ($this->position !== null) {
			$attributes['position'] = $this->position;
		}

		return $attributes;
	}
}
