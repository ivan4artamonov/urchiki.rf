<?php

namespace App\Livewire\Admin\Forms;

use App\Actions\User\SaveUserAction;
use App\Data\UserData;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Form;

/**
 * Форма для создания и редактирования пользователей в админке.
 */
class UserForm extends Form
{
	public ?User $user = null;

	public string $name = '';

	public string $email = '';

	public string $password = '';

	public bool $isAdmin = false;

	/**
	 * Правила валидации полей формы.
	 *
	 * @return array<string, mixed>
	 */
	public function rules(): array
	{
		$passwordRules = ['nullable', 'string', 'min:8', 'max:255'];

		if ($this->isAdmin && (! ($this->user instanceof User) || blank($this->user->password))) {
			$passwordRules[0] = 'required';
		}

		return [
			'name' => ['nullable', 'string', 'max:255'],
			'email' => [
				'required',
				'email',
				'max:255',
				Rule::unique(User::class, 'email')->ignore($this->user?->id),
			],
			'password' => $passwordRules,
		];
	}

	/**
	 * Пользовательские сообщения валидации для формы.
	 *
	 * @return array<string, string>
	 */
	public function messages(): array
	{
		return [
			'name.max' => 'Поле "Имя" не должно превышать 255 символов.',
			'email.required' => 'Поле "Email" обязательно для заполнения.',
			'email.email' => 'Поле "Email" должно быть корректным email-адресом.',
			'email.max' => 'Поле "Email" не должно превышать 255 символов.',
			'email.unique' => 'Пользователь с таким email уже существует.',
			'password.required' => 'Поле "Пароль" обязательно для администратора.',
			'password.min' => 'Поле "Пароль" должно содержать не менее 8 символов.',
			'password.max' => 'Поле "Пароль" не должно превышать 255 символов.',
		];
	}

	/**
	 * Сохраняет пользователя: создаёт нового или обновляет существующего.
	 */
	public function save(): void
	{
		$validated = $this->validate();
		$isNewUser = ! ($this->user instanceof User);
		$data = UserData::from([
			'name' => trim((string) ($validated['name'] ?? '')) ?: null,
			'email' => (string) $validated['email'],
			'password' => trim((string) ($validated['password'] ?? '')) ?: null,
			'isAdmin' => $this->isAdmin,
		]);

		$this->user = app(SaveUserAction::class)->handle($data, $this->user);

		if ($isNewUser) {
			$this->reset();
		}
	}

	/**
	 * Заполняет форму значениями существующего пользователя.
	 */
	public function fillFromUser(User $user): void
	{
		$this->user = $user;
		$this->name = $user->name ?? '';
		$this->email = $user->email;
		$this->password = '';
		$this->isAdmin = $user->is_admin;
	}
}
