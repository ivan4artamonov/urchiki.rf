<?php

use App\Models\Grade;
use App\View\Composers\GradeComposer;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('передаёт в представление классы, отсортированные по number', function (): void {
	Grade::create(['number' => 5]);
	Grade::create(['number' => 2]);

	$view = \Mockery::mock(View::class);
	$view->shouldReceive('with')
		->once()
		->with('grades', \Mockery::on(function ($grades): bool {
			return $grades->count() === 2
				&& $grades->pluck('number')->all() === [2, 5];
		}));

	app(GradeComposer::class)->compose($view);
});

test('передаёт пустую коллекцию если в БД нет классов', function (): void {
	$view = \Mockery::mock(View::class);
	$view->shouldReceive('with')
		->once()
		->with('grades', \Mockery::on(fn ($grades): bool => $grades->isEmpty()));

	app(GradeComposer::class)->compose($view);
});
