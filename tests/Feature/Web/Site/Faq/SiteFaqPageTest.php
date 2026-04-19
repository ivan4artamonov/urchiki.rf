<?php

use App\Livewire\Site\Faq;
use App\Models\FaqItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('гость видит страницу вопросов и ответов', function (): void {
    $response = $this->get(route('site.faq'));

    $response->assertOk();
    $response->assertSeeLivewire(Faq::class);
    $response->assertSee('Вопросы и ответы', false);
    $response->assertSee('Всё, что нужно знать о сервисе Урчики', false);
});

test('на странице выводятся только активные вопросы', function (): void {
    FaqItem::factory()->create([
        'question' => 'Вопрос для сайта',
        'answer' => 'Ответ для сайта.',
        'is_active' => true,
    ]);
    FaqItem::factory()->inactive()->create([
        'question' => 'Скрытый вопрос',
        'answer' => 'Скрытый ответ.',
    ]);

    $this->get(route('site.faq'))
        ->assertOk()
        ->assertSee('Вопрос для сайта', false)
        ->assertSee('Ответ для сайта.', false)
        ->assertDontSee('Скрытый вопрос', false);
});
