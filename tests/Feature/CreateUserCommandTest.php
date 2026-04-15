<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('команда user:create создаёт пользователя с указанными параметрами', function (): void {
	$this->artisan('user:create')
		->expectsQuestion('Email', 'new-admin@example.com')
		->expectsQuestion('Имя (по умолчанию пустое)', 'Новый Админ')
		->expectsQuestion('Пароль (по умолчанию пустой)', 'secret-123')
		->expectsConfirmation('Сделать пользователя администратором?', 'yes')
		->expectsOutputToContain('успешно создан')
		->assertSuccessful();

	$user = User::query()->where('email', 'new-admin@example.com')->first();

	expect($user)->not->toBeNull()
		->and($user->name)->toBe('Новый Админ')
		->and($user->is_admin)->toBeTrue()
		->and(Hash::check('secret-123', (string) $user->password))->toBeTrue();
});

test('команда user:create повторно запрашивает email если он уже занят', function (): void {
	User::factory()->create(['email' => 'taken@example.com']);

	$this->artisan('user:create')
		->expectsQuestion('Email', 'taken@example.com')
		->expectsOutput('Пользователь с таким email уже существует.')
		->expectsQuestion('Email', 'fresh@example.com')
		->expectsQuestion('Имя (по умолчанию пустое)', '')
		->expectsQuestion('Пароль (по умолчанию пустой)', '')
		->expectsConfirmation('Сделать пользователя администратором?', 'no')
		->assertSuccessful();

	$user = User::query()->where('email', 'fresh@example.com')->first();

	expect($user)->not->toBeNull()
		->and($user->name)->toBeNull()
		->and($user->password)->toBeNull()
		->and($user->is_admin)->toBeFalse();
});
