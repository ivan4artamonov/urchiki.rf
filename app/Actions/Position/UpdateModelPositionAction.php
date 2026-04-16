<?php

namespace App\Actions\Position;

use Illuminate\Database\Eloquent\Model;

/**
 * Обновляет позицию sortable-модели по её классу и идентификатору.
 */
class UpdateModelPositionAction
{
	/**
	 * Обновить позицию модели.
	 *
	 * @param class-string<Model> $modelClass Класс модели.
	 * @param int $id Идентификатор модели.
	 * @param int $position Позиция из frontend (0-based).
	 */
	public function handle(string $modelClass, int $id, int $position): void
	{
		$model = $modelClass::find($id);
		if ($model === null || ! method_exists($model, 'move')) {
			return;
		}

		$model->move($position + 1);
	}
}
