<?php

use App\Data\SubjectData;
use Tests\TestCase;

uses(TestCase::class);

test('dto предмета создается из массива с ожидаемыми типами', function (): void {
	$data = SubjectData::from([
		'name' => 'Математика',
		'slug' => 'matematika',
		'position' => '2',
		'seo_title' => 'Математика - 5 класс',
		'seo_description' => 'Описание страницы математики',
		'seo_keywords' => 'математика, 5 класс',
		'article' => 'Текст статьи о предмете',
	]);

	expect($data)->toBeInstanceOf(SubjectData::class)
		->and($data->name)->toBe('Математика')
		->and($data->slug)->toBe('matematika')
		->and($data->position)->toBeInt()->toBe(2)
		->and($data->seo_title)->toBe('Математика - 5 класс')
		->and($data->seo_description)->toBe('Описание страницы математики')
		->and($data->seo_keywords)->toBe('математика, 5 класс')
		->and($data->article)->toBe('Текст статьи о предмете');
});

test('dto предмета маппит поля в атрибуты модели', function (): void {
	$data = SubjectData::from([
		'name' => 'Русский язык',
		'slug' => 'russkij-yazyk',
		'position' => 1,
		'seo_title' => 'Русский язык - теория',
		'seo_description' => 'Описание страницы русского языка',
		'seo_keywords' => 'русский язык, правила',
		'article' => 'Подробная статья по русскому языку',
	]);

	expect($data->toModelAttributes())->toBe([
		'name' => 'Русский язык',
		'slug' => 'russkij-yazyk',
		'position' => 1,
		'seo_title' => 'Русский язык - теория',
		'seo_description' => 'Описание страницы русского языка',
		'seo_keywords' => 'русский язык, правила',
		'article' => 'Подробная статья по русскому языку',
	]);
});

test('dto предмета не добавляет slug и position в атрибуты когда они не заданы', function (): void {
	$data = SubjectData::from([
		'name' => 'Окружающий мир',
		'slug' => null,
		'position' => null,
		'seo_title' => null,
		'seo_description' => null,
		'seo_keywords' => null,
		'article' => null,
	]);

	expect($data->toModelAttributes())->toBe([
		'name' => 'Окружающий мир',
	]);
});

test('dto предмета не добавляет пустой slug в атрибуты', function (): void {
	$data = SubjectData::from([
		'name' => 'Литература',
		'slug' => '',
		'position' => null,
	]);

	expect($data->toModelAttributes())->toBe([
		'name' => 'Литература',
	]);
});
