<?php

namespace App\Livewire\Admin\Faq;

use App\Livewire\Admin\Forms\FaqItemForm;
use App\Support\Notification;
use Livewire\Component;

class Create extends Component
{
	public FaqItemForm $form;

	public function createFaqItem(): void
	{
		$this->form->save();

		Notification::success('Запись ЧаВо успешно создана.');
		$this->redirectRoute('admin.faq.index', navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.faq.create')
			->layout('admin', ['adminSectionTitle' => 'создание записи чаво']);
	}
}
