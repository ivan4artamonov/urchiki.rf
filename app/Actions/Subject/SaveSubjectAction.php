<?php

namespace App\Actions\Subject;

use App\Data\SubjectData;
use App\Models\Subject;

/**
 * Создаёт новый предмет или обновляет существующий.
 */
class SaveSubjectAction
{
	/**
	 * Выполняет сохранение предмета на основе DTO.
	 *
	 * @param SubjectData $data DTO с данными предмета.
	 * @param Subject|null $subject Существующая модель предмета для обновления.
	 * @return Subject Сохранённая модель предмета.
	 */
	public function handle(SubjectData $data, ?Subject $subject = null): Subject
	{
		$attributes = $data->toModelAttributes();

		if ($subject instanceof Subject) {
			$subject->update($attributes);

			return $subject->refresh();
		}

		return Subject::query()->create($attributes);
	}
}

