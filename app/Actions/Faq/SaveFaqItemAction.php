<?php

namespace App\Actions\Faq;

use App\Data\FaqItemData;
use App\Models\FaqItem;

/**
 * Создаёт новую запись ЧаВо или обновляет существующую.
 */
class SaveFaqItemAction
{
	/**
	 * Выполняет сохранение записи ЧаВо на основе DTO.
	 */
	public function handle(FaqItemData $data, ?FaqItem $faqItem = null): FaqItem
	{
		$attributes = $data->toModelAttributes();

		if ($faqItem instanceof FaqItem) {
			$groupChanged = $faqItem->is_active !== $attributes['is_active'];

			$faqItem->update($attributes);

			if ($groupChanged) {
				$faqItem->move(-1);
			}

			return $faqItem->refresh();
		}

		return FaqItem::query()->create($attributes);
	}
}
