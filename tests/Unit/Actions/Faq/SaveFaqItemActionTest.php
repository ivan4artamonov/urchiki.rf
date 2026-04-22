<?php

use App\Actions\Faq\SaveFaqItemAction;
use App\Data\FaqItemData;
use App\Models\FaqItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('action создает новую запись чаво из dto', function (): void {
	$action = app(SaveFaqItemAction::class);
	$data = FaqItemData::from([
		'question' => 'Что такое рабочие листы?',
		'answer' => 'Рабочий лист — материал в PDF для печати.',
		'isActive' => true,
	]);

	$faqItem = $action->handle($data);

	expect($faqItem->exists)->toBeTrue()
		->and($faqItem->id)->toBeInt()
		->and($faqItem->question)->toBe('Что такое рабочие листы?')
		->and($faqItem->answer)->toBe('Рабочий лист — материал в PDF для печати.')
		->and($faqItem->is_active)->toBeTrue();

	expect(FaqItem::query()->count())->toBe(1);
});

test('action обновляет существующую запись чаво из dto', function (): void {
	$action = app(SaveFaqItemAction::class);
	$faqItem = FaqItem::factory()->create([
		'question' => 'Старый вопрос',
		'answer' => 'Старый ответ',
		'is_active' => true,
	]);
	$data = FaqItemData::from([
		'question' => 'Новый вопрос',
		'answer' => 'Новый ответ',
		'isActive' => false,
	]);

	$updatedFaqItem = $action->handle($data, $faqItem);

	expect($updatedFaqItem->id)->toBe($faqItem->id)
		->and($updatedFaqItem->question)->toBe('Новый вопрос')
		->and($updatedFaqItem->answer)->toBe('Новый ответ')
		->and($updatedFaqItem->is_active)->toBeFalse();

	expect(FaqItem::query()->count())->toBe(1);
});

test('action отправляет запись чаво в конец новой группы при смене активности', function (): void {
	$action = app(SaveFaqItemAction::class);

	$activeFirst = FaqItem::factory()->create([
		'question' => 'Активный 1',
		'is_active' => true,
	]);
	$activeSecond = FaqItem::factory()->create([
		'question' => 'Активный 2',
		'is_active' => true,
	]);
	$archivedFirst = FaqItem::factory()->inactive()->create([
		'question' => 'Архивный 1',
	]);

	$data = FaqItemData::from([
		'question' => $activeSecond->question,
		'answer' => $activeSecond->answer,
		'isActive' => false,
	]);

	$updatedFaqItem = $action->handle($data, $activeSecond);

	$activePositions = FaqItem::query()
		->active()
		->ordered()
		->pluck('position', 'question')
		->all();
	$archivedPositions = FaqItem::query()
		->active(false)
		->ordered()
		->pluck('position', 'question')
		->all();

	expect($updatedFaqItem->is_active)->toBeFalse()
		->and($activePositions)->toBe([
			$activeFirst->question => 0,
		])
		->and($archivedPositions)->toBe([
			$archivedFirst->question => 0,
			$activeSecond->question => 1,
		]);
});
