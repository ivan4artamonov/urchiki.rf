<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Nevadskiy\Position\HasPosition;
use Nevadskiy\Position\PositionObserver;
use Nevadskiy\Position\PositioningScope;

/**
 * Школьный предмет с набором тем.
 *
 * Предмет используется как верхний уровень для каталога тем. Сегмент публичного URL
 * хранится в {@see $slug} (латиница, как в прототипе навигации сайта), а порядок
 * в списках задаётся колонкой {@see $position} (трейт {@see HasPosition}).
 *
 * Дополнительно к наследуемому API {@see Model}:
 *
 * @property string $name Название для интерфейса («Математика», «Русский язык»).
 * @property string $slug Уникальный слаг URL (например «matematika»).
 * @property int $position Порядок отображения среди предметов.
 * @property null|string $seo_title SEO-заголовок страницы предмета.
 * @property null|string $seo_description SEO-описание страницы предмета.
 * @property null|string $seo_keywords SEO-ключевые слова страницы предмета.
 * @property null|string $article Текст статьи для страницы предмета.
 * @property-read Collection<int, Topic> $topics Темы текущего предмета.
 * @method static Builder<self> ordered() Получить предметы в порядке отображения.
 */
class Subject extends Model
{
	use HasPosition;

	/**
	 * Инициализирует позиционирование без рекурсивной регистрации observer.
	 *
	 * @return void
	 */
	protected static function bootHasPosition(): void
	{
		static::addGlobalScope(new PositioningScope());
	}

	/**
	 * Список имён атрибутов, разрешённых для массового присваивания ({@see Model::fill()}, {@see Model::create()}).
	 *
	 * Ключи:
	 * — name: подпись предмета;
	 * — slug: URL-сегмент; если пусто при сохранении, подставляется в {@see booted()} из {@see $name};
	 * — position: порядок в списке (необязательно при создании — назначит пакет позиций).
	 * — seo_title, seo_description, seo_keywords: SEO-метаданные карточки и страницы предмета.
	 * — article: текст статьи для страницы предмета.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'name',
		'slug',
		'position',
		'seo_title',
		'seo_description',
		'seo_keywords',
		'article',
	];

	/**
	 * Правила приведения атрибутов к нужным типам.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'position' => 'integer',
	];

	/**
	 * Регистрация глобальных событий жизненного цикла модели.
	 *
	 * Регистрирует observer позиций после boot модели. Перед сохранением: если {@see $slug} не задан,
	 * формируется из {@see $name} через {@see Str::slug()}, чтобы совпадать с ожидаемым форматом публичных URL.
	 *
	 * @return void
	 */
	protected static function booted(): void
	{
		static::observe(PositionObserver::class);

		static::saving(
			/**
			 * Обработчик события «saving»: дополняет slug до сохранения в БД.
			 *
			 * @param Subject $subject Модель, которая будет сохранена.
			 * @return void
			 */
			function (Subject $subject): void {
				if ($subject->slug === null || $subject->slug === '') {
					$subject->slug = Str::slug($subject->name);
				}
			}
		);
	}

	/**
	 * Имя столбца для неявной маршрутизации и подстановки модели из URL ({@see Model::resolveRouteBinding()}).
	 *
	 * @return non-empty-string Всегда «slug», а не числовой id.
	 */
	public function getRouteKeyName(): string
	{
		return 'slug';
	}

	/**
	 * Scope для получения предметов в порядке отображения.
	 *
	 * @param Builder<Subject> $query Базовый запрос к модели предметов.
	 * @return Builder<Subject>
	 */
	public function scopeOrdered(Builder $query): Builder
	{
		return $query
			->orderByPosition()
			->orderBy('id');
	}

	/**
	 * Возвращает связь «предмет содержит темы».
	 *
	 * @return HasMany<Topic, Subject> Запрос на выборку тем текущего предмета.
	 */
	public function topics(): HasMany
	{
		return $this->hasMany(Topic::class)->ordered();
	}
}
