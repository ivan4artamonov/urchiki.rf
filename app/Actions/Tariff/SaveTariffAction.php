<?php

namespace App\Actions\Tariff;

use App\Data\TariffData;
use App\Models\Tariff;

/**
 * Создаёт новый тариф или обновляет существующий.
 */
class SaveTariffAction
{
	/**
	 * Выполняет сохранение тарифа на основе DTO.
	 */
	public function handle(TariffData $data, ?Tariff $tariff = null): Tariff
	{
		$attributes = $data->toModelAttributes();

		if ($tariff instanceof Tariff) {
			$tariff->update($attributes);

			return $tariff->refresh();
		}

		return Tariff::query()->create($attributes);
	}
}
