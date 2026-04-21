<?php

use App\Models\Grade;
use App\Models\Quarter;
use Database\Seeders\QuarterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('QuarterSeeder создаёт по 4 четверти для каждого класса', function (): void {
	for ($number = 1; $number <= 3; $number++) {
		Grade::create(['number' => $number]);
	}

	$this->seed(QuarterSeeder::class);

	expect(Quarter::count())->toBe(12);

	Grade::query()->each(function (Grade $grade): void {
		$numbers = Quarter::query()
			->where('grade_id', $grade->id)
			->orderBy('number')
			->pluck('number')
			->all();

		expect($numbers)->toBe([1, 2, 3, 4]);
	});
});

test('QuarterSeeder идемпотентен и не создаёт дубликаты при повторном запуске', function (): void {
	for ($number = 1; $number <= 2; $number++) {
		Grade::create(['number' => $number]);
	}

	$this->seed(QuarterSeeder::class);
	$this->seed(QuarterSeeder::class);

	expect(Quarter::count())->toBe(8);
});
