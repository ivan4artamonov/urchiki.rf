<?php

namespace App\Models;

// Подтверждение email не подключено; при необходимости — контракт MustVerifyEmail из Laravel.
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

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
 * @property-read string $initial Буква для аватара: первый символ имени или email (верхний регистр).
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

    /**
     * Одна буква для аватара в шапке: сначала из имени, иначе из email (верхний регистр).
     *
     * @return Attribute<string, never>
     */
    protected function initial(): Attribute
    {
        return Attribute::get(function (): string {
            $name = trim($this->name ?? '');
            if ($name !== '') {
                return Str::upper(Str::substr($name, 0, 1));
            }

            $email = trim($this->email ?? '');
            if ($email !== '') {
                return Str::upper(Str::substr($email, 0, 1));
            }

            return '?';
        });
    }
}
