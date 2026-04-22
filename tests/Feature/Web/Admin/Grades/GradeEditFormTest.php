<?php

use App\Livewire\Admin\Grades\Edit as GradeEdit;
use App\Models\Grade;
use App\Models\Quarter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ может обновить класс через форму редактирования без изменения номера', function (): void {
	$admin = User::factory()->admin()->create();
	$grade = Grade::create([
		'number' => 5,
		'slug' => '5-klass',
	]);

	Livewire::actingAs($admin)
		->test(GradeEdit::class, ['grade' => $grade])
		->set('form.seoTitle', 'Пятый класс')
		->call('updateGrade')
		->assertHasNoErrors()
		->assertRedirect(route('admin.grades.index'));

	$this->assertDatabaseHas('grades', [
		'id' => $grade->id,
		'number' => 5,
		'slug' => '5-klass',
		'seo_title' => 'Пятый класс',
	]);
});

test('страница редактирования класса содержит вкладки свойств и четвертей без ручной сортировки', function (): void {
	$admin = User::factory()->admin()->create();
	$grade = Grade::create(['number' => 8, 'slug' => '8-klass']);
	Quarter::create(['grade_id' => $grade->id, 'number' => 1]);

	$response = $this->actingAs($admin)->get(route('admin.grades.edit', $grade));

	$response->assertOk()
		->assertSeeText('Свойства')
		->assertSeeText('Четверти')
		->assertDontSeeText('Создать четверть')
		->assertDontSee('x-sort', false)
		->assertDontSee('wire:model="form.number"', false)
		->assertDontSee('wire:model="form.slug"', false);
});
