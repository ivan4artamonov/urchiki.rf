<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Worksheets extends Component
{
	public function render()
	{
		return view('livewire.admin.worksheets')
			->layout('admin', ['adminSectionTitle' => 'рабочие листы']);
	}
}
