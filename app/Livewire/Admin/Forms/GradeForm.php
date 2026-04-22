<?php

namespace App\Livewire\Admin\Forms;

use App\Actions\Grade\SaveGradeAction;
use App\Data\GradeData;
use App\Models\Grade;
use Exception;
use Livewire\Attributes\Validate;
use Livewire\Form;

/**
 * Форма для редактирования класса в админке.
 */
class GradeForm extends Form
{
	public ?Grade $grade = null;

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
	 * Сохраняет изменения класса.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function save(): void
	{
		if (! ($this->grade instanceof Grade)) {
			throw new Exception('Класс для редактирования не найден.');
		}

		$validated = $this->validate();
		$data = GradeData::from([
			'seo_title' => $validated['seoTitle'] !== null ? (string) $validated['seoTitle'] : null,
			'seo_description' => $validated['seoDescription'] !== null ? (string) $validated['seoDescription'] : null,
			'seo_keywords' => $validated['seoKeywords'] !== null ? (string) $validated['seoKeywords'] : null,
			'article' => $validated['article'] !== null ? (string) $validated['article'] : null,
		]);

		$this->grade = app(SaveGradeAction::class)->handle($data, $this->grade);
	}

	/**
	 * Заполняет форму данными существующего класса для редактирования.
	 *
	 * @param Grade $grade Редактируемый класс.
	 * @return void
	 */
	public function fillFromGrade(Grade $grade): void
	{
		$this->grade = $grade;
		$this->seoTitle = $grade->seo_title;
		$this->seoDescription = $grade->seo_description;
		$this->seoKeywords = $grade->seo_keywords;
		$this->article = $grade->article;
	}
}
