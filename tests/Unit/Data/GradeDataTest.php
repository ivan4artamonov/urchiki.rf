<?php

use App\Data\GradeData;
use Tests\TestCase;

uses(TestCase::class);

test('dto класса создается из массива с ожидаемыми типами', function (): void {
	$data = GradeData::from([
		'seo_title' => 'SEO заголовок класса',
		'seo_description' => 'SEO описание класса',
		'seo_keywords' => 'класс, школа',
		'article' => 'Текст статьи класса',
	]);

	expect($data)->toBeInstanceOf(GradeData::class)
		->and($data->seo_title)->toBe('SEO заголовок класса')
		->and($data->seo_description)->toBe('SEO описание класса')
		->and($data->seo_keywords)->toBe('класс, школа')
		->and($data->article)->toBe('Текст статьи класса');
});

test('dto класса маппит поля в атрибуты модели', function (): void {
	$data = GradeData::from([
		'seo_title' => 'Заголовок',
		'seo_description' => 'Описание',
		'seo_keywords' => 'ключи',
		'article' => 'Статья',
	]);

	expect($data->toModelAttributes())->toBe([
		'seo_title' => 'Заголовок',
		'seo_description' => 'Описание',
		'seo_keywords' => 'ключи',
		'article' => 'Статья',
	]);
});

test('dto класса маппит null значения без дополнительных полей', function (): void {
	$data = GradeData::from([
		'seo_title' => null,
		'seo_description' => null,
		'seo_keywords' => null,
		'article' => null,
	]);

	expect($data->toModelAttributes())->toBe([
		'seo_title' => null,
		'seo_description' => null,
		'seo_keywords' => null,
		'article' => null,
	]);
});
