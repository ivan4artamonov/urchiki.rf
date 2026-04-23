<?php

use App\Models\Grade;
use App\Models\Quarter;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Worksheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('четверти по умолчанию сортируются по номеру', function (): void {
	$grade = Grade::create(['number' => 7]);

	Quarter::create(['grade_id' => $grade->id, 'number' => 3]);
	Quarter::create(['grade_id' => $grade->id, 'number' => 1]);
	Quarter::create(['grade_id' => $grade->id, 'number' => 4]);
	Quarter::create(['grade_id' => $grade->id, 'number' => 2]);

	$numbers = Quarter::query()
		->pluck('number')
		->all();

	expect($numbers)->toBe([1, 2, 3, 4]);
});

test('ordinal_label возвращает порядковую метку четверти словами', function (): void {
	$grade = Grade::create(['number' => 5]);

	$firstQuarter = Quarter::create(['grade_id' => $grade->id, 'number' => 1]);
	$fourthQuarter = Quarter::create(['grade_id' => $grade->id, 'number' => 4]);

	expect($firstQuarter->ordinal_label)->toBe('Первая')
		->and($fourthQuarter->ordinal_label)->toBe('Четвертая');
});

test('short_label возвращает метку в формате «N четверть»', function (): void {
	$grade = Grade::create(['number' => 9]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 2]);

	expect($quarter->short_label)->toBe('2 четверть');
});

test('full_label возвращает полную метку на базе ordinal_label', function (): void {
	$grade = Grade::create(['number' => 10]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 3]);

	expect($quarter->full_label)->toBe('Третья четверть');
});

test('массовое заполнение поддерживает seo-поля и article у четверти', function (): void {
	$grade = Grade::create(['number' => 6]);

	$quarter = Quarter::create([
		'grade_id' => $grade->id,
		'number' => 1,
		'seo_title' => 'Первая четверть 6 класса',
		'seo_description' => 'Описание страницы первой четверти',
		'seo_keywords' => 'первая четверть, 6 класс',
		'article' => 'Текст статьи для первой четверти',
	]);

	expect($quarter->seo_title)->toBe('Первая четверть 6 класса')
		->and($quarter->seo_description)->toBe('Описание страницы первой четверти')
		->and($quarter->seo_keywords)->toBe('первая четверть, 6 класс')
		->and($quarter->article)->toBe('Текст статьи для первой четверти');
});

test('связь worksheets возвращает рабочие листы четверти', function (): void {
	$grade = Grade::create(['number' => 6]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 1]);
	$otherQuarter = Quarter::create(['grade_id' => $grade->id, 'number' => 2]);
	$subject = Subject::create(['name' => 'История']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Средневековье']);

	Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Лист по Средневековью',
	]);
	Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $otherQuarter->id,
		'title' => 'Лист по Возрождению',
	]);

	$worksheetTitles = $quarter->worksheets()->pluck('title')->all();

	expect($worksheetTitles)->toBe(['Лист по Средневековью']);
});

