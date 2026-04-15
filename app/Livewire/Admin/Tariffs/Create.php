<?php

namespace App\Livewire\Admin\Tariffs;

use App\Livewire\Admin\Forms\TariffForm;
use Livewire\Component;

class Create extends Component
{
	public TariffForm $form;

	public function createTariff(): void
	{
		$this->form->save();

		session()->flash('tariff-created', 'Тариф успешно создан.');
		$this->redirectRoute('admin.tariffs.index', navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.tariffs.create')
			->layout('admin', ['adminSectionTitle' => 'создание тарифа']);
	}
}
