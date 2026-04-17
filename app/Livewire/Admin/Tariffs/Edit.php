<?php

namespace App\Livewire\Admin\Tariffs;

use App\Livewire\Admin\Forms\TariffForm;
use App\Models\Tariff;
use App\Support\Notification;
use Livewire\Component;

class Edit extends Component
{
	public TariffForm $form;

	public function mount(Tariff $tariff): void
	{
		$this->form->fillFromTariff($tariff);
	}

	public function updateTariff(): void
	{
		$this->form->save();

		Notification::success('Тариф успешно обновлен.');
		$this->redirectRoute('admin.tariffs.index', navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.tariffs.edit')
			->layout('admin', ['adminSectionTitle' => 'редактирование тарифа']);
	}
}
