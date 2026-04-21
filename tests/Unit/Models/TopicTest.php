<?php

use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('массовое заполнение работает для атрибутов темы', function (): void {
	$subject = Subject::create(['name' => 'Математика']);

	$topic = Topic::create([
		'subject_id' => $subject->id,
		'name' => 'Алгебра',
		'position' => 3,
	]);

	expect($topic->subject_id)->toBe($subject->id)
		->and($topic->name)->toBe('Алгебра')
		->and($topic->position)->toBe(3);
});

test('касты приводят поля темы к корректным типам', function (): void {
	$subject = Subject::create(['name' => 'Русский язык']);

	$topic = Topic::query()->create([
		'subject_id' => (string) $subject->id,
		'name' => 'Синтаксис',
		'position' => '2',
	]);

	$topic->refresh();

	expect($topic->subject_id)->toBeInt()->toBe($subject->id)
		->and($topic->position)->toBeInt()->toBe(2);
});

test('scope ordered сортирует темы по позиции', function (): void {
	$subject = Subject::create(['name' => 'Литература']);

	Topic::create(['subject_id' => $subject->id, 'name' => 'Поэзия', 'position' => 3]);
	Topic::create(['subject_id' => $subject->id, 'name' => 'Драма', 'position' => 1]);
	Topic::create(['subject_id' => $subject->id, 'name' => 'Проза', 'position' => 2]);

	$orderedNames = Topic::query()
		->where('subject_id', $subject->id)
		->ordered()
		->pluck('name')
		->all();

	expect($orderedNames)->toBe([
		'Драма',
		'Проза',
		'Поэзия',
	]);
});

test('связь subject возвращает предмет темы', function (): void {
	$subject = Subject::create(['name' => 'Биология']);
	$topic = Topic::create([
		'subject_id' => $subject->id,
		'name' => 'Клетка',
	]);

	expect($topic->subject)->not->toBeNull()
		->and($topic->subject->is($subject))->toBeTrue();
});

test('при создании без position позиция назначается автоматически в рамках предмета', function (): void {
	$firstSubject = Subject::create(['name' => 'История']);
	$secondSubject = Subject::create(['name' => 'География']);

	$firstTopic = Topic::create([
		'subject_id' => $firstSubject->id,
		'name' => 'Древний мир',
	]);
	$secondTopic = Topic::create([
		'subject_id' => $firstSubject->id,
		'name' => 'Средние века',
	]);
	$otherSubjectTopic = Topic::create([
		'subject_id' => $secondSubject->id,
		'name' => 'Материки',
	]);

	expect($firstTopic->position)->toBeInt()
		->and($secondTopic->position)->toBeInt()
		->and($otherSubjectTopic->position)->toBeInt()
		->and($firstTopic->position)->toBeLessThan($secondTopic->position)
		->and($otherSubjectTopic->position)->toBe($firstTopic->position);
});
