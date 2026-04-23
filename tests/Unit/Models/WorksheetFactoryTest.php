<?php

use App\Models\Worksheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('фабрика рабочего листа создает запись с валидными связями', function (): void {
	$worksheet = Worksheet::factory()->create();

	expect($worksheet->id)->toBeInt()
		->and($worksheet->topic_id)->toBeInt()
		->and($worksheet->quarter_id)->toBeInt()
		->and($worksheet->title)->toBeString()->not->toBe('')
		->and($worksheet->topic)->not->toBeNull()
		->and($worksheet->quarter)->not->toBeNull();
});

test('фабрика рабочего листа принимает переопределение атрибутов', function (): void {
	$worksheet = Worksheet::factory()->create([
		'title' => 'Кастомный лист',
		'seo_title' => 'Кастомный SEO title',
	]);

	expect($worksheet->title)->toBe('Кастомный лист')
		->and($worksheet->seo_title)->toBe('Кастомный SEO title');
});
