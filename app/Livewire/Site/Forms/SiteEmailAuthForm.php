<?php

namespace App\Livewire\Site\Forms;

use App\Actions\Site\CompleteSiteEmailLoginAction;
use App\Actions\Site\SendSiteLoginCodeAction;
use Livewire\Form;

/**
 * Единая форма входа и регистрации на сайте по адресу электронной почты и коду из письма.
 */
class SiteEmailAuthForm extends Form
{
    public string $email = '';

    public string $code = '';

    /**
	 * Правила валидации для шага с адресом электронной почты.
     *
     * @return array<string, mixed>
     */
    public function rulesForEmail(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }

    /**
     * Правила валидации для шага с кодом.
     *
     * @return array<string, mixed>
     */
    public function rulesForCode(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'code' => ['required', 'string', 'regex:/^\d{4}$/'],
        ];
    }

    /**
     * Сообщения валидации.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
			'email.required' => 'Укажите адрес электронной почты.',
			'email.email' => 'Введите корректный адрес электронной почты.',
            'code.required' => 'Введите код из письма.',
            'code.regex' => 'Код должен состоять из 4 цифр.',
        ];
    }

    /**
	 * Отправляет код на указанный адрес электронной почты.
     *
     * @return bool true, если письмо отправлено; false при срабатывании лимита запросов
     */
    public function sendLoginCode(): bool
    {
        $this->validate($this->rulesForEmail(), $this->messages());

        $sent = app(SendSiteLoginCodeAction::class)->handle($this->email);

        if (! $sent) {
            $this->addError('email', 'Слишком много запросов кода. Подождите до часа и попробуйте снова.');

            return false;
        }

        return true;
    }

    /**
     * Проверяет код и выполняет вход или регистрацию.
     *
     * @return bool true при успешной авторизации; false при неверном или просроченном коде
     */
    public function verifyAndLogin(): bool
    {
        $this->validate($this->rulesForCode(), $this->messages());

        $normalized = mb_strtolower(trim($this->email));

        $user = app(CompleteSiteEmailLoginAction::class)->handle($normalized, $this->code);

        if (! $user) {
            $this->addError('code', 'Неверный или устаревший код. Запросите новый.');

            return false;
        }

        return true;
    }
}
