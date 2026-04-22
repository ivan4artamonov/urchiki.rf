<?php

use App\Actions\Quarter\SaveQuarterAction;
use App\Data\QuarterData;
use App\Models\Grade;
use App\Models\Quarter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('action обновляет существующую четверть из dto', function (): void {
	$action = app(SaveQuarterAction::class);
	$grade = Grade::create(['number' => 8, 'slug' => '8-klass']);
	$quarter = Quarter::create([
		'grade_id' => $grade->id,
		'number' => 3,
		'seo_title' => null,
		'seo_description' => null,
		'seo_keywords' => null,
		'article' => null,
	]);
	$data = QuarterData::from([
		'seo_title' => 'Третья четверть',
		'seo_description' => 'Описание третьей четверти',
		'seo_keywords' => 'третья четверть, 8 класс',
		'article' => 'Статья о третьей четверти',
	]);

	$updatedQuarter = $action->handle($data, $quarter);

	expect($updatedQuarter->id)->toBe($quarter->id)
		->and($updatedQuarter->grade_id)->toBe($grade->id)
		->and($updatedQuarter->number)->toBe(3)
		->and($updatedQuarter->seo_title)->toBe('Третья четверть')
		->and($updatedQuarter->seo_description)->toBe('Описание третьей четверти')
		->and($updatedQuarter->seo_keywords)->toBe('третья четверть, 8 класс')
		->and($updatedQuarter->article)->toBe('Статья о третьей четверти');
});
