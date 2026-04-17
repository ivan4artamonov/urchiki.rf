<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Страница админки со списком пользователей.
 */
class Index extends Component
{
	use WithPagination;

	public function render()
	{
		$users = User::query()
			->orderByDesc('id')
			->paginate(20);

		return view('livewire.admin.users.index', [
			'users' => $users,
		])
			->layout('admin', ['adminSectionTitle' => 'пользователи']);
	}
}
