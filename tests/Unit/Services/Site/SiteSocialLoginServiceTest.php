<?php

use App\Models\User;
use App\Services\Site\SiteSocialLoginService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function (): void {
    Auth::logout();
});

test('displayNameFromSocialiteUser берёт полное имя если оно непустое', function (): void {
    $social = Mockery::mock(SocialiteUser::class);
    $social->shouldReceive('getName')->andReturn('  Иван Петров  ');
    $social->shouldReceive('getNickname')->never();

    $name = app(SiteSocialLoginService::class)->displayNameFromSocialiteUser($social);

    expect($name)->toBe('Иван Петров');
});

test('displayNameFromSocialiteUser при пустом имени берёт nickname', function (): void {
    $social = Mockery::mock(SocialiteUser::class);
    $social->shouldReceive('getName')->andReturn('   ');
    $social->shouldReceive('getNickname')->andReturn(' ivan_p ');

    $name = app(SiteSocialLoginService::class)->displayNameFromSocialiteUser($social);

    expect($name)->toBe('ivan_p');
});

test('displayNameFromSocialiteUser возвращает null если нет ни имени ни nickname', function (): void {
    $social = Mockery::mock(SocialiteUser::class);
    $social->shouldReceive('getName')->andReturn(null);
    $social->shouldReceive('getNickname')->andReturn(null);

    $name = app(SiteSocialLoginService::class)->displayNameFromSocialiteUser($social);

    expect($name)->toBeNull();
});

test('syncDisplayNameFromSocialite не меняет пользователя если имя уже задано', function (): void {
    $user = User::factory()->create(['name' => 'Старый', 'email' => 'has-name@example.com']);
    $social = Mockery::mock(SocialiteUser::class);
    $social->shouldReceive('getName')->never();
    $social->shouldReceive('getNickname')->never();

    app(SiteSocialLoginService::class)->syncDisplayNameFromSocialite($user, $social);

    expect($user->fresh()->name)->toBe('Старый');
});

test('syncDisplayNameFromSocialite подставляет имя из профиля если у пользователя имя пустое', function (): void {
    $user = User::factory()->create(['name' => null, 'email' => 'noname@example.com']);
    $social = Mockery::mock(SocialiteUser::class);
    $social->shouldReceive('getName')->andReturn('Из OAuth');
    $social->shouldReceive('getNickname')->never();

    app(SiteSocialLoginService::class)->syncDisplayNameFromSocialite($user, $social);

    expect($user->fresh()->name)->toBe('Из OAuth');
});

test('loginWebUser авторизует пользователя в guard по умолчанию', function (): void {
    $user = User::factory()->create(['email' => 'login@example.com']);

    app(SiteSocialLoginService::class)->loginWebUser($user);

    expect(Auth::check())->toBeTrue()
        ->and(Auth::id())->toBe($user->id);
});
