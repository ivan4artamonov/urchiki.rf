<?php

namespace App\Livewire\Admin\Grades;

use App\Models\Grade;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
	/**
	 * Формирует страницу списка классов.
	 *
	 * @return View
	 */
	public function render(): View
	{
		$grades = Grade::query()
			->get();

		return view('livewire.admin.grades.index')
			->with([
				'grades' => $grades,
			])
			->layout('admin', ['adminSectionTitle' => 'классы']);
	}
}
