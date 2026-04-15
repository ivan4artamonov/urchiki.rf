<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных тарифа между слоями приложения.
 */
class TariffData extends Data
{
	/**
	 * @param string $name Название тарифа.
	 * @param string|null $description Описание условий тарифа.
	 * @param int $durationDays Срок действия в днях.
	 * @param int $downloadsLimit Лимит скачиваний за период.
	 * @param int $price Стоимость тарифа в рублях.
	 * @param bool $isActive Флаг доступности тарифа для покупки.
	 * @param bool $isFeatured Флаг акцентного тарифа.
	 */
	public function __construct(
		public string $name,
		public ?string $description,
		public int $durationDays,
		public int $downloadsLimit,
		public int $price,
		public bool $isActive,
		public bool $isFeatured,
	) {}

	/**
	 * Возвращает атрибуты для сохранения модели тарифа.
	 *
	 * @return array<string, int|string|null|bool>
	 */
	public function toModelAttributes(): array
	{
		return [
			'name' => $this->name,
			'description' => $this->description,
			'duration_days' => $this->durationDays,
			'downloads_limit' => $this->downloadsLimit,
			'price' => $this->price,
			'is_active' => $this->isActive,
			'is_featured' => $this->isFeatured,
		];
	}
}
