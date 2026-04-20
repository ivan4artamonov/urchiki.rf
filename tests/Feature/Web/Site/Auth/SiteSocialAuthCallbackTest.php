<?php

use App\Enums\SocialLoginProvider;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as OAuthUser;

uses(RefreshDatabase::class);

afterEach(function (): void {
    Socialite::clearResolvedInstances();
});

beforeEach(function (): void {
    config([
        'services.vkontakte.client_id' => 'test-vk-id',
        'services.vkontakte.client_secret' => 'test-vk-secret',
        'services.vkontakte.redirect' => 'http://localhost/auth/social/vkontakte/callback',
    ]);
});

test('неизвестный провайдер в пути даёт 404', function (): void {
    $this->get('/auth/social/unknown/callback')->assertNotFound();
});

test('гость перенаправляется на фейковый URL провайдера при переходе на social redirect', function (): void {
    Socialite::fake(SocialLoginProvider::Vkontakte->value);

    $this->get(route('site.social.redirect', SocialLoginProvider::Vkontakte))
        ->assertRedirect('https://socialite.fake/vkontakte/authorize');
});

test('callback создаёт пользователя и привязку соцсети при новом email', function (): void {
    $oauthUser = new OAuthUser;
    $oauthUser->id = 'vk-new-1';
    $oauthUser->email = 'oauth-new@example.com';
    $oauthUser->name = 'OAuth User';

    Socialite::fake(SocialLoginProvider::Vkontakte->value, $oauthUser);

    $this->get(route('site.social.callback', SocialLoginProvider::Vkontakte))
        ->assertRedirect(route('site.home'));

    $this->assertDatabaseHas('users', [
        'email' => 'oauth-new@example.com',
        'is_admin' => false,
    ]);

    $user = User::query()->where('email', 'oauth-new@example.com')->firstOrFail();
    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseHas('social_accounts', [
        'user_id' => $user->id,
        'provider' => SocialLoginProvider::Vkontakte->value,
        'provider_user_id' => 'vk-new-1',
    ]);
});

test('callback объединяет с существующим пользователем по email и создаёт привязку', function (): void {
    $existing = User::factory()->create([
        'email' => 'merge@example.com',
        'name' => 'Existing',
        'password' => null,
    ]);

    $oauthUser = new OAuthUser;
    $oauthUser->id = 'vk-merge-1';
    $oauthUser->email = 'merge@example.com';
    $oauthUser->name = 'From VK';

    Socialite::fake(SocialLoginProvider::Vkontakte->value, $oauthUser);

    $this->get(route('site.social.callback', SocialLoginProvider::Vkontakte))
        ->assertRedirect(route('site.home'));

    $this->assertAuthenticatedAs($existing);
    $this->assertSame(1, SocialAccount::query()->where('user_id', $existing->id)->count());
    $this->assertDatabaseHas('social_accounts', [
        'user_id' => $existing->id,
        'provider' => SocialLoginProvider::Vkontakte->value,
        'provider_user_id' => 'vk-merge-1',
    ]);
});

test('callback без email в ответе провайдера возвращает на страницу входа с сообщением', function (): void {
    $oauthUser = new OAuthUser;
    $oauthUser->id = 'vk-no-email';
    $oauthUser->email = null;

    Socialite::fake(SocialLoginProvider::Vkontakte->value, $oauthUser);

    $response = $this->get(route('site.social.callback', SocialLoginProvider::Vkontakte));

    $response->assertRedirect(route('site.login'));
    $response->assertSessionHas('social_login_error');
    $this->assertGuest();
});
