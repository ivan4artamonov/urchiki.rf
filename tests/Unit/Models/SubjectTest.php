<?php

use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('при сохранении без slug подставляется slug из названия', function (): void {
	$subject = Subject::create(['name' => 'Математика']);

	expect($subject->slug)->toBe('matematika')
		->and($subject->name)->toBe('Математика');
});

test('пустая строка slug перед сохранением заменяется на slug из названия', function (): void {
	$subject = Subject::create(['name' => 'Окружающий мир', 'slug' => '']);

	expect($subject->slug)->toBe('okruzaiushhii-mir');
});

test('если slug задан явно, он не перезаписывается', function (): void {
	$subject = Subject::create(['name' => 'Русский язык', 'slug' => 'russkij-yazyk']);

	expect($subject->slug)->toBe('russkij-yazyk')
		->and($subject->name)->toBe('Русский язык');
});

test('ключ маршрутизации — slug', function (): void {
	$subject = new Subject;

	expect($subject->getRouteKeyName())->toBe('slug');
});

test('позиционирование позволяет менять порядок предметов в списке', function (): void {
	$a = Subject::create(['name' => 'Предмет A']);
	$b = Subject::create(['name' => 'Предмет B']);
	$c = Subject::create(['name' => 'Предмет C']);

	$c->move(0);

	$orderedNames = Subject::query()
		->ordered()
		->pluck('name')
		->all();

	expect($orderedNames)->toBe([
		$c->name,
		$a->name,
		$b->name,
	]);
});

test('при создании без position позиция назначается автоматически', function (): void {
	$first = Subject::create(['name' => 'Первый']);
	$second = Subject::create(['name' => 'Второй']);

	expect($first->position)->toBeInt()
		->and($second->position)->toBeInt()
		->and($first->position)->toBeLessThan($second->position);
});

test('связь topics возвращает темы предмета в порядке позиции', function (): void {
	$math = Subject::create(['name' => 'Математика']);
	$russian = Subject::create(['name' => 'Русский язык']);

	Topic::create(['subject_id' => $math->id, 'name' => 'Алгебра', 'position' => 2]);
	Topic::create(['subject_id' => $math->id, 'name' => 'Геометрия', 'position' => 1]);
	Topic::create(['subject_id' => $russian->id, 'name' => 'Синтаксис', 'position' => 1]);

	$topicNames = $math->topics()
		->ordered()
		->pluck('name')
		->all();

	expect($topicNames)->toBe([
		'Геометрия',
		'Алгебра',
	]);
});
