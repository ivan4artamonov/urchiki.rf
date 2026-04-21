<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных темы между слоями приложения.
 */
class TopicData extends Data
{
	/**
	 * @param int $subject_id Идентификатор предмета, к которому относится тема.
	 * @param string $name Название темы для интерфейса.
	 * @param int|null $position Порядок темы в рамках предмета; если null — позицию назначит пакет позиций при сохранении.
	 * @param string|null $seo_title SEO-заголовок страницы темы.
	 * @param string|null $seo_description SEO-описание страницы темы.
	 * @param string|null $seo_keywords SEO-ключевые слова страницы темы.
	 * @param string|null $article Текст статьи для страницы темы.
	 */
	public function __construct(
		public int $subject_id,
		public string $name,
		public ?int $position = null,
		public ?string $seo_title = null,
		public ?string $seo_description = null,
		public ?string $seo_keywords = null,
		public ?string $article = null,
	) {}

	/**
	 * Возвращает атрибуты для сохранения модели темы.
	 *
	 * @return array<string, int|string|null>
	 */
	public function toModelAttributes(): array
	{
		$attributes = [
			'subject_id' => $this->subject_id,
			'name' => $this->name,
		];

		if ($this->position !== null) {
			$attributes['position'] = $this->position;
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
