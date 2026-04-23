<?php

namespace App\Livewire\Admin\Worksheets;

use App\Livewire\Admin\Forms\WorksheetForm;
use App\Models\Worksheet;
use App\Support\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
	use HasWorksheetFormOptions;
	use WithFileUploads;

	public WorksheetForm $form;

	public function mount(Worksheet $worksheet): void
	{
		$this->form->fillFromWorksheet($worksheet);
	}

	public function updateWorksheet(): void
	{
		$this->form->save();

		Notification::success('Рабочий лист успешно обновлен.');
		$this->redirectRoute('admin.worksheets.index', navigate: true);
	}

	public function deleteWorksheet(): void
	{
		if (! ($this->form->worksheet instanceof Worksheet)) {
			return;
		}

		$this->form->worksheet->delete();

		Notification::success('Рабочий лист удален.');
		$this->redirectRoute('admin.worksheets.index', navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.worksheets.edit', $this->worksheetFormOptions())
			->layout('admin', ['adminSectionTitle' => 'редактирование рабочего листа']);
	}
}
