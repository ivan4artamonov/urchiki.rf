<?php

namespace App\Actions\Grade;

use App\Data\GradeData;
use App\Models\Grade;

/**
 * Обновляет существующий класс на основе DTO.
 */
class SaveGradeAction
{
	/**
	 * Выполняет обновление класса.
	 *
	 * @param GradeData $data DTO с данными класса.
	 * @param Grade $grade Существующая модель класса для обновления.
	 * @return Grade Обновлённая модель класса.
	 */
	public function handle(GradeData $data, Grade $grade): Grade
	{
		$grade->update($data->toModelAttributes());

		return $grade->refresh();
	}
}
