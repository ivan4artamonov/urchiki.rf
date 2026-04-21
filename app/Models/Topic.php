<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Nevadskiy\Position\HasPosition;
use Nevadskiy\Position\PositionObserver;
use Nevadskiy\Position\PositioningScope;

/**
 * Тема учебного материала внутри конкретного предмета.
 *
 * @property int $subject_id Идентификатор предмета, к которому относится тема.
 * @property string $name Название темы для интерфейса.
 * @property int $position Порядок отображения темы внутри предмета.
 * @property-read Subject $subject Предмет, к которому привязана тема.
 * @method static Builder<self> ordered() Получить темы в порядке отображения.
 */
class Topic extends Model
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
	 * Регистрирует observer после завершения boot модели.
	 *
	 * @return void
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
		'subject_id',
		'name',
		'position',
	];

	/**
	 * Правила приведения атрибутов к нужным типам.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'subject_id' => 'integer',
		'position' => 'integer',
	];

	/**
	 * Группирует позиции тем в рамках одного предмета.
	 *
	 * @return list<string> Поля группировки для позиционирования.
	 */
	public function groupPositionBy(): array
	{
		return ['subject_id'];
	}

	/**
	 * Scope для получения тем в порядке отображения.
	 *
	 * @param Builder<Topic> $query Базовый запрос к модели тем.
	 * @return Builder<Topic> Запрос с сортировкой по позиции и идентификатору.
	 */
	public function scopeOrdered(Builder $query): Builder
	{
		return $query
			->orderByPosition()
			->orderBy('id');
	}

	/**
	 * Возвращает связь «тема принадлежит предмету».
	 *
	 * @return BelongsTo<Subject, Topic> Запрос на выборку предмета текущей темы.
	 */
	public function subject(): BelongsTo
	{
		return $this->belongsTo(Subject::class);
	}
}
