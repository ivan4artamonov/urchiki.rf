<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

test('выход с сайта перенаправляет на страницу, с которой ушли', function (): void {
    $user = User::factory()->create(['is_admin' => false]);
    $previous = url('/subscribe');

    $this->actingAs($user)
        ->from($previous)
        ->post(route('site.logout'))
        ->assertRedirect($previous);

    $this->assertGuest();
});

test('выход из админки по-прежнему ведёт на страницу входа в админку', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->from(route('admin.dashboard'))
        ->post(route('admin.logout'))
        ->assertRedirect(route('admin.login'));

    $this->assertGuest();
});

test('гость при обращении к закрытому маршруту сайта перенаправляется на главную', function (): void {
    Route::get('/__auth_site', fn () => 'ok')->middleware(['web', 'auth']);

    $this->get('/__auth_site')->assertRedirect(route('site.home'));
});
