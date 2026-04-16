<?php

namespace App\Livewire\Admin\Tariffs;

use App\Actions\Position\UpdateModelPositionAction;
use App\Models\Tariff;
use Exception;
use Livewire\Component;

class Index extends Component
{
	/**
	 * Переместить тариф на новую позицию.
	 *
	 * @param int $itemId ID перетаскиваемого тарифа.
	 * @param int $position Новая позиция тарифа.
	 * @throws Exception
	 */
	public function moveTariff(int $itemId, int $position): void
	{
		app(UpdateModelPositionAction::class)->handle(Tariff::class, $itemId, $position);
	}

	public function render()
	{
		$activeTariffs = Tariff::query()
			->active()
			->ordered()
			->get();

		$archivedTariffs = Tariff::query()
			->archived()
			->ordered()
			->get();

		return view('livewire.admin.tariffs.index')
			->with([
				'activeTariffs' => $activeTariffs,
				'archivedTariffs' => $archivedTariffs,
			])
			->layout('admin', ['adminSectionTitle' => 'тарифы']);
	}

}
