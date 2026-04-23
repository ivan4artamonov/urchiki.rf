<?php

use App\Data\WorksheetData;
use Tests\TestCase;

uses(TestCase::class);

test('dto рабочего листа создается из массива с ожидаемыми типами', function (): void {
	$data = WorksheetData::from([
		'topic_id' => '7',
		'quarter_id' => '3',
		'title' => 'Рабочий лист по дробям',
		'seo_title' => 'SEO заголовок листа',
		'seo_description' => 'SEO описание листа',
		'seo_keywords' => 'лист, дроби, математика',
		'article' => 'Текст статьи рабочего листа',
	]);

	expect($data)->toBeInstanceOf(WorksheetData::class)
		->and($data->topic_id)->toBeInt()->toBe(7)
		->and($data->quarter_id)->toBeInt()->toBe(3)
		->and($data->title)->toBe('Рабочий лист по дробям')
		->and($data->seo_title)->toBe('SEO заголовок листа')
		->and($data->seo_description)->toBe('SEO описание листа')
		->and($data->seo_keywords)->toBe('лист, дроби, математика')
		->and($data->article)->toBe('Текст статьи рабочего листа');
});

test('dto рабочего листа маппит поля в атрибуты модели', function (): void {
	$data = WorksheetData::from([
		'topic_id' => 4,
		'quarter_id' => 2,
		'title' => 'Лист по русскому языку',
		'seo_title' => 'SEO заголовок',
		'seo_description' => 'SEO описание',
		'seo_keywords' => 'русский язык, орфография',
		'article' => 'Статья по рабочему листу',
	]);

	expect($data->toModelAttributes())->toBe([
		'topic_id' => 4,
		'quarter_id' => 2,
		'title' => 'Лист по русскому языку',
		'seo_title' => 'SEO заголовок',
		'seo_description' => 'SEO описание',
		'seo_keywords' => 'русский язык, орфография',
		'article' => 'Статья по рабочему листу',
	]);
});

test('dto рабочего листа не добавляет seo и article поля когда они не заданы', function (): void {
	$data = WorksheetData::from([
		'topic_id' => 9,
		'quarter_id' => 1,
		'title' => 'Лист без SEO',
		'seo_title' => null,
		'seo_description' => null,
		'seo_keywords' => null,
		'article' => null,
	]);

	expect($data->toModelAttributes())->toBe([
		'topic_id' => 9,
		'quarter_id' => 1,
		'title' => 'Лист без SEO',
	]);
});
