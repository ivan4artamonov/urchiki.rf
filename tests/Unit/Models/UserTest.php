<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('пароль хешируется при сохранении', function (): void {
	$user = User::create([
		'name' => 'Admin',
		'email' => 'admin@example.com',
		'password' => 'secret-123',
		'is_admin' => 1,
	]);

	expect(Hash::check('secret-123', $user->password))->toBeTrue()
		->and($user->password)->not->toBe('secret-123');
});

test('is_admin приводится к boolean', function (): void {
	$user = User::factory()->create(['is_admin' => 1]);

	expect($user->is_admin)->toBeBool()
		->and($user->is_admin)->toBeTrue();
});

test('email_verified_at приводится к datetime', function (): void {
	$user = User::factory()->create();

	expect($user->email_verified_at)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('скрывает password и remember_token при сериализации', function (): void {
	$user = User::factory()->create();

	$data = $user->toArray();

	expect($data)->not->toHaveKey('password')
		->not->toHaveKey('remember_token');
});

test('массовое заполнение разрешено для fillable полей', function (): void {
	$user = new User;
	$user->fill([
		'name' => 'Manager',
		'email' => 'manager@example.com',
		'password' => 'secret-123',
		'is_admin' => false,
	]);

	expect($user->name)->toBe('Manager')
		->and($user->email)->toBe('manager@example.com')
		->and($user->is_admin)->toBeFalse();
});
