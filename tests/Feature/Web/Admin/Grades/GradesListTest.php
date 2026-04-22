<?php

use App\Models\Grade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('админ видит список классов на странице admin.grades.index', function (): void {
	$admin = User::factory()->admin()->create();
	$grade = Grade::create([
		'number' => 7,
		'slug' => '7-klass',
	]);

	$response = $this->actingAs($admin)->get(route('admin.grades.index'));

	$response->assertOk()
		->assertSeeText('Классы')
		->assertSeeText('7 класс')
		->assertSeeText('7-klass')
		->assertDontSeeText('Создать класс');
});
