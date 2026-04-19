<?php

use App\Data\SiteProfileData;
use Tests\TestCase;

uses(TestCase::class);

test('toModelAttributes превращает пустое имя в null', function (): void {
    $data = SiteProfileData::from([
        'name' => '',
        'email' => 'user@example.com',
    ]);

    expect($data->toModelAttributes())->toBe([
        'name' => null,
        'email' => 'user@example.com',
    ]);
});
