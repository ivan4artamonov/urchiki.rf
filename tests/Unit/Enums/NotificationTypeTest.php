<?php

use App\Enums\NotificationType;

test('все кейсы имеют ожидаемые строковые значения', function (): void {
    expect(NotificationType::Success->value)->toBe('success')
        ->and(NotificationType::Error->value)->toBe('error')
        ->and(NotificationType::Warning->value)->toBe('warning')
        ->and(NotificationType::Info->value)->toBe('info');
});

test('tryFrom возвращает кейс для каждого известного значения', function (): void {
    expect(NotificationType::tryFrom('success'))->toBe(NotificationType::Success)
        ->and(NotificationType::tryFrom('error'))->toBe(NotificationType::Error)
        ->and(NotificationType::tryFrom('warning'))->toBe(NotificationType::Warning)
        ->and(NotificationType::tryFrom('info'))->toBe(NotificationType::Info);
});

test('tryFrom возвращает null для неизвестного значения', function (): void {
    expect(NotificationType::tryFrom('unknown'))->toBeNull()
        ->and(NotificationType::tryFrom(''))->toBeNull();
});

test('from выбрасывает при невалидном значении', function (): void {
    NotificationType::from('not-a-type');
})->throws(ValueError::class);

test('cases содержит ровно четыре элемента', function (): void {
    expect(NotificationType::cases())->toHaveCount(4);
});
