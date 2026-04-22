<?php

use App\Livewire\Admin\Subjects\Create as SubjectCreate;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ может создать предмет через форму в админке', function (): void {
	$admin = User::factory()->admin()->create();

	Livewire::actingAs($admin)
		->test(SubjectCreate::class)
		->set('form.name', 'Русский язык')
		->set('form.slug', 'russkiy-yazyk')
		->set('form.seoTitle', 'SEO заголовок')
		->set('form.article', 'Текст статьи')
		->call('createSubject')
		->assertHasNoErrors()
		->assertRedirect(route('admin.subjects.index'));

	$this->assertDatabaseHas('subjects', [
		'name' => 'Русский язык',
		'slug' => 'russkiy-yazyk',
		'seo_title' => 'SEO заголовок',
		'article' => 'Текст статьи',
	]);
});

test('админ может загрузить иконку при создании предмета', function (): void {
	Storage::fake('public');
	$admin = User::factory()->admin()->create();

	Livewire::actingAs($admin)
		->test(SubjectCreate::class)
		->set('form.name', 'Физика')
		->set('form.slug', 'fizika')
		->set('form.icon', UploadedFile::fake()->image('physics.png'))
		->call('createSubject')
		->assertHasNoErrors();

	$subject = Subject::query()->where('slug', 'fizika')->firstOrFail();

	expect($subject->media()->where('collection_name', 'icon')->count())->toBe(1)
		->and($subject->icon_url)->not->toBeNull();
});

test('форма создания предмета показывает ошибки валидации обязательных полей', function (): void {
	$admin = User::factory()->admin()->create();

	Livewire::actingAs($admin)
		->test(SubjectCreate::class)
		->set('form.name', '')
		->call('createSubject')
		->assertHasErrors([
			'form.name' => 'required',
		]);

	expect(Subject::query()->count())->toBe(0);
});

