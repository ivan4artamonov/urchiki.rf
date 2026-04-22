<?php

namespace App\Livewire\Admin\Forms;

use App\Actions\Faq\SaveFaqItemAction;
use App\Data\FaqItemData;
use App\Models\FaqItem;
use Livewire\Attributes\Validate;
use Livewire\Form;

/**
 * Форма для создания и редактирования записи ЧаВо в админке.
 */
class FaqItemForm extends Form
{
	public ?FaqItem $faqItem = null;

	#[Validate('required|string|min:5|max:255')]
	public string $question = '';

	#[Validate('required|string|min:5|max:10000')]
	public string $answer = '';

	public bool $isActive = true;

	/**
	 * Пользовательские сообщения валидации полей формы.
	 *
	 * @return array<string, string>
	 */
	public function messages(): array
	{
		return [
			'question.required' => 'Поле "Вопрос" обязательно для заполнения.',
			'question.string' => 'Поле "Вопрос" должно быть строкой.',
			'question.min' => 'Поле "Вопрос" должно содержать не менее 5 символов.',
			'question.max' => 'Поле "Вопрос" не должно превышать 255 символов.',
			'answer.required' => 'Поле "Ответ" обязательно для заполнения.',
			'answer.string' => 'Поле "Ответ" должно быть строкой.',
			'answer.min' => 'Поле "Ответ" должно содержать не менее 5 символов.',
			'answer.max' => 'Поле "Ответ" не должно превышать 10000 символов.',
		];
	}

	/**
	 * Сохраняет запись ЧаВо: создаёт новую или обновляет существующую.
	 */
	public function save(): void
	{
		$validated = $this->validate();
		$isNewFaqItem = ! ($this->faqItem instanceof FaqItem);
		$data = FaqItemData::from([
			'question' => (string) $validated['question'],
			'answer' => (string) $validated['answer'],
			'isActive' => $this->isActive,
		]);

		$this->faqItem = app(SaveFaqItemAction::class)->handle($data, $this->faqItem);

		if ($isNewFaqItem) {
			$this->reset();
			$this->isActive = true;
		}
	}

	/**
	 * Заполняет форму данными существующей записи ЧаВо для редактирования.
	 */
	public function fillFromFaqItem(FaqItem $faqItem): void
	{
		$this->faqItem = $faqItem;
		$this->question = $faqItem->question;
		$this->answer = $faqItem->answer;
		$this->isActive = $faqItem->is_active;
	}
}
