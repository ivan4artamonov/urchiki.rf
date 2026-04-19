<?php

namespace App\Livewire\Site;

use App\Livewire\Site\Forms\SiteEmailAuthForm;
use App\Support\SiteLoginRedirect;
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
        $this->redirect(SiteLoginRedirect::urlAfterLogin(), navigate: true);
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
