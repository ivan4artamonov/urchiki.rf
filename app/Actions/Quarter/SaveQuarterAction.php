<?php

namespace App\Actions\Quarter;

use App\Data\QuarterData;
use App\Models\Quarter;

/**
 * Обновляет существующую четверть на основе DTO.
 */
class SaveQuarterAction
{
	/**
	 * Выполняет обновление четверти.
	 *
	 * @param QuarterData $data DTO с данными четверти.
	 * @param Quarter $quarter Существующая модель четверти для обновления.
	 * @return Quarter Обновлённая модель четверти.
	 */
	public function handle(QuarterData $data, Quarter $quarter): Quarter
	{
		$quarter->update($data->toModelAttributes());

		return $quarter->refresh();
	}
}
