<?php

namespace App\Actions\Site;

use App\Data\SiteProfileData;
use App\Models\User;

/**
 * Обновляет личные данные пользователя в личном кабинете на сайте.
 */
class UpdateSiteProfileAction
{
    /**
     * Применяет данные профиля к учётной записи: нормализует email, при смене почты сбрасывает подтверждение.
     *
     * @param  User  $user  Текущий пользователь
     * @param  SiteProfileData  $data  Данные формы профиля
     * @return User Обновлённая модель после refresh
     */
    public function handle(User $user, SiteProfileData $data): User
    {
        $normalizedEmail = User::normalizeEmail($data->email);
        $wasEmail = User::normalizeEmail((string) $user->email);

        $attributes = $data->toModelAttributes();
        $attributes['email'] = $normalizedEmail;

        $user->fill([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
        ]);

        if ($normalizedEmail !== $wasEmail) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user->refresh();
    }
}
