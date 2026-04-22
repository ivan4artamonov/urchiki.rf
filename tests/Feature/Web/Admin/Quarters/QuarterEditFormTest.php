<?php

use App\Livewire\Admin\Quarters\Edit as QuarterEdit;
use App\Models\Grade;
use App\Models\Quarter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ может обновить четверть через форму редактирования без изменения номера', function (): void {
	$admin = User::factory()->admin()->create();
	$grade = Grade::create(['number' => 9, 'slug' => '9-klass']);
	$quarter = Quarter::create([
		'grade_id' => $grade->id,
		'number' => 2,
	]);

	Livewire::actingAs($admin)
		->test(QuarterEdit::class, ['quarter' => $quarter])
		->set('form.seoTitle', 'Вторая четверть 9 класса')
		->call('updateQuarter')
		->assertHasNoErrors()
		->assertRedirect(route('admin.grades.edit', $grade));

	$this->assertDatabaseHas('quarters', [
		'id' => $quarter->id,
		'grade_id' => $grade->id,
		'number' => 2,
		'seo_title' => 'Вторая четверть 9 класса',
	]);
});

test('страница редактирования четверти не позволяет редактировать номер', function (): void {
	$admin = User::factory()->admin()->create();
	$grade = Grade::create(['number' => 3, 'slug' => '3-klass']);
	$quarter = Quarter::create([
		'grade_id' => $grade->id,
		'number' => 4,
	]);

	$response = $this->actingAs($admin)->get(route('admin.quarters.edit', $quarter));

	$response->assertOk()
		->assertSeeText('Редактирование четверти')
		->assertDontSee('wire:model="form.number"', false)
		->assertDontSee('wire:model="form.slug"', false)
		->assertSee('readonly', false);
});
