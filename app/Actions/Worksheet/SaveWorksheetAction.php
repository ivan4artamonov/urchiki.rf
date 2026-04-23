<?php

namespace App\Actions\Worksheet;

use App\Data\WorksheetData;
use App\Models\Worksheet;

/**
 * Создает новый рабочий лист или обновляет существующий.
 */
class SaveWorksheetAction
{
	/**
	 * Выполняет сохранение рабочего листа на основе DTO.
	 */
	public function handle(WorksheetData $data, ?Worksheet $worksheet = null): Worksheet
	{
		$attributes = $data->toModelAttributes();

		if ($worksheet instanceof Worksheet) {
			$worksheet->update($attributes);

			return $worksheet->refresh();
		}

		return Worksheet::query()->create($attributes);
	}
}
