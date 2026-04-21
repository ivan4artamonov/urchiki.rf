<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных темы между слоями приложения.
 */
class TopicData extends Data
{
	/**
	 * @param int $subject_id Идентификатор предмета, к которому относится тема.
	 * @param string $name Название темы для интерфейса.
	 * @param int|null $position Порядок темы в рамках предмета; если null — позицию назначит пакет позиций при сохранении.
	 */
	public function __construct(
		public int $subject_id,
		public string $name,
		public ?int $position = null,
	) {}

	/**
	 * Возвращает атрибуты для сохранения модели темы.
	 *
	 * @return array<string, int|string>
	 */
	public function toModelAttributes(): array
	{
		$attributes = [
			'subject_id' => $this->subject_id,
			'name' => $this->name,
		];

		if ($this->position !== null) {
			$attributes['position'] = $this->position;
		}

		return $attributes;
	}
}
