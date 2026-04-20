<?php

namespace App\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Заглушка раздела админки «Предметы и темы».
 */
class SubjectsTopics extends Component
{
    /**
     * Формирует представление страницы раздела.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.admin.subjects-topics')
            ->layout('admin', ['adminSectionTitle' => 'предметы и темы']);
    }
}
