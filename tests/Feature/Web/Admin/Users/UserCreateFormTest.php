<?php

use App\Livewire\Admin\Users\Create as UserCreate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ видит страницу создания пользователя', function (): void {
	$admin = User::factory()->admin()->create();

	$response = $this->actingAs($admin)->get(route('admin.users.create'));

	$response->assertOk()
		->assertSeeText('Создание пользователя')
		->assertSeeText('Создать пользователя');
});

test('админ может создать не-администратора без пароля', function (): void {
	$admin = User::factory()->admin()->create();

	Livewire::actingAs($admin)
		->test(UserCreate::class)
		->set('form.name', 'Пользователь сайта')
		->set('form.email', 'site-user@example.com')
		->set('form.password', '')
		->set('form.isAdmin', false)
		->call('createUser')
		->assertHasNoErrors()
		->assertRedirect(route('admin.users.index'));

	$this->assertDatabaseHas('users', [
		'name' => 'Пользователь сайта',
		'email' => 'site-user@example.com',
		'is_admin' => false,
		'password' => null,
	]);
});

test('форма создания требует пароль для администратора', function (): void {
	$admin = User::factory()->admin()->create();

	Livewire::actingAs($admin)
		->test(UserCreate::class)
		->set('form.name', 'Новый админ')
		->set('form.email', 'new-admin@example.com')
		->set('form.password', '')
		->set('form.isAdmin', true)
		->call('createUser')
		->assertHasErrors([
			'form.password' => 'required',
		]);

	$this->assertDatabaseMissing('users', [
		'email' => 'new-admin@example.com',
	]);
});
