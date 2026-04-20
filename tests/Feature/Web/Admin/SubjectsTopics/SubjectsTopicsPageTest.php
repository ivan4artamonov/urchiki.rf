<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('админ видит заглушку раздела «Предметы и темы»', function (): void {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.subjects-topics'));

    $response->assertOk()
        ->assertSeeText('Предметы и темы')
        ->assertSeeText('Раздел в разработке.');
});
