<?php

use App\Data\QuarterData;
use Tests\TestCase;

uses(TestCase::class);

test('dto четверти создается из массива с ожидаемыми типами', function (): void {
	$data = QuarterData::from([
		'seo_title' => 'SEO заголовок четверти',
		'seo_description' => 'SEO описание четверти',
		'seo_keywords' => 'четверть, школа',
		'article' => 'Текст статьи четверти',
	]);

	expect($data)->toBeInstanceOf(QuarterData::class)
		->and($data->seo_title)->toBe('SEO заголовок четверти')
		->and($data->seo_description)->toBe('SEO описание четверти')
		->and($data->seo_keywords)->toBe('четверть, школа')
		->and($data->article)->toBe('Текст статьи четверти');
});

test('dto четверти маппит поля в атрибуты модели', function (): void {
	$data = QuarterData::from([
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

test('dto четверти маппит null значения без дополнительных полей', function (): void {
	$data = QuarterData::from([
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
