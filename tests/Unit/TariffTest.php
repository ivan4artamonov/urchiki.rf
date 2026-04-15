<?php

use App\Models\Tariff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('массовое заполнение работает для атрибутов тарифа', function (): void {
	$tariff = Tariff::create([
		'name' => 'Месяц',
		'description' => 'Основной тариф',
		'duration_days' => 30,
		'downloads_limit' => 60,
		'price' => 499,
		'is_active' => true,
		'is_featured' => true,
		'sort_order' => 10,
	]);

	expect($tariff->name)->toBe('Месяц')
		->and($tariff->description)->toBe('Основной тариф')
		->and($tariff->duration_days)->toBe(30)
		->and($tariff->downloads_limit)->toBe(60)
		->and($tariff->price)->toBe(499)
		->and($tariff->is_active)->toBeTrue()
		->and($tariff->is_featured)->toBeTrue();
});

test('касты приводят числовые и булевы поля к корректным типам', function (): void {
	$tariff = Tariff::query()->create([
		'name' => 'Неделя',
		'description' => null,
		'duration_days' => '7',
		'downloads_limit' => '20',
		'price' => '249',
		'is_active' => 1,
		'is_featured' => 0,
		'sort_order' => '3',
	]);

	$tariff->refresh();

	expect($tariff->duration_days)->toBeInt()->toBe(7)
		->and($tariff->downloads_limit)->toBeInt()->toBe(20)
		->and($tariff->price)->toBeInt()->toBe(249)
		->and($tariff->sort_order)->toBeInt()->toBeGreaterThan(0)
		->and($tariff->is_active)->toBeBool()->toBeTrue()
		->and($tariff->is_featured)->toBeBool()->toBeFalse();
});

test('флаги активности и акцента берут значения по умолчанию', function (): void {
	$tariff = Tariff::query()->create([
		'name' => 'Пробный',
		'description' => null,
		'duration_days' => 1,
		'downloads_limit' => 5,
		'price' => 79,
	]);

	$tariff->refresh();

	expect($tariff->is_active)->toBeTrue()
		->and($tariff->is_featured)->toBeFalse();
});

test('scope ordered сортирует по sort_order и затем по id', function (): void {
	$first = Tariff::factory()->create([
		'name' => 'Первый',
	]);
	$second = Tariff::factory()->create([
		'name' => 'Второй',
	]);
	$third = Tariff::factory()->create([
		'name' => 'Третий',
	]);

	Tariff::query()->whereKey($first->id)->update(['sort_order' => 10]);
	Tariff::query()->whereKey($second->id)->update(['sort_order' => 5]);
	Tariff::query()->whereKey($third->id)->update(['sort_order' => 10]);

	$orderedIds = Tariff::query()
		->ordered()
		->pluck('id')
		->all();

	expect($orderedIds)->toBe([
		$second->id,
		$first->id,
		$third->id,
	]);
});

test('sortable позволяет менять позицию тарифа в списке', function (): void {
	$trial = Tariff::factory()->create([
		'name' => 'Пробный',
	]);
	$week = Tariff::factory()->create([
		'name' => 'Неделя',
	]);
	$month = Tariff::factory()->create([
		'name' => 'Месяц',
	]);

	$month->moveToStart();

	$orderedNames = Tariff::query()
		->ordered()
		->pluck('name')
		->all();

	expect($orderedNames)->toBe([
		$month->name,
		$trial->name,
		$week->name,
	]);
});

test('при создании без sort_order позиция назначается автоматически', function (): void {
	$first = Tariff::create([
		'name' => 'Тариф A',
		'description' => null,
		'duration_days' => 30,
		'downloads_limit' => 60,
		'price' => 499,
		'is_active' => true,
		'is_featured' => false,
	]);
	$second = Tariff::create([
		'name' => 'Тариф B',
		'description' => null,
		'duration_days' => 7,
		'downloads_limit' => 20,
		'price' => 249,
		'is_active' => true,
		'is_featured' => false,
	]);

	expect($first->sort_order)->toBeInt()
		->and($second->sort_order)->toBeInt()
		->and($first->sort_order)->toBeLessThan($second->sort_order);
});
