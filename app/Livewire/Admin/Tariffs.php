<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Tariffs extends Component
{
	public function render()
	{
		return view('livewire.admin.tariffs')
			->layout('admin', ['adminSectionTitle' => 'тарифы']);
	}
}
