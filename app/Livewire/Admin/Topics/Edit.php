<?php

namespace App\Livewire\Admin\Topics;

use App\Livewire\Admin\Forms\TopicForm;
use App\Models\Subject;
use App\Models\Topic;
use App\Support\Notification;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Edit extends Component
{
	public TopicForm $form;
	public Subject $subject;

	/**
	 * Заполняет форму данными выбранной темы.
	 *
	 * @param Topic $topic Редактируемая тема.
	 * @return void
	 */
	public function mount(Topic $topic): void
	{
		$this->form->fillFromTopic($topic);
		$this->subject = $topic->subject;
	}

	/**
	 * Сохраняет изменения темы.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function updateTopic(): void
	{
		$this->form->save();

		Notification::success('Тема успешно обновлена.');
		$this->redirectRoute('admin.subjects.edit', ['subject' => $this->subject], navigate: true);
	}

	/**
	 * Формирует страницу редактирования темы.
	 *
	 * @return View
	 */
	public function render():View {
		$backUrl = route('admin.subjects.edit', ['subject' => $this->subject]);

		return view('livewire.admin.topics.edit')
			->with([
				'backUrl' => $backUrl,
				'subject' => $this->subject,
			])
			->layout('admin', ['adminSectionTitle' => 'редактирование темы']);
	}
}

