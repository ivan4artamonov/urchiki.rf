<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
	public function logout(): void
	{
		Auth::logout();

		request()->session()->invalidate();
		request()->session()->regenerateToken();

		$this->redirectRoute('admin.login', navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.dashboard')
			->layout('admin');
	}
}
