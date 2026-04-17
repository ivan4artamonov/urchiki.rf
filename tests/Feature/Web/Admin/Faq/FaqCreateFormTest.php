<?php

use App\Livewire\Admin\Faq\Create as FaqCreate;
use App\Models\FaqItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ может создать запись чаво через форму в админке', function (): void {
	$admin = User::factory()->admin()->create();

	Livewire::actingAs($admin)
		->test(FaqCreate::class)
		->set('form.question', 'Что такое рабочие листы?')
		->set('form.answer', 'Рабочий лист — готовый материал в PDF для урока.')
		->set('form.isActive', true)
		->call('createFaqItem')
		->assertHasNoErrors()
		->assertRedirect(route('admin.faq.index'));

	$this->assertDatabaseHas('faq_items', [
		'question' => 'Что такое рабочие листы?',
		'answer' => 'Рабочий лист — готовый материал в PDF для урока.',
		'is_active' => true,
	]);
});

test('форма создания записи чаво показывает ошибки валидации обязательных полей', function (): void {
	$admin = User::factory()->admin()->create();

	Livewire::actingAs($admin)
		->test(FaqCreate::class)
		->set('form.question', '')
		->set('form.answer', '')
		->call('createFaqItem')
		->assertHasErrors([
			'form.question' => 'required',
			'form.answer' => 'required',
		]);

	expect(FaqItem::query()->count())->toBe(0);
});
