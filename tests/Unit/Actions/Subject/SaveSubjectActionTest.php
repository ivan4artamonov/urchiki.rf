<?php

use App\Actions\Subject\SaveSubjectAction;
use App\Data\SubjectData;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('action создает новый предмет из dto', function (): void {
	$action = app(SaveSubjectAction::class);
	$data = SubjectData::from([
		'name' => 'Математика',
		'slug' => 'matematika',
		'seo_title' => 'Заголовок',
		'seo_description' => 'Описание',
		'seo_keywords' => 'математика, предмет',
		'article' => 'Текст статьи',
	]);

	$subject = $action->handle($data);

	expect($subject->exists)->toBeTrue()
		->and($subject->id)->toBeInt()
		->and($subject->name)->toBe('Математика')
		->and($subject->slug)->toBe('matematika')
		->and($subject->seo_title)->toBe('Заголовок')
		->and($subject->seo_description)->toBe('Описание')
		->and($subject->seo_keywords)->toBe('математика, предмет')
		->and($subject->article)->toBe('Текст статьи');

	expect(Subject::query()->count())->toBe(1);
});

test('action обновляет существующий предмет из dto', function (): void {
	$action = app(SaveSubjectAction::class);
	$subject = Subject::factory()->create([
		'name' => 'Старое название',
		'slug' => 'old-slug',
		'seo_title' => 'Старый title',
		'seo_description' => 'Старое описание',
		'seo_keywords' => 'старые ключи',
		'article' => 'Старая статья',
	]);
	$data = SubjectData::from([
		'name' => 'Новое название',
		'slug' => 'new-slug',
		'seo_title' => null,
		'seo_description' => null,
		'seo_keywords' => null,
		'article' => null,
	]);

	$updatedSubject = $action->handle($data, $subject);

	expect($updatedSubject->id)->toBe($subject->id)
		->and($updatedSubject->name)->toBe('Новое название')
		->and($updatedSubject->slug)->toBe('new-slug')
		->and($updatedSubject->seo_title)->toBe('Старый title')
		->and($updatedSubject->seo_description)->toBe('Старое описание')
		->and($updatedSubject->seo_keywords)->toBe('старые ключи')
		->and($updatedSubject->article)->toBe('Старая статья');

	expect(Subject::query()->count())->toBe(1);
});
