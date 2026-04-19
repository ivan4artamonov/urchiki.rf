<?php

namespace App\Livewire\Site;

use App\Models\FaqItem;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

/**
 * Публичная страница «Вопросы и ответы»: активные записи из админки.
 */
class Faq extends Component
{
    /**
     * Возвращает представление страницы с вопросами и ответами.
     *
     * @return View
     */
    public function render(): View
    {
        /** @var Collection<int, FaqItem> $faqItems */
        $faqItems = FaqItem::query()
            ->active()
            ->ordered()
            ->get();

        return view('livewire.site.faq', [
            'faqItems' => $faqItems,
        ])->layout('site', [
            'title' => 'Вопросы и ответы — '.config('app.name'),
        ]);
    }
}
