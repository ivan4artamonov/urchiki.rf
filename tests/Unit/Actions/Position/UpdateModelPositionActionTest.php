<?php

use App\Actions\Position\UpdateModelPositionAction;
use App\Models\Tariff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('action перемещает тариф на новую позицию', function (): void {
	$action = app(UpdateModelPositionAction::class);

	$trial = Tariff::factory()->create([
		'name' => 'Пробный',
		'is_active' => true,
	]);
	$week = Tariff::factory()->create([
		'name' => 'Неделя',
		'is_active' => true,
	]);
	$month = Tariff::factory()->create([
		'name' => 'Месяц',
		'is_active' => true,
	]);

	$action->handle(Tariff::class, $month->id, 0);

	$orderedNames = Tariff::query()
		->active()
		->ordered()
		->pluck('name')
		->all();

	expect($orderedNames)->toBe([
		'Месяц',
		'Пробный',
		'Неделя',
	]);
});

test('action игнорирует модель без метода move', function (): void {
	$action = app(UpdateModelPositionAction::class);
	$user = User::factory()->create();

	$action->handle(User::class, $user->id, 0);

	expect($user->fresh())->not->toBeNull();
});
