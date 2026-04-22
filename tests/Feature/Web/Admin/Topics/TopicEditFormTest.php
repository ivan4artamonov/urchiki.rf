<?php

use App\Livewire\Admin\Topics\Edit as TopicEdit;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ может обновить тему через форму редактирования', function (): void {
	$admin = User::factory()->admin()->create();
	$subject = Subject::factory()->create();
	$topic = Topic::factory()->create([
		'subject_id' => $subject->id,
		'name' => 'Старая тема',
	]);

	Livewire::actingAs($admin)
		->test(TopicEdit::class, ['topic' => $topic])
		->set('form.name', 'Новая тема')
		->call('updateTopic')
		->assertHasNoErrors()
		->assertRedirect(route('admin.subjects.edit', $subject));

	$this->assertDatabaseHas('topics', [
		'id' => $topic->id,
		'subject_id' => $subject->id,
		'name' => 'Новая тема',
	]);
});

