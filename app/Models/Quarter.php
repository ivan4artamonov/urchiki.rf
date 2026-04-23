<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Учебная четверть внутри конкретного класса.
 *
 * Используется как уровень детализации внутри параллели: у каждого класса есть
 * четыре четверти с номерами 1…4.
 *
 * @property int $grade_id Идентификатор класса, к которому относится четверть.
 * @property int $number Номер четверти (1…4) внутри класса.
 * @property null|string $seo_title SEO-заголовок страницы четверти.
 * @property null|string $seo_description SEO-описание страницы четверти.
 * @property null|string $seo_keywords SEO-ключевые слова страницы четверти.
 * @property null|string $article Текст статьи для страницы четверти.
 * @property-read string $ordinal_label Порядковая метка четверти («Первая», «Вторая», ...).
 * @property-read string $short_label Короткая метка четверти («1 четверть», «2 четверть», ...).
 * @property-read string $full_label Полная метка четверти («Первая четверть», «Вторая четверть», ...).
 * @property-read Collection<int, Worksheet> $worksheets Рабочие листы четверти.
 * @method static Builder<self> ordered() Получить четверти в порядке номеров.
 */
class Quarter extends Model
{
	/**
	 * Список имён атрибутов, разрешённых для массового присваивания.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'grade_id',
		'number',
		'seo_title',
		'seo_description',
		'seo_keywords',
		'article',
	];

	/**
	 * Правила приведения типов при чтении/записи атрибутов.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'grade_id' => 'integer',
		'number' => 'integer',
	];

	/**
	 * Регистрирует дефолтную сортировку четвертей по номеру.
	 *
	 * @return void
	 */
	protected static function booted(): void
	{
		static::addGlobalScope(
			'ordered_by_number',
			/**
			 * Добавляет дефолтную сортировку четвертей по номеру и идентификатору.
			 *
			 * @param Builder<Quarter> $query Базовый запрос к модели четвертей.
			 * @return void
			 */
			function (Builder $query): void {
				$query
					->orderBy('number')
					->orderBy('id');
			}
		);
	}

	/**
	 * Scope для сортировки четвертей по порядковому номеру.
	 *
	 * @param Builder<Quarter> $query Базовый запрос к модели четвертей.
	 * @return Builder<Quarter> Запрос с сортировкой по номеру и идентификатору.
	 */
	public function scopeOrdered(Builder $query): Builder
	{
		return $query
			->orderBy('number')
			->orderBy('id');
	}

	/**
	 * Возвращает связь «четверть принадлежит классу».
	 *
	 * @return BelongsTo<Grade, Quarter> Запрос на выборку класса текущей четверти.
	 */
	public function grade(): BelongsTo
	{
		return $this->belongsTo(Grade::class);
	}

	/**
	 * Возвращает связь «четверть содержит рабочие листы».
	 *
	 * @return HasMany<Worksheet, Quarter> Запрос на выборку листов текущей четверти.
	 */
	public function worksheets(): HasMany
	{
		return $this->hasMany(Worksheet::class);
	}

	/**
	 * Возвращает порядковую метку четверти словами.
	 *
	 * @return Attribute<string, never> Аксессор для формата «Первая».
	 */
	protected function ordinalLabel(): Attribute
	{
		return Attribute::get(fn (): string => self::ordinalByNumber($this->number));
	}

	/**
	 * Возвращает короткую метку четверти с числом.
	 *
	 * @return Attribute<string, never> Аксессор для формата «1 четверть».
	 */
	protected function shortLabel(): Attribute
	{
		return Attribute::get(fn (): string => $this->number . ' четверть');
	}

	/**
	 * Возвращает полную метку четверти на базе порядковой метки.
	 *
	 * @return Attribute<string, never> Аксессор для формата «Первая четверть».
	 */
	protected function fullLabel(): Attribute
	{
		return Attribute::get(fn (): string => $this->ordinal_label . ' четверть');
	}

	/**
	 * Возвращает порядковое название по номеру четверти.
	 *
	 * @param int $number Номер четверти.
	 * @return string Порядковая метка четверти словами.
	 */
	private static function ordinalByNumber(int $number): string
	{
		return match ($number) {
			1 => 'Первая',
			2 => 'Вторая',
			3 => 'Третья',
			4 => 'Четвертая',
			default => (string) $number,
		};
	}
}
