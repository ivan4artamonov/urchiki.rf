<?php

namespace App\Livewire\Admin\Forms;

use App\Actions\Topic\SaveTopicAction;
use App\Data\TopicData;
use App\Models\Topic;
use Exception;
use Livewire\Attributes\Validate;
use Livewire\Form;

/**
 * Форма для создания и редактирования темы в админке.
 */
class TopicForm extends Form
{
	public ?Topic $topic = null;

	#[Validate('required|integer|exists:subjects,id')]
	public int|string|null $subjectId = null;

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
			'subjectId.required' => 'Поле "Предмет" обязательно для выбора.',
			'subjectId.integer' => 'Поле "Предмет" должно быть корректным идентификатором.',
			'subjectId.exists' => 'Выбранный предмет не найден.',
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
	 * Сохраняет тему: создаёт новую или обновляет существующую.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function save(): void
	{
		$validated = $this->validate();
		$isNewTopic = ! ($this->topic instanceof Topic);
		$data = TopicData::from([
			'subject_id' => (int) $validated['subjectId'],
			'name' => (string) $validated['name'],
			'slug' => $validated['slug'] !== null && $validated['slug'] !== '' ? (string) $validated['slug'] : null,
			'seo_title' => $validated['seoTitle'] !== null ? (string) $validated['seoTitle'] : null,
			'seo_description' => $validated['seoDescription'] !== null ? (string) $validated['seoDescription'] : null,
			'seo_keywords' => $validated['seoKeywords'] !== null ? (string) $validated['seoKeywords'] : null,
			'article' => $validated['article'] !== null ? (string) $validated['article'] : null,
		]);

		$this->topic = app(SaveTopicAction::class)->handle($data, $this->topic);

		if ($isNewTopic) {
			$this->reset();
		}
	}

	/**
	 * Заполняет форму данными существующей темы для редактирования.
	 *
	 * @param Topic $topic Редактируемая тема.
	 * @return void
	 */
	public function fillFromTopic(Topic $topic): void
	{
		$this->topic = $topic;
		$this->subjectId = $topic->subject_id;
		$this->name = $topic->name;
		$this->slug = $topic->slug;
		$this->seoTitle = $topic->seo_title;
		$this->seoDescription = $topic->seo_description;
		$this->seoKeywords = $topic->seo_keywords;
		$this->article = $topic->article;
	}
}

