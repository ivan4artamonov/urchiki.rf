<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/**
 * Строка лет для копирайта в подвале сайта.
 */
final class CopyrightYearRange
{
	/**
	 * Форматирует годы: при текущем годе, равном году старта — один год;
	 * при большем — диапазон «год старта–текущий»; при меньшем — один фактический год.
	 */
	public static function format(?CarbonInterface $at = null, int $startYear = 2026): string
	{
		$moment = $at ?? Carbon::now();
		$y = $moment->year;

		return match (true) {
			$y < $startYear => (string) $y,
			$y === $startYear => (string) $startYear,
			default => $startYear . "\u{2013}" . $y,
		};
	}
}
