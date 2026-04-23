<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных рабочего листа между слоями приложения.
 */
class WorksheetData extends Data
{
	/**
	 * @param int $topic_id Идентификатор темы, к которой привязан рабочий лист.
	 * @param int $quarter_id Идентификатор четверти, к которой привязан рабочий лист.
	 * @param string $title Название рабочего листа.
	 * @param string|null $slug Уникальный слаг URL рабочего листа.
	 * @param string|null $seo_title SEO-заголовок страницы рабочего листа.
	 * @param string|null $seo_description SEO-описание страницы рабочего листа.
	 * @param string|null $seo_keywords SEO-ключевые слова страницы рабочего листа.
	 * @param string|null $article Текст статьи для страницы рабочего листа.
	 */
	public function __construct(
		public int $topic_id,
		public int $quarter_id,
		public string $title,
		public ?string $slug = null,
		public ?string $seo_title = null,
		public ?string $seo_description = null,
		public ?string $seo_keywords = null,
		public ?string $article = null,
	) {}

	/**
	 * Возвращает атрибуты для сохранения модели рабочего листа.
	 *
	 * @return array<string, int|string|null>
	 */
	public function toModelAttributes(): array
	{
		$attributes = [
			'topic_id' => $this->topic_id,
			'quarter_id' => $this->quarter_id,
			'title' => $this->title,
		];

		if ($this->slug !== null && $this->slug !== '') {
			$attributes['slug'] = $this->slug;
		}

		if ($this->seo_title !== null) {
			$attributes['seo_title'] = $this->seo_title;
		}

		if ($this->seo_description !== null) {
			$attributes['seo_description'] = $this->seo_description;
		}

		if ($this->seo_keywords !== null) {
			$attributes['seo_keywords'] = $this->seo_keywords;
		}

		if ($this->article !== null) {
			$attributes['article'] = $this->article;
		}

		return $attributes;
	}
}
