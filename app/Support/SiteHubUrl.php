<?php

namespace App\Support;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Topic;
use InvalidArgumentException;

/**
 * Построение публичных URL страницы хаба каталога (маршрут «site.hub»).
 */
final class SiteHubUrl
{
	/**
	 * Собирает URL хаба по переданным сущностям.
	 *
	 * Допустимые комбинации соответствуют веткам в {@see \App\Livewire\Site\Hub::mount()}:
	 * только предмет; предмет и класс; предмет, класс и тема; только класс (СЕО).
	 *
	 * @param Subject|null $subject Предмет или null в режиме «только класс».
	 * @param Grade|null $grade Класс (параллель).
	 * @param Topic|null $topic Тема (третий сегмент пути).
	 * @return string Абсолютный URL маршрута «site.hub».
	 * @throws InvalidArgumentException Если комбинация аргументов не задаёт допустимый URL или тема не согласована с предметом.
	 */
	public static function make(?Subject $subject = null, ?Grade $grade = null, ?Topic $topic = null): string
	{
		if ($topic !== null) {
			$subject = $subject ?? $topic->subject;
			if ($grade === null) {
				throw new InvalidArgumentException('Для ссылки на тему в хабе нужен класс.');
			}
			if ($subject === null) {
				throw new InvalidArgumentException('Для ссылки на тему в хабе нужен предмет.');
			}
			if ($topic->subject_id !== $subject->getKey()) {
				throw new InvalidArgumentException('Тема не относится к переданному предмету.');
			}

			return route('site.hub', [
				'slug1' => $subject->slug,
				'slug2' => $grade->slug,
				'slug3' => $topic->slug,
			]);
		}

		if ($grade !== null && $subject === null) {
			return route('site.hub', ['slug1' => $grade->slug]);
		}

		if ($subject !== null && $grade !== null) {
			return route('site.hub', [
				'slug1' => $subject->slug,
				'slug2' => $grade->slug,
			]);
		}

		if ($subject !== null) {
			return route('site.hub', ['slug1' => $subject->slug]);
		}

		throw new InvalidArgumentException('Для ссылки на хаб нужен хотя бы предмет или класс.');
	}
}
