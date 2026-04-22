<?php

namespace App\Livewire\Site;

use App\Models\Subject;
use Livewire\Component;

class Home extends Component
{
	/**
	 * Отображает главную страницу с каталогом предметов.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function render()
	{
		$subjects = Subject::query()
			->ordered()
			->withCount('topics')
			->get();

		return view('livewire.site.home')
			->with([
				'subjects' => $subjects,
			])
			->layout('site');
	}
}
