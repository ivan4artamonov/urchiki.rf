<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

/**
 * Определяет URL редиректа для неаутентифицированных пользователей (middleware `auth`).
 *
 * Для запросов к админке — страница входа в админку, для остального сайта — главная.
 */
class RedirectGuestUsers
{
    /**
     * Возвращает целевой URL для редиректа гостя в зависимости от префикса пути.
     *
     * @param  Request  $request  Входящий HTTP-запрос
     * @return string Полный URL маршрута входа в админку или главной сайта
     */
    public static function redirect(Request $request): string
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            return route('admin.login');
        }

        return route('site.home');
    }
}
