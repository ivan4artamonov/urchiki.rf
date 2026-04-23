<?php

namespace App\Livewire\Admin\Worksheets;

use App\Livewire\Admin\Forms\WorksheetForm;
use App\Support\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
	use HasWorksheetFormOptions;
	use WithFileUploads;

	public WorksheetForm $form;

	public function createWorksheet(): void
	{
		$this->form->save();

		Notification::success('Рабочий лист успешно создан.');
		$this->redirectRoute('admin.worksheets.index', navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.worksheets.create', $this->worksheetFormOptions())
			->layout('admin', ['adminSectionTitle' => 'создание рабочего листа']);
	}
}
