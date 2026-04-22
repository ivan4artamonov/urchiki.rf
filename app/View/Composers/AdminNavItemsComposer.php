<?php

namespace App\View\Composers;

use Illuminate\Contracts\View\View;

/**
 * Подставляет в шапку админ-лейаута пункты главного меню (подпись → URL).
 */
final class AdminNavItemsComposer
{
	/**
	 * Добавляет в представление карту пунктов навигации для авторизованной шапки.
	 *
	 * @param View $view Экземпляр представления `admin`
	 * @return void
	 */
	public function compose(View $view): void
	{
		$view->with('adminNavItems', [
			'Рабочие листы' => route('admin.worksheets'),
			'Предметы' => route('admin.subjects.index'),
			'Пользователи' => route('admin.users.index'),
			'ЧаВо' => route('admin.faq.index'),
			'Тарифы' => route('admin.tariffs.index'),
		]);
	}
}
