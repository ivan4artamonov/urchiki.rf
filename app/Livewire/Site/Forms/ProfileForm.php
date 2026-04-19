<?php

namespace App\Livewire\Site\Forms;

use App\Actions\Site\UpdateSiteProfileAction;
use App\Data\SiteProfileData;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

/**
 * Форма редактирования личных данных пользователя на сайте.
 */
class ProfileForm extends Form
{
    public string $name = '';

    public string $email = '';

    /**
     * Возвращает правила валидации полей формы.
     *
     * @return array<string, mixed> Правила для Livewire Form
     */
    public function rules(): array
    {
        $user = Auth::user();

        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($user instanceof User ? $user->id : null),
            ],
        ];
    }

    /**
     * Возвращает пользовательские сообщения валидации.
     *
     * @return array<string, string> Тексты ошибок по ключам правил
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Укажите адрес электронной почты.',
            'email.email' => 'Введите корректный адрес электронной почты.',
            'email.unique' => 'Этот адрес уже привязан к другому аккаунту.',
            'name.max' => 'Имя не должно превышать 255 символов.',
        ];
    }

    /**
     * Заполняет форму данными авторизованного пользователя.
     *
     * @return void
     */
    public function fillFromAuthUser(): void
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            return;
        }

        $this->name = $user->name ?? '';
        $this->email = $user->email;
    }

	/**
	 * Валидирует ввод и сохраняет профиль текущего пользователя в базе.
	 *
	 * @return void
	 * @throws Exception
	 */
    public function save(): void
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            return;
        }

        $this->email = User::normalizeEmail($this->email);

        $validated = $this->validate();

        $name = trim((string) ($validated['name'] ?? ''));

        $data = SiteProfileData::from([
            'name' => $name === '' ? null : $name,
            'email' => (string) $validated['email'],
        ]);

        $updated = app(UpdateSiteProfileAction::class)->handle($user, $data);

        Auth::setUser($updated);

        $this->name = $updated->name ?? '';
        $this->email = $updated->email;
    }
}
