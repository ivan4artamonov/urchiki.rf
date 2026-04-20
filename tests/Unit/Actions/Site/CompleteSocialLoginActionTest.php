<?php

use App\Actions\Site\CompleteSocialLoginAction;
use App\Enums\SocialLoginProvider;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function (): void {
    Auth::logout();
});

function socialiteUserMock(
    string $id,
    ?string $email,
    ?string $name = null,
    ?string $nickname = null,
): SocialiteUser {
    $mock = Mockery::mock(SocialiteUser::class);
    $mock->shouldReceive('getId')->andReturn($id);
    $mock->shouldReceive('getEmail')->andReturn($email);
    $mock->shouldReceive('getName')->andReturn($name);
    $mock->shouldReceive('getNickname')->andReturn($nickname);

    return $mock;
}

test('handle при существующей привязке соцсети логинит и возвращает того же пользователя', function (): void {
    $user = User::factory()->create(['email' => 'linked@example.com']);
    SocialAccount::query()->create([
        'user_id' => $user->id,
        'provider' => SocialLoginProvider::Vkontakte->value,
        'provider_user_id' => 'ext-1',
    ]);

    $social = socialiteUserMock('ext-1', 'other@example.com', 'OAuth', null);

    $result = app(CompleteSocialLoginAction::class)->handle(SocialLoginProvider::Vkontakte, $social);

    expect($result->is($user))->toBeTrue()
        ->and(Auth::id())->toBe($user->id);
});

test('handle создаёт пользователя и social_accounts при новом email', function (): void {
    $social = socialiteUserMock('new-ext', 'brand-new-unit@example.com', 'Новый', null);

    $result = app(CompleteSocialLoginAction::class)->handle(SocialLoginProvider::Yandex, $social);

    expect($result)->not->toBeNull()
        ->and($result->email)->toBe('brand-new-unit@example.com')
        ->and($result->name)->toBe('Новый')
        ->and(Auth::id())->toBe($result->id);

    $this->assertDatabaseHas('social_accounts', [
        'user_id' => $result->id,
        'provider' => SocialLoginProvider::Yandex->value,
        'provider_user_id' => 'new-ext',
    ]);
});

test('handle объединяет с существующим пользователем по email и создаёт привязку', function (): void {
    $existing = User::factory()->create([
        'email' => 'merge-unit@example.com',
        'name' => 'Было',
        'password' => null,
    ]);

    $social = socialiteUserMock('merge-ext', 'merge-unit@example.com', 'С OAuth', null);

    $result = app(CompleteSocialLoginAction::class)->handle(SocialLoginProvider::Mailru, $social);

    expect($result->is($existing))->toBeTrue()
        ->and(Auth::id())->toBe($existing->id);

    $this->assertDatabaseHas('social_accounts', [
        'user_id' => $existing->id,
        'provider' => SocialLoginProvider::Mailru->value,
        'provider_user_id' => 'merge-ext',
    ]);
});

test('handle возвращает null если у провайдера нет email', function (): void {
    $social = socialiteUserMock('no-mail', null, 'Имя', null);

    $result = app(CompleteSocialLoginAction::class)->handle(SocialLoginProvider::Vkontakte, $social);

    expect($result)->toBeNull()
        ->and(Auth::check())->toBeFalse();
});
