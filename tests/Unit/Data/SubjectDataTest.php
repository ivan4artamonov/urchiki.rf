<?php

use App\Data\SubjectData;
use Tests\TestCase;

uses(TestCase::class);

test('dto предмета создается из массива с ожидаемыми типами', function (): void {
	$data = SubjectData::from([
		'name' => 'Математика',
		'slug' => 'matematika',
		'position' => '2',
	]);

	expect($data)->toBeInstanceOf(SubjectData::class)
		->and($data->name)->toBe('Математика')
		->and($data->slug)->toBe('matematika')
		->and($data->position)->toBeInt()->toBe(2);
});

test('dto предмета маппит поля в атрибуты модели', function (): void {
	$data = SubjectData::from([
		'name' => 'Русский язык',
		'slug' => 'russkij-yazyk',
		'position' => 1,
	]);

	expect($data->toModelAttributes())->toBe([
		'name' => 'Русский язык',
		'slug' => 'russkij-yazyk',
		'position' => 1,
	]);
});

test('dto предмета не добавляет slug и position в атрибуты когда они не заданы', function (): void {
	$data = SubjectData::from([
		'name' => 'Окружающий мир',
		'slug' => null,
		'position' => null,
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
