<?php

use App\Livewire\Site\Hub;
use App\Models\Grade;
use App\Models\Subject;
use App\Support\SiteHubUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('хаб по предмету открывается по первому сегменту-слагу предмета', function (): void {
	$subject = Subject::factory()->create([
		'name' => 'Тестовый предмет Хаб',
		'slug' => 'testovyj-predmet-khab',
	]);

	$this->get(SiteHubUrl::make($subject))
		->assertOk()
		->assertSeeLivewire(Hub::class)
		->assertSee('Тестовый предмет Хаб', false);
});

test('хаб по классу в первом сегменте показывает предметы со ссылками предмет/класс', function (): void {
	$grade = Grade::query()->create([
		'number' => 7,
		'slug' => '7-klass-seo-test',
	]);
	$subject = Subject::factory()->create([
		'name' => 'Предмет для СЕО-хаба',
		'slug' => 'predmet-dlya-seo-khaba',
	]);

	$response = $this->get(SiteHubUrl::make(null, $grade));

	$response->assertOk();
	$response->assertSeeLivewire(Hub::class);
	$response->assertSee('Предмет для СЕО-хаба', false);
	$response->assertSee(SiteHubUrl::make($subject, $grade), false);
});

test('при первом сегменте-классе лишние сегменты дают 404', function (): void {
	$grade = Grade::query()->create([
		'number' => 8,
		'slug' => '8-klass-lishnij-segment',
	]);

	$this->get(route('site.hub', [
		'slug1' => $grade->slug,
		'slug2' => 'luboj-slug',
	]))->assertNotFound();
});
