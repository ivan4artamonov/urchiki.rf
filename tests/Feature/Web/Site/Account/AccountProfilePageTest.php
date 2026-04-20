<?php

use App\Livewire\Site\Account\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('гость при открытии профиля перенаправляется на главную', function (): void {
    $this->get(route('site.account.profile'))
        ->assertRedirect(route('site.home'));
});

test('авторизованный пользователь видит страницу профиля', function (): void {
    $user = User::factory()->create([
        'name' => 'Иван',
        'email' => 'ivan@example.com',
        'password' => null,
    ]);

    $this->actingAs($user)
        ->get(route('site.account.profile'))
        ->assertOk()
        ->assertSeeLivewire(Profile::class)
        ->assertSee('Личные данные', false);
});

test('пользователь может сохранить имя и почту', function (): void {
    $user = User::factory()->create([
        'name' => 'Старое имя',
        'email' => 'old@example.com',
        'password' => null,
        'email_verified_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(Profile::class)
        ->set('form.name', 'Новое имя')
        ->set('form.email', 'new@example.com')
        ->call('saveProfile')
        ->assertRedirect(route('site.account.profile'));

    $this->get(route('site.account.profile'))
        ->assertOk()
        ->assertSee('Изменения сохранены', false);

    $user->refresh();

    expect($user->name)->toBe('Новое имя')
        ->and($user->email)->toBe('new@example.com')
        ->and($user->email_verified_at)->toBeNull();
});

test('нельзя сохранить email занятый другим пользователем', function (): void {
    User::factory()->create(['email' => 'taken@example.com']);

    $user = User::factory()->create([
        'email' => 'mine@example.com',
        'password' => null,
    ]);

    Livewire::actingAs($user)
        ->test(Profile::class)
        ->set('form.email', 'taken@example.com')
        ->call('saveProfile')
        ->assertHasErrors(['form.email']);
});
