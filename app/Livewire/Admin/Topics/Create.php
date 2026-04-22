<?php

namespace App\Livewire\Admin\Topics;

use App\Livewire\Admin\Forms\TopicForm;
use App\Models\Subject;
use App\Support\Notification;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
	public TopicForm $form;
	public Subject $subject;

	/**
	 * Предзаполняет предмет для формы создания темы.
	 *
	 * @param Subject $subject Предмет из параметра URL.
	 * @return void
	 */
	public function mount(Subject $subject): void
	{
		$this->subject = $subject;
		$this->form->subjectId = $subject->id;
	}

	/**
	 * Создаёт тему по данным формы.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function createTopic(): void
	{
		$this->form->save();

		Notification::success('Тема успешно создана.');
		$this->redirectRoute('admin.subjects.edit', ['subject' => $this->subject], navigate: true);
	}

	/**
	 * Формирует страницу создания темы.
	 *
	 * @return View
	 */
	public function render():View {
		$backUrl = route('admin.subjects.edit', ['subject' => $this->subject]);

		return view('livewire.admin.topics.create')
			->with([
				'backUrl' => $backUrl,
				'subject' => $this->subject,
			])
			->layout('admin', ['adminSectionTitle' => 'создание темы']);
	}
}

