<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('админ видит список пользователей на странице admin.users.index', function (): void {
	$admin = User::factory()->admin()->create([
		'name' => 'Главный админ',
		'email' => 'admin-list@example.com',
	]);

	$regularUser = User::factory()->create([
		'name' => 'Обычный пользователь',
		'email' => 'user-list@example.com',
		'is_admin' => false,
	]);

	$secondAdmin = User::factory()->admin()->create([
		'name' => 'Второй админ',
		'email' => 'second-admin@example.com',
	]);

	$response = $this->actingAs($admin)->get(route('admin.users.index'));

	$response->assertOk()
		->assertSeeText('Пользователи')
		->assertSeeText('admin-list@example.com')
		->assertSeeText('user-list@example.com')
		->assertSeeText('second-admin@example.com')
		->assertSeeText('Обычный пользователь')
		->assertSeeText('Да')
		->assertSeeText('Нет');

	expect($regularUser->is_admin)->toBeFalse()
		->and($secondAdmin->is_admin)->toBeTrue();
});

