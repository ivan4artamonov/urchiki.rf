<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * DTO данных личного профиля пользователя на публичном сайте (без админских полей).
 */
class SiteProfileData extends Data
{
    /**
     * @param  string|null  $name  Отображаемое имя; пустая строка сохраняется как null.
     * @param  string  $email  Адрес электронной почты (после нормализации на уровне Action).
     */
    public function __construct(
        public ?string $name,
        public string $email,
    ) {}

    /**
     * Возвращает атрибуты модели пользователя для обновления профиля.
     *
     * @return array<string, string|null>
     */
    public function toModelAttributes(): array
    {
        $name = $this->name;
        $name = is_string($name) ? trim($name) : '';
        $name = $name === '' ? null : $name;

        return [
            'name' => $name,
            'email' => $this->email,
        ];
    }
}
