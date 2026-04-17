<?php

namespace App\Livewire\Admin\Users;

use App\Livewire\Admin\Forms\UserForm;
use Livewire\Component;

class Create extends Component
{
	public UserForm $form;

	public function createUser(): void
	{
		$this->form->save();

		session()->flash('user-created', 'Пользователь успешно создан.');
		$this->redirectRoute('admin.users.index', navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.users.create')
			->layout('admin', ['adminSectionTitle' => 'создание пользователя']);
	}
}
