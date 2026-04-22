<?php

namespace App\Livewire\Admin\Forms;

use App\Actions\Quarter\SaveQuarterAction;
use App\Data\QuarterData;
use App\Models\Quarter;
use Exception;
use Livewire\Attributes\Validate;
use Livewire\Form;

/**
 * Форма для редактирования четверти в админке.
 */
class QuarterForm extends Form
{
	public ?Quarter $quarter = null;

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
	 * Сохраняет изменения четверти.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function save(): void
	{
		if (! ($this->quarter instanceof Quarter)) {
			throw new Exception('Четверть для редактирования не найдена.');
		}

		$validated = $this->validate();
		$data = QuarterData::from([
			'seo_title' => $validated['seoTitle'] !== null ? (string) $validated['seoTitle'] : null,
			'seo_description' => $validated['seoDescription'] !== null ? (string) $validated['seoDescription'] : null,
			'seo_keywords' => $validated['seoKeywords'] !== null ? (string) $validated['seoKeywords'] : null,
			'article' => $validated['article'] !== null ? (string) $validated['article'] : null,
		]);

		$this->quarter = app(SaveQuarterAction::class)->handle($data, $this->quarter);
	}

	/**
	 * Заполняет форму данными существующей четверти для редактирования.
	 *
	 * @param Quarter $quarter Редактируемая четверть.
	 * @return void
	 */
	public function fillFromQuarter(Quarter $quarter): void
	{
		$this->quarter = $quarter;
		$this->seoTitle = $quarter->seo_title;
		$this->seoDescription = $quarter->seo_description;
		$this->seoKeywords = $quarter->seo_keywords;
		$this->article = $quarter->article;
	}
}
