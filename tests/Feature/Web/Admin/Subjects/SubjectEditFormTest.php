<?php

use App\Livewire\Admin\Subjects\Edit as SubjectEdit;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ может обновить предмет через форму редактирования', function (): void {
	$admin = User::factory()->admin()->create();
	$subject = Subject::factory()->create([
		'name' => 'Старое название',
		'slug' => 'old-subject',
	]);

	Livewire::actingAs($admin)
		->test(SubjectEdit::class, ['subject' => $subject])
		->set('form.name', 'Новое название')
		->set('form.slug', 'new-subject')
		->call('updateSubject')
		->assertHasNoErrors()
		->assertRedirect(route('admin.subjects.index'));

	$this->assertDatabaseHas('subjects', [
		'id' => $subject->id,
		'name' => 'Новое название',
		'slug' => 'new-subject',
	]);
});

test('страница редактирования предмета содержит вкладки свойств и тем', function (): void {
	$admin = User::factory()->admin()->create();
	$subject = Subject::factory()->create(['name' => 'Математика']);

	$response = $this->actingAs($admin)->get(route('admin.subjects.edit', $subject));

	$response->assertOk()
		->assertSeeText('Свойства')
		->assertSeeText('Темы')
		->assertSeeText('Создать тему');
});

