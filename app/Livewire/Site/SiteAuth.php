<?php

namespace App\Livewire\Site;

use App\Livewire\Site\Forms\SiteEmailAuthForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Страница входа и регистрации на сайте по коду из письма.
 */
class SiteAuth extends Component
{
    public SiteEmailAuthForm $form;

    /**
     * Шаг сценария: ввод адреса электронной почты или ввод кода из письма.
     *
     * @var 'email'|'code'
     */
    public string $step = 'email';

    /**
     * Перенаправляет авторизованного пользователя с страницы гостя.
     */
    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirectRoute('site.home', navigate: true);
        }
    }

    /**
     * Отправляет код на электронную почту и переключает на шаг ввода кода.
     */
    public function sendCode(): void
    {
        if (! $this->form->sendLoginCode()) {
            return;
        }

        $this->step = 'code';
        $this->form->reset('code');
        $this->resetValidation();
    }

    /**
     * Повторно отправляет код (остаёмся на шаге ввода кода).
     */
    public function resendCode(): void
    {
        if (! $this->form->sendLoginCode()) {
            return;
        }

        $this->form->reset('code');
        $this->resetValidation();
    }

    /**
     * Возвращает к вводу адреса электронной почты.
     */
    public function backToEmail(): void
    {
        $this->step = 'email';
        $this->form->reset('code');
        $this->resetValidation();
    }

    /**
     * Проверяет код и перенаправляет на главную при успехе.
     */
    public function verifyCode(): void
    {
        if (! $this->form->verifyAndLogin()) {
            return;
        }

        $this->redirectAfterSiteLogin();
    }

    /**
     * Выполняет редирект после входа на сайт: «intended» из сессии не используется, если ведёт в админку.
     */
    private function redirectAfterSiteLogin(): void
    {
        $default = route('site.home');
        $intended = session()->pull('url.intended');

        if (! is_string($intended) || $intended === '') {
            $this->redirect($default, navigate: true);

            return;
        }

        $path = parse_url($intended, PHP_URL_PATH);
        $path = is_string($path) ? $path : '';

        if (str_starts_with($path, '/admin')) {
            $this->redirect($default, navigate: true);

            return;
        }

        $this->redirect($intended, navigate: true);
    }

    /**
     * Возвращает представление страницы авторизации.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.site.auth')
            ->layout('site', [
                'title' => 'Вход и регистрация — '.config('app.name'),
            ]);
    }
}
