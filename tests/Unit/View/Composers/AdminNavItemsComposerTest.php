<?php

use App\View\Composers\AdminNavItemsComposer;
use Illuminate\Contracts\View\View;
use Tests\TestCase;

uses(TestCase::class);

test('передаёт в представление меню админки в формате label => href', function (): void {
	$expected = [
		'Рабочие листы' => route('admin.worksheets'),
		'Предметы' => route('admin.subjects.index'),
		'Классы' => route('admin.grades.index'),
		'Пользователи' => route('admin.users.index'),
		'ЧаВо' => route('admin.faq.index'),
		'Тарифы' => route('admin.tariffs.index'),
	];

	$view = \Mockery::mock(View::class);
	$view->shouldReceive('with')
		->once()
		->with('adminNavItems', $expected);

	app(AdminNavItemsComposer::class)->compose($view);
});
