<?php

use App\Livewire\Admin\Users\Edit as UserEdit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ видит страницу редактирования пользователя', function (): void {
	$admin = User::factory()->admin()->create();
	$user = User::factory()->create([
		'name' => 'Тестовый пользователь',
		'email' => 'edit-user@example.com',
	]);

	$response = $this->actingAs($admin)->get(route('admin.users.edit', $user));

	$response->assertOk()
		->assertSeeText('Редактирование пользователя')
		->assertSeeText('Сохранить изменения');
});

test('форма редактирования требует пароль при повышении в администратора без пароля', function (): void {
	$admin = User::factory()->admin()->create();
	$user = User::factory()->create([
		'email' => 'site-promote@example.com',
		'is_admin' => false,
		'password' => null,
	]);

	Livewire::actingAs($admin)
		->test(UserEdit::class, ['user' => $user])
		->set('form.isAdmin', true)
		->set('form.password', '')
		->call('updateUser')
		->assertHasErrors([
			'form.password' => 'required',
		]);

	$this->assertDatabaseHas('users', [
		'id' => $user->id,
		'is_admin' => false,
		'password' => null,
	]);
});

test('админ может повысить пользователя в администратора с паролем', function (): void {
	$admin = User::factory()->admin()->create();
	$user = User::factory()->create([
		'email' => 'site-promote-success@example.com',
		'is_admin' => false,
		'password' => null,
	]);

	Livewire::actingAs($admin)
		->test(UserEdit::class, ['user' => $user])
		->set('form.isAdmin', true)
		->set('form.password', 'secret123')
		->call('updateUser')
		->assertHasNoErrors()
		->assertRedirect(route('admin.users.index'));

	$updatedUser = $user->fresh();

	expect($updatedUser)->not->toBeNull();
	expect($updatedUser->is_admin)->toBeTrue()
		->and($updatedUser->password)->not->toBeNull()
		->and(password_verify('secret123', $updatedUser->password))->toBeTrue();
});
