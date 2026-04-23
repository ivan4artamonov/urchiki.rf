<?php

use App\Models\Subject;
use App\Models\Topic;
use App\Models\Grade;
use App\Models\Quarter;
use App\Models\Worksheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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

test('массовое заполнение поддерживает seo-поля и article у предмета', function (): void {
	$subject = Subject::create([
		'name' => 'Физика',
		'seo_title' => 'Физика - теория и задачи',
		'seo_description' => 'Описание страницы предмета физика',
		'seo_keywords' => 'физика, задачи, формулы',
		'article' => 'Большой текст статьи о физике',
	]);

	expect($subject->seo_title)->toBe('Физика - теория и задачи')
		->and($subject->seo_description)->toBe('Описание страницы предмета физика')
		->and($subject->seo_keywords)->toBe('физика, задачи, формулы')
		->and($subject->article)->toBe('Большой текст статьи о физике');
});

test('иконка в коллекции icon заменяется при повторной загрузке из-за singleFile', function (): void {
	Storage::fake('public');

	$subject = Subject::create(['name' => 'Биология']);

	$subject
		->addMedia(UploadedFile::fake()->image('icon-one.png'))
		->toMediaCollection('icon');

	$subject
		->addMedia(UploadedFile::fake()->image('icon-two.png'))
		->toMediaCollection('icon');

	$subject->refresh();

	expect($subject->media()->where('collection_name', 'icon')->count())->toBe(1)
		->and($subject->icon())->toBeInstanceOf(Media::class)
		->and($subject->icon()?->file_name)->toBe('icon-two.png');
});

test('icon_url возвращает null когда иконка не загружена', function (): void {
	$subject = Subject::create(['name' => 'География']);

	expect($subject->icon_url)->toBeNull();
});

test('icon_url возвращает ссылку после загрузки иконки', function (): void {
	Storage::fake('public');

	$subject = Subject::create(['name' => 'Химия']);

	$subject
		->addMedia(UploadedFile::fake()->image('chemistry-icon.png'))
		->toMediaCollection('icon');

	$subject->refresh();

	expect($subject->icon_url)->not->toBeNull()
		->and($subject->icon_url)->toContain('/storage/');
});

test('коллекция icon отклоняет файлы с недопустимым mime-типом', function (): void {
	Storage::fake('public');

	$subject = Subject::create(['name' => 'Информатика']);

	$this->expectException(FileUnacceptableForCollection::class);

	$subject
		->addMedia(UploadedFile::fake()->create('icon.txt', 10, 'text/plain'))
		->toMediaCollection('icon');
});

test('связь worksheets возвращает рабочие листы предмета через темы', function (): void {
	$subject = Subject::create(['name' => 'Алгебра']);
	$otherSubject = Subject::create(['name' => 'География']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Уравнения']);
	$otherTopic = Topic::create(['subject_id' => $otherSubject->id, 'name' => 'Рельеф']);
	$grade = Grade::create(['number' => 8]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 1]);

	Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Лист по уравнениям',
	]);
	Worksheet::create([
		'topic_id' => $otherTopic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Лист по рельефу',
	]);

	$worksheetTitles = $subject->worksheets()->pluck('title')->all();

	expect($worksheetTitles)->toBe(['Лист по уравнениям']);
});
