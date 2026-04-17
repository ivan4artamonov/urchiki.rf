<?php

namespace App\Livewire\Admin\Tariffs;

use App\Livewire\Admin\Forms\TariffForm;
use App\Support\Notification;
use Livewire\Component;

class Create extends Component
{
	public TariffForm $form;

	public function createTariff(): void
	{
		$this->form->save();

		Notification::success('Тариф успешно создан.');
		$this->redirectRoute('admin.tariffs.index', navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.tariffs.create')
			->layout('admin', ['adminSectionTitle' => 'создание тарифа']);
	}
}
