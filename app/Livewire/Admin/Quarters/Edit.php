<?php

namespace App\Livewire\Admin\Quarters;

use App\Livewire\Admin\Forms\QuarterForm;
use App\Models\Grade;
use App\Models\Quarter;
use App\Support\Notification;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Edit extends Component
{
	public QuarterForm $form;
	public Grade $grade;

	/**
	 * Заполняет форму данными выбранной четверти.
	 *
	 * @param Quarter $quarter Редактируемая четверть.
	 * @return void
	 */
	public function mount(Quarter $quarter): void
	{
		$this->form->fillFromQuarter($quarter);
		$this->grade = $quarter->grade;
	}

	/**
	 * Сохраняет изменения четверти.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function updateQuarter(): void
	{
		$this->form->save();

		Notification::success('Четверть успешно обновлена.');
		$this->redirectRoute('admin.grades.edit', ['grade' => $this->grade], navigate: true);
	}

	/**
	 * Формирует страницу редактирования четверти.
	 *
	 * @return View
	 */
	public function render(): View
	{
		$backUrl = route('admin.grades.edit', ['grade' => $this->grade]);

		return view('livewire.admin.quarters.edit')
			->with([
				'backUrl' => $backUrl,
				'grade' => $this->grade,
				'quarter' => $this->form->quarter,
			])
			->layout('admin', ['adminSectionTitle' => 'редактирование четверти']);
	}
}
