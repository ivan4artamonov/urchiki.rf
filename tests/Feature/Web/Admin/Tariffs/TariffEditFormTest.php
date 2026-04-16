<?php

use App\Livewire\Admin\Tariffs\Edit as TariffEdit;
use App\Models\Tariff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ может обновить тариф через форму редактирования', function (): void {
	$admin = User::factory()->admin()->create();
	$tariff = Tariff::factory()->create([
		'name' => 'Старый тариф',
		'description' => 'Старое описание',
		'duration_days' => 7,
		'downloads_limit' => 20,
		'price' => 249,
		'is_active' => true,
		'is_featured' => false,
	]);

	Livewire::actingAs($admin)
		->test(TariffEdit::class, ['tariff' => $tariff])
		->set('form.name', 'Обновлённый тариф')
		->set('form.description', 'Новое описание')
		->set('form.durationDays', 30)
		->set('form.downloadsLimit', 60)
		->set('form.price', 499)
		->set('form.isActive', false)
		->set('form.isFeatured', true)
		->call('updateTariff')
		->assertHasNoErrors()
		->assertRedirect(route('admin.tariffs.index'));

	$this->assertDatabaseHas('tariffs', [
		'id' => $tariff->id,
		'name' => 'Обновлённый тариф',
		'description' => 'Новое описание',
		'duration_days' => 30,
		'downloads_limit' => 60,
		'price' => 499,
		'is_active' => false,
		'is_featured' => true,
	]);
});
