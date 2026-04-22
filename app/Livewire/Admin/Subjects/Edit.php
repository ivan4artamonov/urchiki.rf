<?php

namespace App\Livewire\Admin\Subjects;

use App\Actions\Position\UpdateModelPositionAction;
use App\Livewire\Admin\Forms\SubjectForm;
use App\Models\Subject;
use App\Models\Topic;
use App\Support\Notification;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Edit extends Component
{
	public SubjectForm $form;
	public Subject $subject;

	/**
	 * Заполняет форму данными выбранного предмета.
	 *
	 * @param Subject $subject Редактируемый предмет.
	 * @return void
	 */
	public function mount(Subject $subject): void
	{
		$this->subject = $subject;
		$this->form->fillFromSubject($subject);
	}

	/**
	 * Перемещает тему предмета на новую позицию.
	 *
	 * @param int $itemId Идентификатор темы.
	 * @param int $position Новая позиция темы.
	 * @return void
	 * @throws Exception Если перемещение не удалось.
	 */
	public function moveTopic(int $itemId, int $position): void
	{
		app(UpdateModelPositionAction::class)->handle(Topic::class, $itemId, $position);
	}

	/**
	 * Сохраняет изменения предмета.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function updateSubject(): void
	{
		$this->form->save();

		Notification::success('Предмет успешно обновлён.');
		$this->redirectRoute('admin.subjects.index', navigate: true);
	}

	/**
	 * Формирует страницу редактирования предмета.
	 *
	 * @return View
	 */
	public function render():View {
		$topics = $this->subject
			->topics()
			->get();

		return view('livewire.admin.subjects.edit')
			->with([
				'topics' => $topics,
			])
			->layout('admin', ['adminSectionTitle' => 'редактирование предмета']);
	}
}

