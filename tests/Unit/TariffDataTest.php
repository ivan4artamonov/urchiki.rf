<?php

use App\Data\TariffData;
use Tests\TestCase;

uses(TestCase::class);

test('dto тарифа создается из массива с ожидаемыми типами', function (): void {
	$data = TariffData::from([
		'name' => 'Месяц',
		'description' => 'Основной тариф',
		'durationDays' => '30',
		'downloadsLimit' => '60',
		'price' => '499',
		'isActive' => 1,
		'isFeatured' => 0,
	]);

	expect($data)->toBeInstanceOf(TariffData::class)
		->and($data->name)->toBe('Месяц')
		->and($data->description)->toBe('Основной тариф')
		->and($data->durationDays)->toBeInt()->toBe(30)
		->and($data->downloadsLimit)->toBeInt()->toBe(60)
		->and($data->price)->toBeInt()->toBe(499)
		->and($data->isActive)->toBeBool()->toBeTrue()
		->and($data->isFeatured)->toBeBool()->toBeFalse();
});

test('dto тарифа маппит поля в атрибуты модели', function (): void {
	$data = TariffData::from([
		'name' => 'Неделя',
		'description' => null,
		'durationDays' => 7,
		'downloadsLimit' => 20,
		'price' => 249,
		'isActive' => true,
		'isFeatured' => false,
	]);

	expect($data->toModelAttributes())->toBe([
		'name' => 'Неделя',
		'description' => null,
		'duration_days' => 7,
		'downloads_limit' => 20,
		'price' => 249,
		'is_active' => true,
		'is_featured' => false,
	]);
});
