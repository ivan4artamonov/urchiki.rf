<?php

namespace App\Livewire\Admin\Tariffs;

use Livewire\Component;

class Index extends Component
{
	public function render()
	{
		return view('livewire.admin.tariffs.index')
			->layout('admin', ['adminSectionTitle' => 'тарифы']);
	}
}
