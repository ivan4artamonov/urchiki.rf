<?php

use App\Actions\Worksheet\SaveWorksheetAction;
use App\Data\WorksheetData;
use App\Models\Grade;
use App\Models\Quarter;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Worksheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('action создает новый рабочий лист из dto', function (): void {
	$action = app(SaveWorksheetAction::class);
	$subject = Subject::create(['name' => 'Математика']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Дроби']);
	$grade = Grade::create(['number' => 4]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 2]);
	$data = WorksheetData::from([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Рабочий лист по дробям',
		'slug' => 'worksheet-po-drobiam',
		'is_active' => true,
		'seo_title' => 'SEO заголовок',
		'seo_description' => 'SEO описание',
		'seo_keywords' => 'дроби, лист',
		'article' => 'Текст статьи',
	]);

	$worksheet = $action->handle($data);

	expect($worksheet->exists)->toBeTrue()
		->and($worksheet->id)->toBeInt()
		->and($worksheet->topic_id)->toBe($topic->id)
		->and($worksheet->quarter_id)->toBe($quarter->id)
		->and($worksheet->title)->toBe('Рабочий лист по дробям')
		->and($worksheet->slug)->toBe('worksheet-po-drobiam')
		->and($worksheet->is_active)->toBeTrue()
		->and($worksheet->seo_title)->toBe('SEO заголовок')
		->and($worksheet->seo_description)->toBe('SEO описание')
		->and($worksheet->seo_keywords)->toBe('дроби, лист')
		->and($worksheet->article)->toBe('Текст статьи');

	expect(Worksheet::query()->count())->toBe(1);
});

test('action обновляет существующий рабочий лист из dto', function (): void {
	$action = app(SaveWorksheetAction::class);
	$oldSubject = Subject::create(['name' => 'Математика']);
	$newSubject = Subject::create(['name' => 'Русский язык']);
	$oldTopic = Topic::create(['subject_id' => $oldSubject->id, 'name' => 'Дроби']);
	$newTopic = Topic::create(['subject_id' => $newSubject->id, 'name' => 'Орфография']);
	$oldGrade = Grade::create(['number' => 3]);
	$newGrade = Grade::create(['number' => 5]);
	$oldQuarter = Quarter::create(['grade_id' => $oldGrade->id, 'number' => 1]);
	$newQuarter = Quarter::create(['grade_id' => $newGrade->id, 'number' => 4]);
	$worksheet = Worksheet::factory()->create([
		'topic_id' => $oldTopic->id,
		'quarter_id' => $oldQuarter->id,
		'title' => 'Старое название',
		'slug' => 'old-slug',
		'is_active' => true,
		'seo_title' => 'Старый title',
		'seo_description' => 'Старое описание',
		'seo_keywords' => 'старые ключи',
		'article' => 'Старая статья',
	]);
	$data = WorksheetData::from([
		'topic_id' => $newTopic->id,
		'quarter_id' => $newQuarter->id,
		'title' => 'Новое название',
		'slug' => 'new-slug',
		'is_active' => false,
		'seo_title' => null,
		'seo_description' => null,
		'seo_keywords' => null,
		'article' => null,
	]);

	$updatedWorksheet = $action->handle($data, $worksheet);

	expect($updatedWorksheet->id)->toBe($worksheet->id)
		->and($updatedWorksheet->topic_id)->toBe($newTopic->id)
		->and($updatedWorksheet->quarter_id)->toBe($newQuarter->id)
		->and($updatedWorksheet->title)->toBe('Новое название')
		->and($updatedWorksheet->slug)->toBe('new-slug')
		->and($updatedWorksheet->is_active)->toBeFalse()
		->and($updatedWorksheet->seo_title)->toBe('Старый title')
		->and($updatedWorksheet->seo_description)->toBe('Старое описание')
		->and($updatedWorksheet->seo_keywords)->toBe('старые ключи')
		->and($updatedWorksheet->article)->toBe('Старая статья');

	expect(Worksheet::query()->count())->toBe(1);
});
