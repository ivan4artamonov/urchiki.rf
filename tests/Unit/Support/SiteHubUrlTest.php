<?php

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Topic;
use App\Support\SiteHubUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('только предмет совпадает с route site.hub из одного сегмента', function (): void {
	$subject = Subject::factory()->create(['slug' => 'unit-hub-subject']);

	expect(SiteHubUrl::make($subject))->toBe(route('site.hub', ['slug1' => $subject->slug]));
});

test('предмет и класс совпадают с route из двух сегментов', function (): void {
	$subject = Subject::factory()->create(['slug' => 'unit-hub-subj2']);
	$grade = Grade::query()->create(['number' => 4, 'slug' => '4-klass-unit-hub']);

	expect(SiteHubUrl::make($subject, $grade))->toBe(route('site.hub', [
		'slug1' => $subject->slug,
		'slug2' => $grade->slug,
	]));
});

test('полная тройка совпадает с route из трёх сегментов', function (): void {
	$subject = Subject::factory()->create(['slug' => 'unit-hub-subj3']);
	$grade = Grade::query()->create(['number' => 5, 'slug' => '5-klass-unit-hub']);
	$topic = Topic::factory()->for($subject)->create(['slug' => 'unit-topic-slug']);

	expect(SiteHubUrl::make($subject, $grade, $topic))->toBe(route('site.hub', [
		'slug1' => $subject->slug,
		'slug2' => $grade->slug,
		'slug3' => $topic->slug,
	]));
});

test('только класс даёт СЕО-URL из одного сегмента', function (): void {
	$grade = Grade::query()->create(['number' => 6, 'slug' => '6-klass-seo-unit']);

	expect(SiteHubUrl::make(null, $grade))->toBe(route('site.hub', ['slug1' => $grade->slug]));
});

test('тема без класса бросает исключение', function (): void {
	$subject = Subject::factory()->create();
	$topic = Topic::factory()->for($subject)->create();

	expect(fn () => SiteHubUrl::make($subject, null, $topic))
		->toThrow(InvalidArgumentException::class, 'Для ссылки на тему в хабе нужен класс.');
});

test('тема чужого предмета бросает исключение', function (): void {
	$s1 = Subject::factory()->create();
	$s2 = Subject::factory()->create();
	$grade = Grade::query()->create(['number' => 9, 'slug' => '9-klass-wrong-subj']);
	$topic = Topic::factory()->for($s1)->create();

	expect(fn () => SiteHubUrl::make($s2, $grade, $topic))
		->toThrow(InvalidArgumentException::class, 'Тема не относится к переданному предмету.');
});
