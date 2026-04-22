<?php

use App\Actions\Grade\SaveGradeAction;
use App\Data\GradeData;
use App\Models\Grade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('action обновляет существующий класс из dto', function (): void {
	$action = app(SaveGradeAction::class);
	$grade = Grade::create([
		'number' => 6,
		'slug' => '6-klass',
		'seo_title' => null,
		'seo_description' => null,
		'seo_keywords' => null,
		'article' => null,
	]);
	$data = GradeData::from([
		'seo_title' => 'Шестой класс',
		'seo_description' => 'Описание страницы класса',
		'seo_keywords' => '6 класс, школа',
		'article' => 'Статья о шестом классе',
	]);

	$updatedGrade = $action->handle($data, $grade);

	expect($updatedGrade->id)->toBe($grade->id)
		->and($updatedGrade->number)->toBe(6)
		->and($updatedGrade->slug)->toBe('6-klass')
		->and($updatedGrade->seo_title)->toBe('Шестой класс')
		->and($updatedGrade->seo_description)->toBe('Описание страницы класса')
		->and($updatedGrade->seo_keywords)->toBe('6 класс, школа')
		->and($updatedGrade->article)->toBe('Статья о шестом классе');
});
