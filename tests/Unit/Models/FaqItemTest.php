<?php

use App\Models\FaqItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('массовое заполнение работает для атрибутов вопроса-ответа', function (): void {
	$faqItem = FaqItem::create([
		'question' => 'Что такое рабочие листы?',
		'answer' => 'Это готовые материалы в PDF.',
		'is_active' => true,
	]);

	expect($faqItem->question)->toBe('Что такое рабочие листы?')
		->and($faqItem->answer)->toBe('Это готовые материалы в PDF.')
		->and($faqItem->is_active)->toBeTrue();
});

test('касты приводят поля вопроса-ответа к корректным типам', function (): void {
	$faqItem = FaqItem::query()->create([
		'question' => 'Какие предметы доступны?',
		'answer' => 'Все основные предметы по ФГОС.',
		'is_active' => 1,
		'position' => '4',
	]);

	$faqItem->refresh();

	expect($faqItem->is_active)->toBeBool()->toBeTrue()
		->and($faqItem->position)->toBeInt()->toBe(4);
});

test('флаг активности берёт значение по умолчанию', function (): void {
	$faqItem = FaqItem::query()->create([
		'question' => 'Можно ли вернуть деньги?',
		'answer' => 'Да, в течение 3 дней при соблюдении условий.',
	]);

	$faqItem->refresh();

	expect($faqItem->is_active)->toBeTrue();
});

test('позиционирование позволяет менять место вопроса в списке', function (): void {
	$first = FaqItem::factory()->create([
		'question' => 'Вопрос 1',
	]);
	$second = FaqItem::factory()->create([
		'question' => 'Вопрос 2',
	]);
	$third = FaqItem::factory()->create([
		'question' => 'Вопрос 3',
	]);

	$third->move(0);

	$orderedQuestions = FaqItem::query()
		->ordered()
		->pluck('question')
		->all();

	expect($orderedQuestions)->toBe([
		$third->question,
		$first->question,
		$second->question,
	]);
});

test('при создании без position позиция назначается автоматически', function (): void {
	$first = FaqItem::create([
		'question' => 'Вопрос A',
		'answer' => 'Ответ A',
		'is_active' => true,
	]);
	$second = FaqItem::create([
		'question' => 'Вопрос B',
		'answer' => 'Ответ B',
		'is_active' => true,
	]);

	expect($first->position)->toBeInt()
		->and($second->position)->toBeInt()
		->and($first->position)->toBeLessThan($second->position);
});
