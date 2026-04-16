<?php

use App\Models\Grade;
use Database\Seeders\GradeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('GradeSeeder создаёт 11 параллелей со slug N-klass', function (): void {
	$this->seed(GradeSeeder::class);

	expect(Grade::count())->toBe(11);

	for ($n = 1; $n <= 11; $n++) {
		$grade = Grade::query()->where('number', $n)->first();

		expect($grade)->not->toBeNull()
			->and($grade->slug)->toBe($n . '-klass');
	}
});

test('GradeSeeder идемпотентен: повторный запуск не плодит строки', function (): void {
	$this->seed(GradeSeeder::class);
	$this->seed(GradeSeeder::class);

	expect(Grade::count())->toBe(11);
});
