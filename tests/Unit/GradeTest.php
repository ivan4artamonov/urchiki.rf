<?php

use App\Models\Grade;
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
