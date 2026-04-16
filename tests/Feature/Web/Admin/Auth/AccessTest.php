<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('гость перенаправляется на страницу входа в админку', function (): void {
	$this->get(route('admin.dashboard'))
		->assertRedirect('/admin/login');
});

test('не администратор получает 403 на маршрутах админки', function (): void {
	$user = User::factory()->create(['is_admin' => false]);

	$this->actingAs($user)
		->get(route('admin.dashboard'))
		->assertForbidden();
});

test('администратор получает доступ к админке', function (): void {
	$admin = User::factory()->admin()->create();

	$this->actingAs($admin)
		->get(route('admin.dashboard'))
		->assertOk();
});
