<?php

use App\Livewire\Admin\Tariffs\Create as TariffCreate;
use App\Models\Tariff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ может создать тариф через форму в админке', function (): void {
	$admin = User::factory()->admin()->create();

	Livewire::actingAs($admin)
		->test(TariffCreate::class)
		->set('form.name', 'Месяц')
		->set('form.description', 'Основной тариф для регулярной работы')
		->set('form.durationDays', 30)
		->set('form.downloadsLimit', 60)
		->set('form.price', 499)
		->set('form.isActive', true)
		->set('form.isFeatured', true)
		->call('createTariff')
		->assertHasNoErrors()
		->assertRedirect(route('admin.tariffs.index'));

	$this->assertDatabaseHas('tariffs', [
		'name' => 'Месяц',
		'description' => 'Основной тариф для регулярной работы',
		'duration_days' => 30,
		'downloads_limit' => 60,
		'price' => 499,
		'is_active' => true,
		'is_featured' => true,
	]);
});

test('форма создания тарифа показывает ошибки валидации обязательных полей', function (): void {
	$admin = User::factory()->admin()->create();

	Livewire::actingAs($admin)
		->test(TariffCreate::class)
		->set('form.name', '')
		->set('form.durationDays', 0)
		->set('form.downloadsLimit', 0)
		->set('form.price', -1)
		->call('createTariff')
		->assertHasErrors([
			'form.name' => 'required',
			'form.durationDays' => 'min',
			'form.downloadsLimit' => 'min',
			'form.price' => 'min',
		]);

	expect(Tariff::query()->count())->toBe(0);
});
