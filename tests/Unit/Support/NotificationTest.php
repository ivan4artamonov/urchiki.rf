<?php

use App\Enums\NotificationType;
use App\Support\Notification;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
	$this->startSession();
});

test('read возвращает null если в сессии нет уведомления', function (): void {
	expect(Notification::read())->toBeNull()
		->and(Notification::has())->toBeFalse()
		->and(Notification::type())->toBeNull()
		->and(Notification::message())->toBeNull();
});

test('read возвращает null если payload не массив', function (): void {
	session([Notification::FLASH_KEY => 'not-array']);

	expect(Notification::read())->toBeNull();
});

test('read возвращает null если message пустой или не строка', function (): void {
	session([Notification::FLASH_KEY => ['type' => 'success', 'message' => '']]);
	expect(Notification::read())->toBeNull();

	session([Notification::FLASH_KEY => ['type' => 'success', 'message' => '   ']]);
	expect(Notification::read())->toBeNull();

	session([Notification::FLASH_KEY => ['type' => 'success', 'message' => 123]]);
	expect(Notification::read())->toBeNull();
});

test('read подставляет Info при неизвестном типе', function (): void {
	session([Notification::FLASH_KEY => [
		'type' => 'unknown-type',
		'message' => 'Текст',
	]]);

	$data = Notification::read();

	expect($data['type'])->toBe(NotificationType::Info)
		->and($data['message'])->toBe('Текст');
});

test('success записывает flash и read возвращает Success', function (): void {
	Notification::success('Сохранено');

	expect(Notification::read())->toMatchArray([
		'type' => NotificationType::Success,
		'message' => 'Сохранено',
	])
		->and(Notification::has())->toBeTrue()
		->and(Notification::hasType(NotificationType::Success))->toBeTrue()
		->and(Notification::hasType(NotificationType::Error))->toBeFalse()
		->and(Notification::type())->toBe(NotificationType::Success)
		->and(Notification::message())->toBe('Сохранено');
});

test('error warning и info записывают соответствующий тип', function (): void {
	Notification::error('Ошибка');
	expect(Notification::read()['type'])->toBe(NotificationType::Error)
		->and(Notification::message())->toBe('Ошибка');

	Notification::warning('Внимание');
	expect(Notification::read()['type'])->toBe(NotificationType::Warning);

	Notification::info('Справка');
	expect(Notification::read()['type'])->toBe(NotificationType::Info);
});

test('flash принимает произвольный тип', function (): void {
	Notification::flash(NotificationType::Warning, 'Проверка');

	expect(Notification::read())->toMatchArray([
		'type' => NotificationType::Warning,
		'message' => 'Проверка',
	]);
});
