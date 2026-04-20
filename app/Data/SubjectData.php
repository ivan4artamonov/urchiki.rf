<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных школьного предмета между слоями приложения.
 */
class SubjectData extends Data
{
	/**
	 * @param string $name Название предмета для интерфейса.
	 * @param string|null $slug Сегмент URL; если null или пустая строка — слаг сформирует модель при сохранении.
	 * @param int|null $position Порядок в списке; если null — позицию назначит пакет позиций при сохранении.
	 */
	public function __construct(
		public string $name,
		public ?string $slug = null,
		public ?int $position = null,
	) {}

	/**
	 * Возвращает атрибуты для сохранения модели предмета.
	 *
	 * @return array<string, int|string>
	 */
	public function toModelAttributes(): array
	{
		$attributes = [
			'name' => $this->name,
		];

		if ($this->slug !== null && $this->slug !== '') {
			$attributes['slug'] = $this->slug;
		}

		if ($this->position !== null) {
			$attributes['position'] = $this->position;
		}

		return $attributes;
	}
}
