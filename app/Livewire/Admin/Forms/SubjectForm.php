<?php

namespace App\Livewire\Admin\Forms;

use App\Actions\Subject\SaveSubjectAction;
use App\Data\SubjectData;
use App\Models\Subject;
use Exception;
use Livewire\Attributes\Validate;
use Livewire\Form;

/**
 * Форма для создания и редактирования предмета в админке.
 */
class SubjectForm extends Form
{
	public ?Subject $subject = null;

	#[Validate('required|string|min:2|max:120')]
	public string $name = '';

	#[Validate('nullable|string|max:96')]
	public ?string $slug = null;

	#[Validate('nullable|string|max:255')]
	public ?string $seoTitle = null;

	#[Validate('nullable|string|max:2000')]
	public ?string $seoDescription = null;

	#[Validate('nullable|string|max:2000')]
	public ?string $seoKeywords = null;

	#[Validate('nullable|string|max:20000')]
	public ?string $article = null;

	/**
	 * Пользовательские сообщения валидации полей формы.
	 *
	 * @return array<string, string>
	 */
	public function messages(): array
	{
		return [
			'name.required' => 'Поле "Название" обязательно для заполнения.',
			'name.string' => 'Поле "Название" должно быть строкой.',
			'name.min' => 'Поле "Название" должно содержать не менее 2 символов.',
			'name.max' => 'Поле "Название" не должно превышать 120 символов.',
			'slug.string' => 'Поле "Slug" должно быть строкой.',
			'slug.max' => 'Поле "Slug" не должно превышать 96 символов.',
			'seoTitle.string' => 'Поле "SEO-заголовок" должно быть строкой.',
			'seoTitle.max' => 'Поле "SEO-заголовок" не должно превышать 255 символов.',
			'seoDescription.string' => 'Поле "SEO-описание" должно быть строкой.',
			'seoDescription.max' => 'Поле "SEO-описание" не должно превышать 2000 символов.',
			'seoKeywords.string' => 'Поле "SEO-ключевые слова" должно быть строкой.',
			'seoKeywords.max' => 'Поле "SEO-ключевые слова" не должно превышать 2000 символов.',
			'article.string' => 'Поле "Статья" должно быть строкой.',
			'article.max' => 'Поле "Статья" не должно превышать 20000 символов.',
		];
	}

	/**
	 * Сохраняет предмет: создаёт новый или обновляет существующий.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function save(): void
	{
		$validated = $this->validate();
		$isNewSubject = ! ($this->subject instanceof Subject);
		$data = SubjectData::from([
			'name' => (string) $validated['name'],
			'slug' => $validated['slug'] !== null && $validated['slug'] !== '' ? (string) $validated['slug'] : null,
			'seo_title' => $validated['seoTitle'] !== null ? (string) $validated['seoTitle'] : null,
			'seo_description' => $validated['seoDescription'] !== null ? (string) $validated['seoDescription'] : null,
			'seo_keywords' => $validated['seoKeywords'] !== null ? (string) $validated['seoKeywords'] : null,
			'article' => $validated['article'] !== null ? (string) $validated['article'] : null,
		]);

		$this->subject = app(SaveSubjectAction::class)->handle($data, $this->subject);

		if ($isNewSubject) {
			$this->reset();
		}
	}

	/**
	 * Заполняет форму данными существующего предмета для редактирования.
	 *
	 * @param Subject $subject Редактируемый предмет.
	 * @return void
	 */
	public function fillFromSubject(Subject $subject): void
	{
		$this->subject = $subject;
		$this->name = $subject->name;
		$this->slug = $subject->slug;
		$this->seoTitle = $subject->seo_title;
		$this->seoDescription = $subject->seo_description;
		$this->seoKeywords = $subject->seo_keywords;
		$this->article = $subject->article;
	}
}

