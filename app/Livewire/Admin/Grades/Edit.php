<?php

namespace App\Livewire\Admin\Grades;

use App\Livewire\Admin\Forms\GradeForm;
use App\Models\Grade;
use App\Support\Notification;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Edit extends Component
{
	public GradeForm $form;
	public Grade $grade;

	/**
	 * Заполняет форму данными выбранного класса.
	 *
	 * @param Grade $grade Редактируемый класс.
	 * @return void
	 */
	public function mount(Grade $grade): void
	{
		$this->grade = $grade;
		$this->form->fillFromGrade($grade);
	}

	/**
	 * Сохраняет изменения класса.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function updateGrade(): void
	{
		$this->form->save();

		Notification::success('Класс успешно обновлён.');
		$this->redirectRoute('admin.grades.index', navigate: true);
	}

	/**
	 * Формирует страницу редактирования класса.
	 *
	 * @return View
	 */
	public function render(): View
	{
		$quarters = $this->grade
			->quarters()
			->get();

		return view('livewire.admin.grades.edit')
			->with([
				'quarters' => $quarters,
			])
			->layout('admin', ['adminSectionTitle' => 'редактирование класса']);
	}
}
