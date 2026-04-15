<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Проверяет, что текущий пользователь является администратором.
 */
class EnsureUserIsAdmin
{
	/**
	 * Ограничивает доступ к админским маршрутам только администраторам.
	 *
	 * @param  Closure(Request): Response  $next
	 */
	public function handle(Request $request, Closure $next): Response|RedirectResponse
	{
		$user = $request->user();

		if (! $user) {
			return redirect()->route('admin.login');
		}

		if (! $user->is_admin) {
			abort(403);
		}

		return $next($request);
	}
}
