<?php

use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('админ видит список предметов на странице admin.subjects.index', function (): void {
	$admin = User::factory()->admin()->create();
	$subject = Subject::factory()->create([
		'name' => 'Математика',
		'slug' => 'matematika',
	]);
	Topic::factory()->count(2)->create(['subject_id' => $subject->id]);

	$response = $this->actingAs($admin)->get(route('admin.subjects.index'));

	$response->assertOk()
		->assertSeeText('Предметы')
		->assertSeeText('Создать предмет')
		->assertSeeText('Математика')
		->assertSeeText('matematika')
		->assertSeeText('2');
});

