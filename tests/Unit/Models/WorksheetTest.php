<?php

use App\Models\Grade;
use App\Models\Quarter;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Worksheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

/**
 * Создаёт тестовый DOCX-файл для загрузки в Media Library.
 *
 * @param string $fileName Имя файла, которое будет передано в UploadedFile.
 * @return UploadedFile Тестовый UploadedFile с содержимым DOCX.
 */
function fakeWordFile(string $fileName): UploadedFile
{
	$tempPath = tempnam(sys_get_temp_dir(), 'ws_docx_');

	if ($tempPath === false) {
		throw new \RuntimeException('Не удалось создать временный файл для DOCX.');
	}

	$zip = new \ZipArchive;
	$zipResult = $zip->open($tempPath, \ZipArchive::OVERWRITE);

	if ($zipResult !== true) {
		throw new \RuntimeException('Не удалось открыть временный архив DOCX.');
	}

	$zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"></Types>');
	$zip->addFromString('word/document.xml', '<?xml version="1.0" encoding="UTF-8"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:p><w:r><w:t>Test</w:t></w:r></w:p></w:body></w:document>');
	$zip->close();

	return new UploadedFile(
		$tempPath,
		$fileName,
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		null,
		true
	);
}

/**
 * Создаёт тестовый PDF-файл для ознакомительной копии.
 *
 * @param string $fileName Имя файла, которое будет передано в UploadedFile.
 * @return UploadedFile Тестовый UploadedFile с MIME-типом application/pdf.
 */
function fakePdfFile(string $fileName): UploadedFile
{
	$tempPath = tempnam(sys_get_temp_dir(), 'ws_pdf_');

	if ($tempPath === false) {
		throw new \RuntimeException('Не удалось создать временный файл для PDF.');
	}

	file_put_contents($tempPath, '%PDF-1.4 worksheet preview');

	return new UploadedFile($tempPath, $fileName, 'application/pdf', null, true);
}

test('массовое заполнение поддерживает базовые поля и seo-поля рабочего листа', function (): void {
	$subject = Subject::create(['name' => 'Математика']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Дроби']);
	$grade = Grade::create(['number' => 4]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 2]);

	$worksheet = Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Рабочий лист по дробям',
		'slug' => 'rabochii-list-po-drobiam',
		'seo_title' => 'Рабочий лист: дроби, 4 класс',
		'seo_description' => 'Описание листа по дробям для 4 класса',
		'seo_keywords' => 'дроби, рабочий лист, 4 класс',
		'article' => 'Текст статьи для страницы листа',
	]);

	expect($worksheet->title)->toBe('Рабочий лист по дробям')
		->and($worksheet->slug)->toBe('rabochii-list-po-drobiam')
		->and($worksheet->seo_title)->toBe('Рабочий лист: дроби, 4 класс')
		->and($worksheet->seo_description)->toBe('Описание листа по дробям для 4 класса')
		->and($worksheet->seo_keywords)->toBe('дроби, рабочий лист, 4 класс')
		->and($worksheet->article)->toBe('Текст статьи для страницы листа');
});

test('при сохранении без slug подставляется slug из названия', function (): void {
	$subject = Subject::create(['name' => 'Математика']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Дроби']);
	$grade = Grade::create(['number' => 4]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 2]);

	$worksheet = Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Рабочий лист по дробям',
	]);

	expect($worksheet->slug)->toBe('rabocii-list-po-drobiam');
});

test('связи topic и quarter возвращают связанные модели', function (): void {
	$subject = Subject::create(['name' => 'Русский язык']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Орфография']);
	$grade = Grade::create(['number' => 5]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 1]);

	$worksheet = Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Правописание безударных гласных',
	]);

	expect($worksheet->topic->is($topic))->toBeTrue()
		->and($worksheet->quarter->is($quarter))->toBeTrue();
});

test('методы subject и grade возвращают связанные предмет и класс через промежуточные модели', function (): void {
	$subject = Subject::create(['name' => 'Окружающий мир']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Природные зоны']);
	$grade = Grade::create(['number' => 3]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 3]);

	$worksheet = Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Природные зоны России',
	]);

	expect($worksheet->subject()?->is($subject))->toBeTrue()
		->and($worksheet->grade()?->is($grade))->toBeTrue();
});

test('основной файл в коллекции file заменяется при повторной загрузке', function (): void {
	Storage::fake('public');

	$subject = Subject::create(['name' => 'Литература']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Сказки']);
	$grade = Grade::create(['number' => 2]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 4]);

	$worksheet = Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Лист по русским сказкам',
	]);

	$worksheet
		->addMedia(fakeWordFile('worksheet-one.docx'))
		->toMediaCollection('file');

	$worksheet
		->addMedia(fakeWordFile('worksheet-two.docx'))
		->toMediaCollection('file');

	$worksheet->refresh();

	expect($worksheet->media()->where('collection_name', 'file')->count())->toBe(1)
		->and($worksheet->file())->toBeInstanceOf(Media::class)
		->and($worksheet->file()?->file_name)->toBe('worksheet-two.docx');
});

test('ознакомительный файл в коллекции preview_file заменяется при повторной загрузке', function (): void {
	Storage::fake('public');

	$subject = Subject::create(['name' => 'История']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Древний мир']);
	$grade = Grade::create(['number' => 6]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 2]);

	$worksheet = Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Лист по Древнему миру',
	]);

	$worksheet
		->addMedia(fakePdfFile('preview-one.pdf'))
		->toMediaCollection('preview_file');

	$worksheet
		->addMedia(fakePdfFile('preview-two.pdf'))
		->toMediaCollection('preview_file');

	$worksheet->refresh();

	expect($worksheet->media()->where('collection_name', 'preview_file')->count())->toBe(1)
		->and($worksheet->previewFile())->toBeInstanceOf(Media::class)
		->and($worksheet->previewFile()?->file_name)->toBe('preview-two.pdf');
});

test('коллекция preview_image хранит только одно изображение из-за singleFile', function (): void {
	Storage::fake('public');

	$subject = Subject::create(['name' => 'Биология']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Растения']);
	$grade = Grade::create(['number' => 7]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 1]);

	$worksheet = Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Лист по растениям',
	]);

	$worksheet
		->addMedia(UploadedFile::fake()->image('page-1.png'))
		->toMediaCollection('preview_image');

	$worksheet
		->addMedia(UploadedFile::fake()->image('page-1-alt.jpg'))
		->toMediaCollection('preview_image');

	$worksheet->refresh();

	expect($worksheet->media()->where('collection_name', 'preview_image')->count())->toBe(1)
		->and($worksheet->previewImage())->toBeInstanceOf(Media::class)
		->and($worksheet->previewImage()?->file_name)->toBe('page-1-alt.jpg');
});

test('коллекция file отклоняет файлы с недопустимым mime-типом', function (): void {
	Storage::fake('public');

	$subject = Subject::create(['name' => 'География']);
	$topic = Topic::create(['subject_id' => $subject->id, 'name' => 'Материки']);
	$grade = Grade::create(['number' => 8]);
	$quarter = Quarter::create(['grade_id' => $grade->id, 'number' => 3]);

	$worksheet = Worksheet::create([
		'topic_id' => $topic->id,
		'quarter_id' => $quarter->id,
		'title' => 'Лист по материкам',
	]);

	$this->expectException(FileUnacceptableForCollection::class);

	$worksheet
		->addMedia(UploadedFile::fake()->create('worksheet.txt', 20, 'text/plain'))
		->toMediaCollection('file');
});

test('скоупы forTopic, forQuarter, forSubject и forGrade можно комбинировать в одном запросе', function (): void {
	$math = Subject::create(['name' => 'Математика']);
	$russian = Subject::create(['name' => 'Русский язык']);
	$mathTopic = Topic::create(['subject_id' => $math->id, 'name' => 'Дроби']);
	$russianTopic = Topic::create(['subject_id' => $russian->id, 'name' => 'Орфография']);

	$gradeThree = Grade::create(['number' => 3]);
	$gradeFour = Grade::create(['number' => 4]);
	$quarterThree = Quarter::create(['grade_id' => $gradeThree->id, 'number' => 1]);
	$quarterFour = Quarter::create(['grade_id' => $gradeFour->id, 'number' => 1]);

	$targetWorksheet = Worksheet::create([
		'topic_id' => $mathTopic->id,
		'quarter_id' => $quarterThree->id,
		'title' => 'Целевой лист',
	]);

	Worksheet::create([
		'topic_id' => $russianTopic->id,
		'quarter_id' => $quarterThree->id,
		'title' => 'Лишний лист по предмету',
	]);
	Worksheet::create([
		'topic_id' => $mathTopic->id,
		'quarter_id' => $quarterFour->id,
		'title' => 'Лишний лист по классу',
	]);

	$result = Worksheet::query()
		->forSubject($math)
		->forGrade($gradeThree)
		->forTopic($mathTopic->id)
		->forQuarter($quarterThree->id)
		->get();

	expect($result)->toHaveCount(1)
		->and($result->first()?->is($targetWorksheet))->toBeTrue();
});
