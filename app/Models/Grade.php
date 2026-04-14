<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Школьная параллель (номер класса без литеры «А», «Б» и т.п.).
 *
 * Уровень каталога «класс» в цепочке предмет → класс → тема → лист.
 * Сегмент публичного URL хранится в {@see $slug} в формате «N-klass»
 * (как в прототипе и SEO-каркасе). Номер параллели по смыслу — 1…11.
 *
 * Дополнительно к наследуемому API {@see Model}:
 *
 * @property int $number Номер параллели (ожидается 1…11), колонка в таблице «grades».
 * @property string $slug Уникальный слаг URL (например «3-klass»).
 * @property-read string $label Подпись для UI («3 класс»), вычисляется аксессором, в БД не хранится.
 */
class Grade extends Model
{
	/**
	 * Список имён атрибутов, разрешённых для массового присваивания ({@see Model::fill()}, {@see Model::create()}).
	 *
	 * Ключи:
	 * — number: номер параллели (1…11);
	 * — slug: URL-сегмент; если пусто при сохранении, подставляется в {@see booted()}.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'number',
		'slug',
	];

	/**
	 * Правила приведения типов при чтении/записи атрибутов.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'number' => 'integer',
	];

	/**
	 * Регистрация глобальных событий жизненного цикла модели.
	 *
	 * Перед сохранением: если {@see $slug} не задан, формируется из {@see $number}
	 * по шаблону «{number}-klass», чтобы совпадать с публичными URL каталога.
	 */
	protected static function booted(): void
	{
		static::saving(
			/**
			 * Обработчик события «saving»: дополняет slug до сохранения в БД.
			 *
			 * @param Grade $grade Модель, которая будет сохранена.
			 */
			function (Grade $grade): void {
				if ($grade->slug === null || $grade->slug === '') {
					$grade->slug = $grade->number . '-klass';
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
	 * Виртуальный атрибут «label»: короткая подпись для заголовков и карточек.
	 *
	 * Доступен как магическое свойство {@see $label} (например «11 класс»).
	 */
	protected function label(): Attribute
	{
		return Attribute::get(fn (): string => $this->number . ' класс');
	}
}
