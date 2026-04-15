<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Страница админки: список учётных записей пользователей.
 */
class Users extends Component
{
    use WithPagination;

    public function render()
    {
        $users = User::query()
            ->orderByDesc('id')
            ->paginate(20);

        return view('livewire.admin.users', [
            'users' => $users,
        ])
            ->layout('admin', ['adminSectionTitle' => 'пользователи']);
    }
}
