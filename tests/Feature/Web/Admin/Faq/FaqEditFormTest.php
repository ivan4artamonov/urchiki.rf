<?php

use App\Livewire\Admin\Faq\Edit as FaqEdit;
use App\Models\FaqItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('админ может обновить запись чаво через форму редактирования', function (): void {
	$admin = User::factory()->admin()->create();
	$faqItem = FaqItem::factory()->create([
		'question' => 'Старый вопрос',
		'answer' => 'Старый ответ',
		'is_active' => true,
	]);

	Livewire::actingAs($admin)
		->test(FaqEdit::class, ['faqItem' => $faqItem])
		->set('form.question', 'Обновленный вопрос')
		->set('form.answer', 'Обновленный ответ')
		->set('form.isActive', false)
		->call('updateFaqItem')
		->assertHasNoErrors()
		->assertRedirect(route('admin.faq.index'));

	$this->assertDatabaseHas('faq_items', [
		'id' => $faqItem->id,
		'question' => 'Обновленный вопрос',
		'answer' => 'Обновленный ответ',
		'is_active' => false,
	]);
});
