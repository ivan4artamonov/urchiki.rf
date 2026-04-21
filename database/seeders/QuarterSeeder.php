<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Quarter;
use Illuminate\Database\Seeder;

/**
 * Заполняет четверти для каждого класса.
 *
 * Для каждой параллели из справочника классов создаёт 4 четверти.
 */
class QuarterSeeder extends Seeder
{
	/**
	 * Гарантирует наличие четвертей 1…4 для каждого класса.
	 *
	 * @return void
	 */
	public function run(): void
	{
		Grade::query()
			->select('id')
			/**
			 * Обрабатывает каждый класс и создаёт четверти 1…4.
			 *
			 * @param Grade $grade Класс из справочника параллелей.
			 * @return void
			 */
			->each(function (Grade $grade): void {
				for ($number = 1; $number <= 4; $number++) {
					Quarter::firstOrCreate([
						'grade_id' => $grade->id,
						'number' => $number,
					]);
				}
			});
	}
}
