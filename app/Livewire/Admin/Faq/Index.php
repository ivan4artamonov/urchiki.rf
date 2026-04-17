<?php

namespace App\Livewire\Admin\Faq;

use App\Actions\Position\UpdateModelPositionAction;
use App\Models\FaqItem;
use Exception;
use Livewire\Component;

class Index extends Component
{
	/**
	 * Переместить запись ЧаВо на новую позицию.
	 *
	 * @param int $itemId ID перетаскиваемой записи.
	 * @param int $position Новая позиция записи.
	 * @throws Exception
	 */
	public function moveFaqItem(int $itemId, int $position): void
	{
		app(UpdateModelPositionAction::class)->handle(FaqItem::class, $itemId, $position);
	}

	public function render()
	{
		$activeFaqItems = FaqItem::query()
			->active()
			->ordered()
			->get();

		$archivedFaqItems = FaqItem::query()
			->active(false)
			->ordered()
			->get();

		return view('livewire.admin.faq.index')
			->with([
				'activeFaqItems' => $activeFaqItems,
				'archivedFaqItems' => $archivedFaqItems,
			])
			->layout('admin', ['adminSectionTitle' => 'чаво']);
	}
}
