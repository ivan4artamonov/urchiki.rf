<?php

use App\Actions\Tariff\SaveTariffAction;
use App\Data\TariffData;
use App\Models\Tariff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('action создает новый тариф из dto', function (): void {
	$action = app(SaveTariffAction::class);
	$data = TariffData::from([
		'name' => 'Месяц',
		'description' => 'Основной тариф',
		'durationDays' => 30,
		'downloadsLimit' => 60,
		'price' => 499,
		'isActive' => true,
		'isFeatured' => true,
	]);

	$tariff = $action->handle($data);

	expect($tariff->exists)->toBeTrue()
		->and($tariff->id)->toBeInt()
		->and($tariff->name)->toBe('Месяц')
		->and($tariff->description)->toBe('Основной тариф')
		->and($tariff->duration_days)->toBe(30)
		->and($tariff->downloads_limit)->toBe(60)
		->and($tariff->price)->toBe(499)
		->and($tariff->is_active)->toBeTrue()
		->and($tariff->is_featured)->toBeTrue();

	expect(Tariff::query()->count())->toBe(1);
});

test('action обновляет существующий тариф из dto', function (): void {
	$action = app(SaveTariffAction::class);
	$tariff = Tariff::factory()->create([
		'name' => 'Старое имя',
		'description' => 'Старое описание',
		'duration_days' => 7,
		'downloads_limit' => 20,
		'price' => 249,
		'is_active' => true,
		'is_featured' => false,
	]);
	$data = TariffData::from([
		'name' => 'Год',
		'description' => null,
		'durationDays' => 365,
		'downloadsLimit' => 500,
		'price' => 2990,
		'isActive' => false,
		'isFeatured' => true,
	]);

	$updatedTariff = $action->handle($data, $tariff);

	expect($updatedTariff->id)->toBe($tariff->id)
		->and($updatedTariff->name)->toBe('Год')
		->and($updatedTariff->description)->toBeNull()
		->and($updatedTariff->duration_days)->toBe(365)
		->and($updatedTariff->downloads_limit)->toBe(500)
		->and($updatedTariff->price)->toBe(2990)
		->and($updatedTariff->is_active)->toBeFalse()
		->and($updatedTariff->is_featured)->toBeTrue();

	expect(Tariff::query()->count())->toBe(1);
});

test('action отправляет тариф в конец новой группы при смене активности', function (): void {
	$action = app(SaveTariffAction::class);

	$activeFirst = Tariff::factory()->create([
		'name' => 'Активный 1',
		'is_active' => true,
	]);
	$activeSecond = Tariff::factory()->create([
		'name' => 'Активный 2',
		'is_active' => true,
	]);
	$archivedFirst = Tariff::factory()->inactive()->create([
		'name' => 'Архивный 1',
	]);

	$data = TariffData::from([
		'name' => $activeSecond->name,
		'description' => $activeSecond->description,
		'durationDays' => $activeSecond->duration_days,
		'downloadsLimit' => $activeSecond->downloads_limit,
		'price' => $activeSecond->price,
		'isActive' => false,
		'isFeatured' => $activeSecond->is_featured,
	]);

	$updatedTariff = $action->handle($data, $activeSecond);

	$activePositions = Tariff::query()
		->active()
		->ordered()
		->pluck('position', 'name')
		->all();
	$archivedPositions = Tariff::query()
		->archived()
		->ordered()
		->pluck('position', 'name')
		->all();

	expect($updatedTariff->is_active)->toBeFalse()
		->and($activePositions)->toBe([
			$activeFirst->name => 0,
		])
		->and($archivedPositions)->toBe([
			$archivedFirst->name => 0,
			$activeSecond->name => 1,
		]);
});
