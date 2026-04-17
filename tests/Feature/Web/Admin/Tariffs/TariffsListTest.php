<?php

use App\Models\Tariff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('админ видит список активных и архивных тарифов на странице admin.tariffs.index', function (): void {
	$admin = User::factory()->admin()->create();

	$activeTariff = Tariff::factory()->create([
		'name' => 'Активный базовый',
		'duration_days' => 30,
		'downloads_limit' => 60,
		'price' => 499,
		'is_featured' => true,
	]);

	$archivedTariff = Tariff::factory()->inactive()->create([
		'name' => 'Архивный годовой',
		'duration_days' => 365,
		'downloads_limit' => 500,
		'price' => 2990,
		'is_featured' => false,
	]);

	$response = $this->actingAs($admin)->get(route('admin.tariffs.index'));

	$response->assertOk()
		->assertSeeText('Тарифы')
		->assertSeeText('Активные тарифы')
		->assertSeeText('Архивные тарифы')
		->assertSeeText('Создать тариф')
		->assertSeeText('Активный базовый')
		->assertSeeText('Архивный годовой')
		->assertSeeText((string) $activeTariff->duration_days)
		->assertSeeText((string) $activeTariff->downloads_limit)
		->assertSeeText((string) $activeTariff->price)
		->assertSeeText((string) $archivedTariff->duration_days)
		->assertSeeText((string) $archivedTariff->downloads_limit)
		->assertSeeText((string) $archivedTariff->price)
		->assertSeeText('Да')
		->assertSeeText('Нет');
});
