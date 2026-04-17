<?php

namespace App\Actions\User;

use App\Data\UserData;
use App\Models\User;

/**
 * Создаёт нового пользователя или обновляет существующего.
 */
class SaveUserAction
{
	/**
	 * Выполняет сохранение пользователя на основе DTO.
	 */
	public function handle(UserData $data, ?User $user = null): User
	{
		$attributes = $data->toModelAttributes();

		if ($user instanceof User) {
			$user->update($attributes);

			return $user->refresh();
		}

		return User::query()->create($attributes);
	}
}
