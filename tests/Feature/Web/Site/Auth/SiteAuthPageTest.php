<?php

use App\Livewire\Site\SiteAuth;
use App\Mail\SiteLoginCodeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('гость видит страницу входа и регистрации', function (): void {
    $response = $this->get(route('site.login'));

    $response->assertOk();
    $response->assertSeeLivewire(SiteAuth::class);
    $response->assertSee('Войти в Урчики', false);
});

test('авторизованный пользователь перенаправляется с страницы входа на главную', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('site.login'))
        ->assertRedirect(route('site.home'));
});

test('после ввода адреса электронной почты отправляется письмо с кодом и открывается шаг кода', function (): void {
    Mail::fake();

    Livewire::test(SiteAuth::class)
        ->set('form.email', 'otp@example.com')
        ->call('sendCode')
        ->assertSet('step', 'code')
        ->assertHasNoErrors();

    Mail::assertSent(SiteLoginCodeMail::class, function (SiteLoginCodeMail $mail): bool {
        return preg_match('/^\d{4}$/', $mail->code) === 1;
    });
});

test('верный код входит в существующий аккаунт', function (): void {
    Mail::fake();

    $user = User::factory()->create([
        'email' => 'exists@example.com',
        'password' => null,
    ]);

    $component = Livewire::test(SiteAuth::class)
        ->set('form.email', $user->email)
        ->call('sendCode')
        ->assertSet('step', 'code');

    $code = '';
    Mail::assertSent(SiteLoginCodeMail::class, function (SiteLoginCodeMail $mail) use (&$code): bool {
        $code = $mail->code;

        return true;
    });

    $component->set('form.code', $code)
        ->call('verifyCode')
        ->assertRedirect(route('site.home'));

    $this->assertAuthenticatedAs($user);
});

test('верный код создаёт аккаунт и авторизует нового пользователя', function (): void {
    Mail::fake();

    $component = Livewire::test(SiteAuth::class)
        ->set('form.email', 'brand-new@example.com')
        ->call('sendCode')
        ->assertSet('step', 'code');

    $code = '';
    Mail::assertSent(SiteLoginCodeMail::class, function (SiteLoginCodeMail $mail) use (&$code): bool {
        $code = $mail->code;

        return true;
    });

    $component->set('form.code', $code)
        ->call('verifyCode')
        ->assertRedirect(route('site.home'));

    $this->assertDatabaseHas('users', [
        'email' => 'brand-new@example.com',
        'is_admin' => false,
    ]);

    $newUser = User::query()->where('email', 'brand-new@example.com')->firstOrFail();
    $this->assertAuthenticatedAs($newUser);
    $this->assertNull($newUser->password);
});

test('после входа по коду не срабатывает intended на админку из сессии', function (): void {
    Mail::fake();

    session(['url.intended' => route('admin.dashboard')]);

    $user = User::factory()->create([
        'email' => 'intended-admin@example.com',
        'password' => null,
    ]);

    $component = Livewire::test(SiteAuth::class)
        ->set('form.email', $user->email)
        ->call('sendCode');

    $code = '';
    Mail::assertSent(SiteLoginCodeMail::class, function (SiteLoginCodeMail $mail) use (&$code): bool {
        $code = $mail->code;

        return true;
    });

    $component->set('form.code', $code)
        ->call('verifyCode')
        ->assertRedirect(route('site.home'));
});

test('неверный код показывает ошибку', function (): void {
    Mail::fake();

    $user = User::factory()->create(['email' => 'wrong@example.com', 'password' => null]);

    Livewire::test(SiteAuth::class)
        ->set('form.email', $user->email)
        ->call('sendCode')
        ->set('form.code', '0000')
        ->call('verifyCode')
        ->assertHasErrors(['form.code']);
});
