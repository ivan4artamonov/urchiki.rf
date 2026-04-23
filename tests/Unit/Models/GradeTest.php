<?php

use App\Models\Grade;
use App\Models\Quarter;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Worksheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('при сохранении без slug подставляется N-klass из номера', function (): void {
	$grade = Grade::create(['number' => 3]);

	expect($grade->slug)->toBe('3-klass')
		->and($grade->number)->toBe(3);
});

test('пустая строка slug перед сохранением заменяется на N-klass', function (): void {
	$grade = Grade::create(['number' => 6, 'slug' => '']);

	expect($grade->slug)->toBe('6-klass');
});

test('если slug задан явно, он не перезаписывается', function (): void {
	$grade = Grade::create(['number' => 4, 'slug' => 'chetyre-klass']);

	expect($grade->slug)->toBe('chetyre-klass')
		->and($grade->number)->toBe(4);
});

test('label возвращает строку вида «N класс»', function (): void {
	$grade = new Grade(['number' => 11]);

	expect($grade->label)->toBe('11 класс');
});

test('ключ маршрутизации — slug', function (): void {
	$grade = new Grade;

	expect($grade->getRouteKeyName())->toBe('slug');
});

test('массовое заполнение поддерживает seo-поля и article у класса', function (): void {
	$grade = Grade::create([
		'number' => 8,
		'seo_title' => '8 класс - учебные материалы',
		'seo_description' => 'Описание страницы 8 класса',
		'seo_keywords' => '8 класс, школьная программа',
		'article' => 'Текст статьи для страницы 8 класса',
	]);

	expect($grade->seo_title)->toBe('8 класс - учебные материалы')
		->and($grade->seo_description)->toBe('Описание страницы 8 класса')
		->and($grade->seo_keywords)->toBe('8 класс, школьная программа')
		->and($grade->article)->toBe('Текст статьи для страницы 8 класса');
});

test('по умолчанию классы возвращаются в порядке номера', function (): void {
	Grade::create(['number' => 10]);
	Grade::create(['number' => 2]);
	Grade::create(['number' => 7]);

	$numbers = Grade::query()
		->pluck('number')
		->all();

	expect($numbers)->toBe([2, 7, 10]);
});

test('связь worksheets возвращает рабочие листы класса через четверти', function (): void {
	$grade = Grade::create(['number' => 7]);
	$otherGrade = Grade::create(['number' => 8]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 1]);
	$otherQuarter = Quarter::create(['grade_id' => $otherGrade->id, 'number' => 1]);
	$subject = Subject::create(['name' => 'Английский язык']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Глагол to be']);

	Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Лист по to be',
	]);
	Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $otherQuarter->id,
		'title' => 'Лист по Present Simple',
	]);

	$worksheetTitles = $grade->worksheets()->pluck('title')->all();

	expect($worksheetTitles)->toBe(['Лист по to be']);
});
