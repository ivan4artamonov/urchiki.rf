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
	 * Пользовательские сообщения валидации полей формы.
	 *
	 * @return array<string, string>
	 */
	public function messages(): array
	{
		return [
			'name.required' => 'Поле "Название" обязательно для заполнения.',
			'name.string' => 'Поле "Название" должно быть строкой.',
			'name.min' => 'Поле "Название" должно содержать не менее 2 символов.',
			'name.max' => 'Поле "Название" не должно превышать 120 символов.',
			'description.string' => 'Поле "Описание" должно быть строкой.',
			'description.max' => 'Поле "Описание" не должно превышать 5000 символов.',
			'durationDays.required' => 'Поле "Срок действия" обязательно для заполнения.',
			'durationDays.integer' => 'Поле "Срок действия" должно быть целым числом.',
			'durationDays.min' => 'Поле "Срок действия" должно быть не менее 1 дня.',
			'durationDays.max' => 'Поле "Срок действия" не должно превышать 65535 дней.',
			'downloadsLimit.required' => 'Поле "Лимит скачиваний" обязательно для заполнения.',
			'downloadsLimit.integer' => 'Поле "Лимит скачиваний" должно быть целым числом.',
			'downloadsLimit.min' => 'Поле "Лимит скачиваний" должно быть не менее 1.',
			'downloadsLimit.max' => 'Поле "Лимит скачиваний" не должно превышать 4294967295.',
			'price.required' => 'Поле "Цена" обязательно для заполнения.',
			'price.integer' => 'Поле "Цена" должно быть целым числом.',
			'price.min' => 'Поле "Цена" не может быть отрицательным.',
			'price.max' => 'Поле "Цена" не должно превышать 4294967295.',
		];
	}

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
