<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных школьного предмета между слоями приложения.
 */
class SubjectData extends Data
{
	/**
	 * @param string $name Название предмета для интерфейса.
	 * @param string|null $slug Сегмент URL; если null или пустая строка — слаг сформирует модель при сохранении.
	 * @param int|null $position Порядок в списке; если null — позицию назначит пакет позиций при сохранении.
	 * @param string|null $seo_title SEO-заголовок страницы предмета.
	 * @param string|null $seo_description SEO-описание страницы предмета.
	 * @param string|null $seo_keywords SEO-ключевые слова страницы предмета.
	 * @param string|null $article Текст статьи для страницы предмета.
	 */
	public function __construct(
		public string $name,
		public ?string $slug = null,
		public ?int $position = null,
		public ?string $seo_title = null,
		public ?string $seo_description = null,
		public ?string $seo_keywords = null,
		public ?string $article = null,
	) {}

	/**
	 * Возвращает атрибуты для сохранения модели предмета.
	 *
	 * @return array<string, int|string|null>
	 */
	public function toModelAttributes(): array
	{
		$attributes = [
			'name' => $this->name,
		];

		if ($this->slug !== null && $this->slug !== '') {
			$attributes['slug'] = $this->slug;
		}

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
