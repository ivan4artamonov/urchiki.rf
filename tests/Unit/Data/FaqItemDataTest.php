<?php

use App\Data\FaqItemData;
use Tests\TestCase;

uses(TestCase::class);

test('dto вопроса-ответа создается из массива с ожидаемыми типами', function (): void {
	$data = FaqItemData::from([
		'question' => 'Что такое рабочие листы?',
		'answer' => 'Рабочий лист — готовый материал для урока.',
		'isActive' => 1,
		'position' => '3',
	]);

	expect($data)->toBeInstanceOf(FaqItemData::class)
		->and($data->question)->toBe('Что такое рабочие листы?')
		->and($data->answer)->toBe('Рабочий лист — готовый материал для урока.')
		->and($data->isActive)->toBeBool()->toBeTrue()
		->and($data->position)->toBeInt()->toBe(3);
});

test('dto вопроса-ответа маппит поля в атрибуты модели', function (): void {
	$data = FaqItemData::from([
		'question' => 'Можно ли скачать лист повторно?',
		'answer' => 'Да, повторное скачивание доступно из профиля.',
		'isActive' => false,
		'position' => 7,
	]);

	expect($data->toModelAttributes())->toBe([
		'question' => 'Можно ли скачать лист повторно?',
		'answer' => 'Да, повторное скачивание доступно из профиля.',
		'is_active' => false,
		'position' => 7,
	]);
});

test('dto вопроса-ответа не добавляет position в атрибуты когда он не задан', function (): void {
	$data = FaqItemData::from([
		'question' => 'Есть ли бесплатные материалы?',
		'answer' => 'Да, часть материалов доступна бесплатно.',
		'isActive' => true,
		'position' => null,
	]);

	expect($data->toModelAttributes())->toBe([
		'question' => 'Есть ли бесплатные материалы?',
		'answer' => 'Да, часть материалов доступна бесплатно.',
		'is_active' => true,
	]);
});
