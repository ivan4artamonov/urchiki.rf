<?php

namespace App\Actions\Topic;

use App\Data\TopicData;
use App\Models\Topic;

/**
 * Создаёт новую тему или обновляет существующую.
 */
class SaveTopicAction
{
	/**
	 * Выполняет сохранение темы на основе DTO.
	 *
	 * @param TopicData $data DTO с данными темы.
	 * @param Topic|null $topic Существующая модель темы для обновления.
	 * @return Topic Сохранённая модель темы.
	 */
	public function handle(TopicData $data, ?Topic $topic = null): Topic
	{
		$attributes = $data->toModelAttributes();

		if ($topic instanceof Topic) {
			$subjectChanged = $topic->subject_id !== $attributes['subject_id'];

			$topic->update($attributes);

			if ($subjectChanged) {
				$topic->move(-1);
			}

			return $topic->refresh();
		}

		return Topic::query()->create($attributes);
	}
}

