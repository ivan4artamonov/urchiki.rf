<?php

use App\Actions\Topic\SaveTopicAction;
use App\Data\TopicData;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('action создает новую тему из dto', function (): void {
	$action = app(SaveTopicAction::class);
	$subject = Subject::factory()->create();
	$data = TopicData::from([
		'subject_id' => $subject->id,
		'name' => 'Дроби',
		'slug' => 'drobi',
		'seo_title' => 'SEO title',
		'seo_description' => 'SEO description',
		'seo_keywords' => 'дроби, математика',
		'article' => 'Текст статьи',
	]);

	$topic = $action->handle($data);

	expect($topic->exists)->toBeTrue()
		->and($topic->id)->toBeInt()
		->and($topic->subject_id)->toBe($subject->id)
		->and($topic->name)->toBe('Дроби')
		->and($topic->slug)->toBe('drobi')
		->and($topic->seo_title)->toBe('SEO title')
		->and($topic->seo_description)->toBe('SEO description')
		->and($topic->seo_keywords)->toBe('дроби, математика')
		->and($topic->article)->toBe('Текст статьи');

	expect(Topic::query()->count())->toBe(1);
});

test('action обновляет существующую тему из dto', function (): void {
	$action = app(SaveTopicAction::class);
	$subject = Subject::factory()->create();
	$topic = Topic::factory()->create([
		'subject_id' => $subject->id,
		'name' => 'Старое имя',
		'slug' => 'staraia-tema',
		'seo_title' => 'Старый title',
		'seo_description' => 'Старое описание',
		'seo_keywords' => 'старые ключи',
		'article' => 'Старая статья',
	]);
	$data = TopicData::from([
		'subject_id' => $subject->id,
		'name' => 'Новое имя',
		'slug' => 'novaia-tema',
		'seo_title' => null,
		'seo_description' => null,
		'seo_keywords' => null,
		'article' => null,
	]);

	$updatedTopic = $action->handle($data, $topic);

	expect($updatedTopic->id)->toBe($topic->id)
		->and($updatedTopic->subject_id)->toBe($subject->id)
		->and($updatedTopic->name)->toBe('Новое имя')
		->and($updatedTopic->slug)->toBe('novaia-tema')
		->and($updatedTopic->seo_title)->toBe('Старый title')
		->and($updatedTopic->seo_description)->toBe('Старое описание')
		->and($updatedTopic->seo_keywords)->toBe('старые ключи')
		->and($updatedTopic->article)->toBe('Старая статья');

	expect(Topic::query()->count())->toBe(1);
});

test('action переносит тему в начало списка при смене предмета', function (): void {
	$action = app(SaveTopicAction::class);

	$oldSubject = Subject::factory()->create();
	$newSubject = Subject::factory()->create();

	$oldFirstTopic = Topic::factory()->create([
		'subject_id' => $oldSubject->id,
		'name' => 'Старая 1',
	]);
	$topicToMove = Topic::factory()->create([
		'subject_id' => $oldSubject->id,
		'name' => 'Старая 2',
	]);
	$newFirstTopic = Topic::factory()->create([
		'subject_id' => $newSubject->id,
		'name' => 'Новая 1',
	]);
	$newSecondTopic = Topic::factory()->create([
		'subject_id' => $newSubject->id,
		'name' => 'Новая 2',
	]);

	$data = TopicData::from([
		'subject_id' => $newSubject->id,
		'name' => $topicToMove->name,
		'slug' => $topicToMove->slug,
		'seo_title' => $topicToMove->seo_title,
		'seo_description' => $topicToMove->seo_description,
		'seo_keywords' => $topicToMove->seo_keywords,
		'article' => $topicToMove->article,
	]);

	$updatedTopic = $action->handle($data, $topicToMove);

	$oldSubjectTopicNames = Topic::query()
		->where('subject_id', $oldSubject->id)
		->ordered()
		->pluck('name')
		->values()
		->all();
	$newSubjectTopicNames = Topic::query()
		->where('subject_id', $newSubject->id)
		->ordered()
		->pluck('name')
		->values()
		->all();

	expect($updatedTopic->subject_id)->toBe($newSubject->id)
		->and($oldSubjectTopicNames)->toBe([
			$oldFirstTopic->name,
		])
		->and($newSubjectTopicNames[0])->toBe($topicToMove->name)
		->and($newSubjectTopicNames)->toContain($newFirstTopic->name, $newSecondTopic->name);
});
