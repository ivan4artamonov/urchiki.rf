<?php

use App\Livewire\Admin\Subjects\Edit as SubjectEdit;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

test('админ может обновить иконку предмета через форму редактирования', function (): void {
	Storage::fake('public');
	$admin = User::factory()->admin()->create();
	$subject = Subject::factory()->create([
		'name' => 'Химия',
		'slug' => 'himiya',
	]);

	Livewire::actingAs($admin)
		->test(SubjectEdit::class, ['subject' => $subject])
		->set('form.icon', UploadedFile::fake()->image('chemistry.png'))
		->call('updateSubject')
		->assertHasNoErrors();

	$subject->refresh();

	expect($subject->media()->where('collection_name', 'icon')->count())->toBe(1)
		->and($subject->icon_url)->not->toBeNull();
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

