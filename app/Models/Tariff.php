<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nevadskiy\Position\HasPosition;
use Nevadskiy\Position\PositionObserver;
use Nevadskiy\Position\PositioningScope;

/**
 * Тариф подписки с длительностью, стоимостью и лимитом скачиваний.
 *
 * Используется в витрине тарифов и при создании подписки пользователя.
 *
 * @property int $id Первичный ключ.
 * @property string $name Название тарифа для интерфейса.
 * @property string|null $description Короткое описание условий тарифа.
 * @property int $duration_days Срок действия в днях.
 * @property int $downloads_limit Лимит скачиваний за период.
 * @property int $price Стоимость (целое число, в рублях).
 * @property bool $is_active Признак доступности тарифа для покупки.
 * @property bool $is_featured Признак акцентного тарифа в списке.
 * @property int $position Порядок отображения в витрине.
 * @method static Builder<self> ordered() Получить тарифы в порядке отображения.
 */
class Tariff extends Model
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
	 * Scope для фильтрации тарифов по флагу активности.
	 *
	 * @param Builder<Tariff> $query Базовый запрос к модели тарифов.
	 * @param bool $isActive Флаг активности тарифа.
	 * @return Builder<Tariff>
	 */
	public function scopeActive(Builder $query, bool $isActive = true): Builder
	{
		return $query->where('is_active', $isActive);
	}

	/**
	 * Scope для получения архивных тарифов.
	 *
	 * @param Builder<Tariff> $query Базовый запрос к модели тарифов.
	 * @return Builder<Tariff>
	 */
	public function scopeArchived(Builder $query): Builder
	{
		return $query->active(false);
	}

	/**
	 * Разрешённые для массового присваивания атрибуты.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'name',
		'description',
		'duration_days',
		'downloads_limit',
		'price',
		'is_active',
		'is_featured',
		'position',
	];

	/**
	 * Правила приведения атрибутов к нужным типам.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'duration_days' => 'integer',
		'downloads_limit' => 'integer',
		'price' => 'integer',
		'is_active' => 'boolean',
		'is_featured' => 'boolean',
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
	 * Scope для получения тарифов в порядке отображения.
	 *
	 * @param Builder<Tariff> $query Базовый запрос к модели тарифов.
	 * @return Builder<Tariff>
	 */
	public function scopeOrdered(Builder $query): Builder
	{
		return $query
			->orderByPosition()
			->orderBy('id');
	}
}
