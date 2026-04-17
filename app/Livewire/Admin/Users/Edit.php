<?php

namespace App\Livewire\Admin\Users;

use App\Livewire\Admin\Forms\UserForm;
use App\Models\User;
use App\Support\Notification;
use Livewire\Component;

class Edit extends Component
{
	public UserForm $form;

	public function mount(User $user): void
	{
		$this->form->fillFromUser($user);
	}

	public function updateUser(): void
	{
		$this->form->save();

		Notification::success('Пользователь успешно обновлён.');
		$this->redirectRoute('admin.users.index', navigate: true);
	}

	public function render()
	{
		return view('livewire.admin.users.edit')
			->layout('admin', ['adminSectionTitle' => 'редактирование пользователя']);
	}
}
