<?php

use App\Actions\Site\UpdateSiteProfileAction;
use App\Data\SiteProfileData;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('при смене email сбрасывается подтверждение почты', function (): void {
    $user = User::factory()->create([
        'email' => 'a@example.com',
        'email_verified_at' => now(),
    ]);

    $updated = app(UpdateSiteProfileAction::class)->handle(
        $user,
        SiteProfileData::from([
            'name' => 'Тест',
            'email' => 'b@example.com',
        ])
    );

    expect($updated->email)->toBe('b@example.com')
        ->and($updated->email_verified_at)->toBeNull();
});

test('если email не менялся подтверждение сохраняется', function (): void {
    $verified = now()->startOfSecond();
    $user = User::factory()->create([
        'email' => 'same@example.com',
        'email_verified_at' => $verified,
    ]);

    $updated = app(UpdateSiteProfileAction::class)->handle(
        $user,
        SiteProfileData::from([
            'name' => 'Имя',
            'email' => 'same@example.com',
        ])
    );

    expect($updated->email_verified_at?->equalTo($verified))->toBeTrue();
});
