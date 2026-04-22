<?php

namespace App\Livewire\Admin\Subjects;

use App\Livewire\Admin\Forms\SubjectForm;
use App\Support\Notification;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
	use WithFileUploads;

	public SubjectForm $form;

	/**
	 * Создаёт предмет по данным формы.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function createSubject(): void
	{
		$this->form->save();

		Notification::success('Предмет успешно создан.');
		$this->redirectRoute('admin.subjects.index', navigate: true);
	}

	/**
	 * Формирует страницу создания предмета.
	 *
	 * @return View
	 */
	public function render():View {
		return view('livewire.admin.subjects.create')
			->layout('admin', ['adminSectionTitle' => 'создание предмета']);
	}
}

