<?php

use App\Enums\SiteSocialLoginProvider;

test('значения enum совпадают с именами драйверов Socialite', function (): void {
    expect(SiteSocialLoginProvider::Vkontakte->value)->toBe('vkontakte')
        ->and(SiteSocialLoginProvider::Yandex->value)->toBe('yandex')
        ->and(SiteSocialLoginProvider::Mailru->value)->toBe('mailru');
});

test('driverName для каждого кейса совпадает с value', function (): void {
    foreach (SiteSocialLoginProvider::cases() as $provider) {
        expect($provider->driverName())->toBe($provider->value);
    }
});

test('tryFrom возвращает кейс для каждого известного значения', function (): void {
    expect(SiteSocialLoginProvider::tryFrom('vkontakte'))->toBe(SiteSocialLoginProvider::Vkontakte)
        ->and(SiteSocialLoginProvider::tryFrom('yandex'))->toBe(SiteSocialLoginProvider::Yandex)
        ->and(SiteSocialLoginProvider::tryFrom('mailru'))->toBe(SiteSocialLoginProvider::Mailru);
});

test('tryFrom возвращает null для неизвестного значения', function (): void {
    expect(SiteSocialLoginProvider::tryFrom('github'))->toBeNull()
        ->and(SiteSocialLoginProvider::tryFrom(''))->toBeNull();
});

test('from выбрасывает при невалидном значении', function (): void {
    SiteSocialLoginProvider::from('invalid');
})->throws(ValueError::class);

test('cases содержит ровно три элемента', function (): void {
    expect(SiteSocialLoginProvider::cases())->toHaveCount(3);
});
