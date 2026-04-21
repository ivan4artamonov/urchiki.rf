<?php

use App\Data\TopicData;
use Tests\TestCase;

uses(TestCase::class);

test('dto темы создается из массива с ожидаемыми типами', function (): void {
	$data = TopicData::from([
		'subject_id' => '3',
		'name' => 'Алгебра',
		'position' => '2',
		'seo_title' => 'Алгебра - формулы',
		'seo_description' => 'Описание страницы темы Алгебра',
		'seo_keywords' => 'алгебра, формулы',
		'article' => 'Текст статьи по алгебре',
	]);

	expect($data)->toBeInstanceOf(TopicData::class)
		->and($data->subject_id)->toBeInt()->toBe(3)
		->and($data->name)->toBe('Алгебра')
		->and($data->position)->toBeInt()->toBe(2)
		->and($data->seo_title)->toBe('Алгебра - формулы')
		->and($data->seo_description)->toBe('Описание страницы темы Алгебра')
		->and($data->seo_keywords)->toBe('алгебра, формулы')
		->and($data->article)->toBe('Текст статьи по алгебре');
});

test('dto темы маппит поля в атрибуты модели', function (): void {
	$data = TopicData::from([
		'subject_id' => 7,
		'name' => 'Геометрия',
		'position' => 1,
		'seo_title' => 'Геометрия - основы',
		'seo_description' => 'Описание страницы темы Геометрия',
		'seo_keywords' => 'геометрия, фигуры',
		'article' => 'Текст статьи по геометрии',
	]);

	expect($data->toModelAttributes())->toBe([
		'subject_id' => 7,
		'name' => 'Геометрия',
		'position' => 1,
		'seo_title' => 'Геометрия - основы',
		'seo_description' => 'Описание страницы темы Геометрия',
		'seo_keywords' => 'геометрия, фигуры',
		'article' => 'Текст статьи по геометрии',
	]);
});

test('dto темы не добавляет position в атрибуты когда он не задан', function (): void {
	$data = TopicData::from([
		'subject_id' => 5,
		'name' => 'Синтаксис',
		'position' => null,
		'seo_title' => null,
		'seo_description' => null,
		'seo_keywords' => null,
		'article' => null,
	]);

	expect($data->toModelAttributes())->toBe([
		'subject_id' => 5,
		'name' => 'Синтаксис',
	]);
});
