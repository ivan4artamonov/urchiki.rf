<?php

use App\Models\Grade;
use App\Models\Quarter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('scope ordered сортирует четверти по номеру', function (): void {
	$grade = Grade::create(['number' => 7]);

	Quarter::create(['grade_id' => $grade->id, 'number' => 3]);
	Quarter::create(['grade_id' => $grade->id, 'number' => 1]);
	Quarter::create(['grade_id' => $grade->id, 'number' => 4]);
	Quarter::create(['grade_id' => $grade->id, 'number' => 2]);

	$numbers = Quarter::query()
		->ordered()
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
