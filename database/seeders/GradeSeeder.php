<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

/**
 * Заполняет справочник школьных параллелей (1–11 класс).
 *
 * Повторный запуск безопасен: для каждого номера запись создаётся один раз, затем только находится в БД.
 */
class GradeSeeder extends Seeder
{
	/**
	 * Для номеров 1…11 гарантирует наличие строки {@see Grade}; slug задаёт модель при первом сохранении.
	 */
	public function run(): void
	{
		for ($n = 1; $n <= 11; $n++) {
			Grade::firstOrCreate(['number' => $n]);
		}
	}
}
