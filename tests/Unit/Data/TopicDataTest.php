<?php

use App\Data\TopicData;
use Tests\TestCase;

uses(TestCase::class);

test('dto темы создается из массива с ожидаемыми типами', function (): void {
	$data = TopicData::from([
		'subject_id' => '3',
		'name' => 'Алгебра',
		'position' => '2',
	]);

	expect($data)->toBeInstanceOf(TopicData::class)
		->and($data->subject_id)->toBeInt()->toBe(3)
		->and($data->name)->toBe('Алгебра')
		->and($data->position)->toBeInt()->toBe(2);
});

test('dto темы маппит поля в атрибуты модели', function (): void {
	$data = TopicData::from([
		'subject_id' => 7,
		'name' => 'Геометрия',
		'position' => 1,
	]);

	expect($data->toModelAttributes())->toBe([
		'subject_id' => 7,
		'name' => 'Геометрия',
		'position' => 1,
	]);
});

test('dto темы не добавляет position в атрибуты когда он не задан', function (): void {
	$data = TopicData::from([
		'subject_id' => 5,
		'name' => 'Синтаксис',
		'position' => null,
	]);

	expect($data->toModelAttributes())->toBe([
		'subject_id' => 5,
		'name' => 'Синтаксис',
	]);
});
