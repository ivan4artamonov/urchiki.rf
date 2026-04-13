<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Classes extends Component
{
	public function render()
	{
		return view('livewire.admin.classes')
			->layout('admin', ['adminSectionTitle' => 'классы']);
	}
}
