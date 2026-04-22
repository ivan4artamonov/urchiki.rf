<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных класса между слоями приложения.
 */
class GradeData extends Data
{
	/**
	 * @param string|null $seo_title SEO-заголовок страницы класса.
	 * @param string|null $seo_description SEO-описание страницы класса.
	 * @param string|null $seo_keywords SEO-ключевые слова страницы класса.
	 * @param string|null $article Текст статьи для страницы класса.
	 */
	public function __construct(
		public ?string $seo_title = null,
		public ?string $seo_description = null,
		public ?string $seo_keywords = null,
		public ?string $article = null,
	) {}

	/**
	 * Возвращает атрибуты для обновления модели класса.
	 *
	 * @return array<string, string|null>
	 */
	public function toModelAttributes(): array
	{
		return [
			'seo_title' => $this->seo_title,
			'seo_description' => $this->seo_description,
			'seo_keywords' => $this->seo_keywords,
			'article' => $this->article,
		];
	}
}
