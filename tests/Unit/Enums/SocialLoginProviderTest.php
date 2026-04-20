<?php

use App\Enums\SocialLoginProvider;

test('значения enum совпадают с именами драйверов Socialite', function (): void {
	expect(SocialLoginProvider::Vkontakte->value)->toBe('vkontakte')
		->and(SocialLoginProvider::Yandex->value)->toBe('yandex')
		->and(SocialLoginProvider::Mailru->value)->toBe('mailru');
});

test('driverName для каждого кейса совпадает с value', function (): void {
	foreach (SocialLoginProvider::cases() as $provider) {
		expect($provider->driverName())->toBe($provider->value);
	}
});

test('tryFrom возвращает кейс для каждого известного значения', function (): void {
	expect(SocialLoginProvider::tryFrom('vkontakte'))->toBe(SocialLoginProvider::Vkontakte)
		->and(SocialLoginProvider::tryFrom('yandex'))->toBe(SocialLoginProvider::Yandex)
		->and(SocialLoginProvider::tryFrom('mailru'))->toBe(SocialLoginProvider::Mailru);
});

test('tryFrom возвращает null для неизвестного значения', function (): void {
	expect(SocialLoginProvider::tryFrom('github'))->toBeNull()
		->and(SocialLoginProvider::tryFrom(''))->toBeNull();
});

test('from выбрасывает при невалидном значении', function (): void {
	SocialLoginProvider::from('invalid');
})->throws(ValueError::class);

test('cases содержит ровно три элемента', function (): void {
	expect(SocialLoginProvider::cases())->toHaveCount(3);
});
