<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

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
 * @property int $sort_order Порядок отображения в витрине.
 * @method static Builder<self> ordered() Получить тарифы в порядке отображения.
 */
class Tariff extends Model implements Sortable
{
	use HasFactory, SortableTrait;

	/**
	 * Настройки автоматической сортировки записей в рамках модели.
	 *
	 * @var array<string, bool|string>
	 */
	public array $sortable = [
		'order_column_name' => 'sort_order',
		'sort_when_creating' => true,
	];

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
		'sort_order',
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
		'sort_order' => 'integer',
	];

	/**
	 * Scope для получения тарифов в порядке отображения.
	 *
	 * @param Builder<Tariff> $query Базовый запрос к модели тарифов.
	 * @return Builder<Tariff>
	 */
	public function scopeOrdered(Builder $query): Builder
	{
		return $query
			->orderBy('sort_order')
			->orderBy('id');
	}
}
