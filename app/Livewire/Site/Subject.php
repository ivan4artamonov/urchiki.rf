<?php

namespace App\Livewire\Site;

use App\Models\Subject as SubjectModel;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Публичная страница предмета со списком классов.
 *
 * Страница показывает выбранный предмет и ссылки на страницы рабочих листов
 * по классам внутри этого предмета.
 */
class Subject extends Component
{
	/**
	 * Текущий предмет из URL.
	 *
	 * @var SubjectModel
	 */
	public SubjectModel $subject;

	/**
	 * Инициализирует страницу выбранным предметом.
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @param SubjectModel $subject Предмет, полученный через route model binding.
	 * @return void
	 */
	public function mount(SubjectModel $subject): void
	{
		$this->subject = $subject;
	}

	/**
	 * Возвращает страницу предмета со списком классов.
	 *
	 * Исключения наружу не пробрасывает.
	 *
	 * @return View Представление страницы предмета.
	 */
	public function render(): View
	{
		return view('livewire.site.subject', [
			'subject' => $this->subject,
		])->layout('site', [
			'title' => 'Рабочие листы по предмету ' . $this->subject->name . ' — ' . config('app.name'),
		]);
	}
}
