<?php

namespace App\Livewire\Admin\Subjects;

use App\Actions\Position\UpdateModelPositionAction;
use App\Models\Subject;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
	/**
	 * Переместить предмет на новую позицию.
	 *
	 * @param int $itemId ID перетаскиваемого предмета.
	 * @param int $position Новая позиция предмета.
	 * @return void
	 * @throws Exception Если перемещение не удалось.
	 */
	public function moveSubject(int $itemId, int $position): void
	{
		app(UpdateModelPositionAction::class)->handle(Subject::class, $itemId, $position);
	}

	/**
	 * Формирует страницу списка предметов.
	 *
	 * @return View
	 */
	public function render():View {
		$subjects = Subject::query()
			->withCount('topics')
			->ordered()
			->get();

		return view('livewire.admin.subjects.index')
			->with([
				'subjects' => $subjects,
			])
			->layout('admin', ['adminSectionTitle' => 'предметы']);
	}
}

