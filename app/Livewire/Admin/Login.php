<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
	#[Validate('required|email')]
	public string $email = '';

	#[Validate('required|string')]
	public string $password = '';

	public bool $remember = false;

	public function mount(): void
	{
		if (Auth::check() && Auth::user()?->is_admin) {
			$this->redirectRoute('admin.dashboard', navigate: true);
		}
	}

	public function authenticate(): void
	{
		$credentials = [
			...$this->validate(),
			'is_admin' => true,
		];

		if (! Auth::attempt($credentials, $this->remember)) {
			$this->addError('email', 'Неверный email, пароль или нет доступа к админке.');

			return;
		}

		request()->session()->regenerate();

		$this->redirectIntended(route('admin.dashboard'), navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.login')
			->layout('admin', [
				'adminSectionTitle' => 'вход',
				'hideAdminHeader' => true,
			]);
	}
}
