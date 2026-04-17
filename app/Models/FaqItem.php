<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nevadskiy\Position\HasPosition;
use Nevadskiy\Position\PositionObserver;
use Nevadskiy\Position\PositioningScope;

/**
 * Вопрос-ответ для раздела ЧаВо на сайте и в админке.
 *
 * @property int $id Первичный ключ.
 * @property string $question Текст вопроса.
 * @property string $answer Текст ответа.
 * @property bool $is_active Признак показа на сайте.
 * @property int $position Порядок ручной сортировки.
 * @method static Builder<self> ordered() Получить записи в порядке отображения.
 */
class FaqItem extends Model
{
	use HasFactory, HasPosition;

	/**
	 * Инициализирует позиционирование без рекурсивной регистрации observer.
	 */
	protected static function bootHasPosition(): void
	{
		static::addGlobalScope(new PositioningScope());
	}

	/**
	 * Регистрирует observer после завершения boot модели.
	 */
	protected static function booted(): void
	{
		static::observe(PositionObserver::class);
	}

	/**
	 * Атрибуты, доступные для массового присваивания.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'question',
		'answer',
		'is_active',
		'position',
	];

	/**
	 * Правила приведения атрибутов к нужным типам.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'is_active' => 'boolean',
		'position' => 'integer',
	];

	/**
	 * Группировать позиции по флагу активности.
	 *
	 * @return list<string>
	 */
	public function groupPositionBy(): array
	{
		return ['is_active'];
	}

	/**
	 * Scope для фильтрации записей по активности.
	 *
	 * @param Builder<FaqItem> $query Базовый запрос к модели.
	 * @param bool $isActive Флаг активности записи.
	 * @return Builder<FaqItem>
	 */
	public function scopeActive(Builder $query, bool $isActive = true): Builder
	{
		return $query->where('is_active', $isActive);
	}

	/**
	 * Scope для получения записей в порядке отображения.
	 *
	 * @param Builder<FaqItem> $query Базовый запрос к модели.
	 * @return Builder<FaqItem>
	 */
	public function scopeOrdered(Builder $query): Builder
	{
		return $query
			->orderByPosition()
			->orderBy('id');
	}
}
