<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Faq extends Component
{
	public function render()
	{
		return view('livewire.admin.faq')
			->layout('admin', ['adminSectionTitle' => 'чаво']);
	}
}
