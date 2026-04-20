<?php

namespace App\Http\Controllers\Site;

use App\Actions\Site\CompleteSocialLoginAction;
use App\Enums\SocialLoginProvider;
use App\Support\SiteLoginRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Throwable;

class SiteSocialAuthController
{
    public function redirect(SocialLoginProvider $provider): RedirectResponse
    {
        return Socialite::driver($provider->driverName())->redirect();
    }

    public function callback(SocialLoginProvider $provider, CompleteSocialLoginAction $completeSocialLogin): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->to(route('site.home'));
        }

        try {
            $socialUser = Socialite::driver($provider->driverName())->user();
        } catch (InvalidStateException) {
            return redirect()->route('site.login')
                ->with('social_login_error', 'Сессия входа устарела. Попробуйте снова.');
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('site.login')
                ->with('social_login_error', 'Не удалось завершить вход через выбранный сервис.');
        }

        $user = $completeSocialLogin->handle($provider, $socialUser);

        if ($user === null) {
            return redirect()->route('site.login')
                ->with('social_login_error', 'Сервис не передал адрес электронной почты. Войдите по коду из письма или разрешите доступ к email в настройках приложения.');
        }

        return redirect()->to(SiteLoginRedirect::urlAfterLogin());
    }
}
