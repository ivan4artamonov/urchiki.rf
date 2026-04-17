<?php

namespace App\Livewire\Admin\Faq;

use App\Livewire\Admin\Forms\FaqItemForm;
use App\Models\FaqItem;
use App\Support\Notification;
use Livewire\Component;

class Edit extends Component
{
	public FaqItemForm $form;

	public function mount(FaqItem $faqItem): void
	{
		$this->form->fillFromFaqItem($faqItem);
	}

	public function updateFaqItem(): void
	{
		$this->form->save();

		Notification::success('Запись ЧаВо успешно обновлена.');
		$this->redirectRoute('admin.faq.index', navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.faq.edit')
			->layout('admin', ['adminSectionTitle' => 'редактирование записи чаво']);
	}
}
