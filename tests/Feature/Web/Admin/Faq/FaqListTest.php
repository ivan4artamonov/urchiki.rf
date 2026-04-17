<?php

use App\Models\FaqItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('админ видит список активных и архивных вопросов на странице admin.faq.index', function (): void {
	$admin = User::factory()->admin()->create();

	FaqItem::factory()->create([
		'question' => 'Активный вопрос',
		'answer' => 'Активный ответ',
		'is_active' => true,
	]);
	FaqItem::factory()->inactive()->create([
		'question' => 'Архивный вопрос',
		'answer' => 'Архивный ответ',
	]);

	$response = $this->actingAs($admin)->get(route('admin.faq.index'));

	$response->assertOk()
		->assertSeeText('ЧаВо')
		->assertSeeText('Активные вопросы')
		->assertSeeText('Архивные вопросы')
		->assertSeeText('Создать вопрос')
		->assertSeeText('Активный вопрос')
		->assertSeeText('Архивный вопрос');
});
