<?php

namespace App\Livewire\Admin\Forms;

use App\Actions\Tariff\SaveTariffAction;
use App\Data\TariffData;
use App\Models\Tariff;
use Livewire\Attributes\Validate;
use Livewire\Form;

/**
 * Форма для создания и редактирования тарифа в админке.
 */
class TariffForm extends Form
{
	public ?Tariff $tariff = null;

	#[Validate('required|string|min:2|max:120')]
	public string $name = '';

	#[Validate('nullable|string|max:5000')]
	public ?string $description = null;

	#[Validate('required|integer|min:1|max:65535')]
	public int|string $durationDays = '';

	#[Validate('required|integer|min:1|max:4294967295')]
	public int|string $downloadsLimit = '';

	#[Validate('required|integer|min:0|max:4294967295')]
	public int|string $price = '';

	public bool $isActive = true;

	public bool $isFeatured = false;

	/**
	 * Сохраняет тариф: создаёт новый или обновляет существующий.
	 */
	public function save(): void
	{
		$validated = $this->validate();
		$isNewTariff = ! ($this->tariff instanceof Tariff);
		$data = TariffData::from([
			'name' => (string) $validated['name'],
			'description' => $validated['description'] !== null ? (string) $validated['description'] : null,
			'durationDays' => (int) $validated['durationDays'],
			'downloadsLimit' => (int) $validated['downloadsLimit'],
			'price' => (int) $validated['price'],
			'isActive' => $this->isActive,
			'isFeatured' => $this->isFeatured,
		]);

		$this->tariff = app(SaveTariffAction::class)->handle($data, $this->tariff);

		if ($isNewTariff) {
			$this->reset();
			$this->isActive = true;
		}
	}

	/**
	 * Заполняет форму данными существующего тарифа для редактирования.
	 */
	public function fillFromTariff(Tariff $tariff): void
	{
		$this->tariff = $tariff;
		$this->name = $tariff->name;
		$this->description = $tariff->description;
		$this->durationDays = $tariff->duration_days;
		$this->downloadsLimit = $tariff->downloads_limit;
		$this->price = $tariff->price;
		$this->isActive = $tariff->is_active;
		$this->isFeatured = $tariff->is_featured;
	}
}
