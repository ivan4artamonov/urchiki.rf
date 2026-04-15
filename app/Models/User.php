<?php

namespace App\Models;

// Подтверждение email не подключено; при необходимости — контракт MustVerifyEmail из Laravel.
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Учётная запись для входа в админку и личный кабинет на сайте.
 *
 * @property int $id Первичный ключ.
 * @property string|null $name Отображаемое имя; может быть пустым (например, вход только по email).
 * @property string $email Уникальный адрес электронной почты.
 * @property Carbon|null $email_verified_at Момент подтверждения email; null — если не подтверждён.
 * @property string|null $password Хэш пароля; null — если пароль не задан.
 * @property bool $is_admin Флаг администратора для входа в админку.
 * @property string|null $remember_token Токен опции «запомнить меня».
 * @property Carbon|null $created_at Дата и время создания записи.
 * @property Carbon|null $updated_at Дата и время последнего обновления записи.
 */
#[Fillable(['name', 'email', 'password', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Приведение атрибутов к типам при чтении и записи.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
			'is_admin' => 'boolean',
        ];
    }
}
