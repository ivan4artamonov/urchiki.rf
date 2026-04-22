<?php

use App\Livewire\Admin\Topics\Create as TopicCreate;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ может создать тему через форму в админке', function (): void {
	$admin = User::factory()->admin()->create();
	$subject = Subject::factory()->create();

	Livewire::actingAs($admin)
		->test(TopicCreate::class, ['subject' => $subject])
		->set('form.name', 'Уравнения')
		->set('form.seoTitle', 'SEO для темы')
		->call('createTopic')
		->assertHasNoErrors()
		->assertRedirect(route('admin.subjects.edit', $subject));

	$this->assertDatabaseHas('topics', [
		'subject_id' => $subject->id,
		'name' => 'Уравнения',
		'seo_title' => 'SEO для темы',
	]);
});

test('форма создания темы показывает ошибки валидации обязательных полей', function (): void {
	$admin = User::factory()->admin()->create();
	$subject = Subject::factory()->create();

	Livewire::actingAs($admin)
		->test(TopicCreate::class, ['subject' => $subject])
		->set('form.name', '')
		->call('createTopic')
		->assertHasErrors([
			'form.name' => 'required',
		]);

	expect(Topic::query()->count())->toBe(0);
});

