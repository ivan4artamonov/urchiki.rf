<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Рабочий лист, привязанный к теме и четверти.
 *
 * @property int $topic_id Идентификатор темы, через которую лист связан с предметом.
 * @property int $quarter_id Идентификатор четверти, через которую лист связан с классом.
 * @property string $title Название рабочего листа.
 * @property string $slug Уникальный слаг URL рабочего листа.
 * @property bool $is_active Признак публикации рабочего листа.
 * @property null|string $seo_title SEO-заголовок страницы листа.
 * @property null|string $seo_description SEO-описание страницы листа.
 * @property null|string $seo_keywords SEO-ключевые слова страницы листа.
 * @property null|string $article Текст статьи для страницы листа.
 * @property-read Topic $topic Тема рабочего листа.
 * @property-read Quarter $quarter Четверть рабочего листа.
 */
class Worksheet extends Model implements HasMedia
{
	use HasFactory;
	use InteractsWithMedia;

	/**
	 * Атрибуты, доступные для массового присваивания.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'topic_id',
		'quarter_id',
		'title',
		'slug',
		'is_active',
		'seo_title',
		'seo_description',
		'seo_keywords',
		'article',
	];

	/**
	 * Правила приведения атрибутов к нужным типам.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'topic_id' => 'integer',
		'quarter_id' => 'integer',
		'is_active' => 'boolean',
	];

	/**
	 * Регистрирует обработчики событий модели рабочего листа.
	 *
	 * @return void
	 */
	protected static function booted(): void
	{
		static::saving(
			/**
			 * Заполняет слаг из заголовка, если он не задан перед сохранением.
			 *
			 * @param Worksheet $worksheet Сохраняемая модель рабочего листа.
			 * @return void
			 */
			function (Worksheet $worksheet): void {
				if ($worksheet->slug === null || $worksheet->slug === '') {
					$worksheet->slug = Str::slug($worksheet->title);
				}
			}
		);
	}

	/**
	 * Scope для фильтрации листов по теме.
	 *
	 * @param Builder<Worksheet> $query Базовый запрос к рабочим листам.
	 * @param Topic|int $topic Тема или её идентификатор.
	 * @return Builder<Worksheet> Запрос, ограниченный указанной темой.
	 */
	public function scopeForTopic(Builder $query, Topic|int $topic): Builder
	{
		$topicId = $topic instanceof Topic ? $topic->id : $topic;

		return $query->where('topic_id', $topicId);
	}

	/**
	 * Scope для фильтрации листов по четверти.
	 *
	 * @param Builder<Worksheet> $query Базовый запрос к рабочим листам.
	 * @param Quarter|int $quarter Четверть или её идентификатор.
	 * @return Builder<Worksheet> Запрос, ограниченный указанной четвертью.
	 */
	public function scopeForQuarter(Builder $query, Quarter|int $quarter): Builder
	{
		$quarterId = $quarter instanceof Quarter ? $quarter->id : $quarter;

		return $query->where('quarter_id', $quarterId);
	}

	/**
	 * Scope для фильтрации листов по предмету через связь с темой.
	 *
	 * @param Builder<Worksheet> $query Базовый запрос к рабочим листам.
	 * @param Subject|int $subject Предмет или его идентификатор.
	 * @return Builder<Worksheet> Запрос, ограниченный указанным предметом.
	 */
	public function scopeForSubject(Builder $query, Subject|int $subject): Builder
	{
		$subjectId = $subject instanceof Subject ? $subject->id : $subject;

		return $query->whereHas(
			'topic',
			/**
			 * Ограничивает темы по заданному предмету.
			 *
			 * @param Builder<Topic> $topicQuery Запрос к темам.
			 * @return void
			 */
			function (Builder $topicQuery) use ($subjectId): void {
				$topicQuery->where('subject_id', $subjectId);
			}
		);
	}

	/**
	 * Scope для фильтрации листов по классу через связь с четвертью.
	 *
	 * @param Builder<Worksheet> $query Базовый запрос к рабочим листам.
	 * @param Grade|int $grade Класс или его идентификатор.
	 * @return Builder<Worksheet> Запрос, ограниченный указанным классом.
	 */
	public function scopeForGrade(Builder $query, Grade|int $grade): Builder
	{
		$gradeId = $grade instanceof Grade ? $grade->id : $grade;

		return $query->whereHas(
			'quarter',
			/**
			 * Ограничивает четверти по заданному классу.
			 *
			 * @param Builder<Quarter> $quarterQuery Запрос к четвертям.
			 * @return void
			 */
			function (Builder $quarterQuery) use ($gradeId): void {
				$quarterQuery->where('grade_id', $gradeId);
			}
		);
	}

	/**
	 * Scope для сортировки листов по дате загрузки от новых к старым.
	 *
	 * @param Builder<Worksheet> $query Базовый запрос к рабочим листам.
	 * @return Builder<Worksheet> Запрос с сортировкой по времени создания.
	 */
	public function scopeLatestUploaded(Builder $query): Builder
	{
		return $query
			->orderByDesc('created_at')
			->orderByDesc('id');
	}

	/**
	 * Scope для выборки только опубликованных рабочих листов.
	 *
	 * @param Builder<Worksheet> $query Базовый запрос к рабочим листам.
	 * @return Builder<Worksheet> Запрос, ограниченный опубликованными листами.
	 */
	public function scopePublished(Builder $query): Builder
	{
		return $query->where('is_active', true);
	}

	/**
	 * Возвращает связь «лист принадлежит теме».
	 *
	 * @return BelongsTo<Topic, Worksheet> Запрос на выборку темы текущего листа.
	 */
	public function topic(): BelongsTo
	{
		return $this->belongsTo(Topic::class);
	}

	/**
	 * Возвращает связь «лист принадлежит четверти».
	 *
	 * @return BelongsTo<Quarter, Worksheet> Запрос на выборку четверти текущего листа.
	 */
	public function quarter(): BelongsTo
	{
		return $this->belongsTo(Quarter::class);
	}

	/**
	 * Возвращает предмет листа через связанную тему.
	 *
	 * @return Subject|null Предмет листа или null, если тема не загружена/отсутствует.
	 */
	public function subject(): ?Subject
	{
		return $this->topic?->subject;
	}

	/**
	 * Возвращает класс листа через связанную четверть.
	 *
	 * @return Grade|null Класс листа или null, если четверть не загружена/отсутствует.
	 */
	public function grade(): ?Grade
	{
		return $this->quarter?->grade;
	}

	/**
	 * Регистрирует медиаколлекции рабочего листа.
	 *
	 * @return void
	 */
	public function registerMediaCollections(): void
	{
		$this
			->addMediaCollection('file')
			->acceptsMimeTypes([
				'application/msword',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'application/octet-stream',
				'application/zip',
			])
			->singleFile();

		$this
			->addMediaCollection('preview_file')
			->acceptsMimeTypes([
				'application/pdf',
			])
			->singleFile();

		$this
			->addMediaCollection('preview_image')
			->acceptsMimeTypes([
				'image/jpeg',
				'image/png',
				'image/webp',
			])
			->singleFile();
	}

	/**
	 * Возвращает основной файл рабочего листа.
	 *
	 * @return Media|null Медиа-объект основного файла или null, если файл не загружен.
	 */
	public function file(): ?Media
	{
		return $this->getFirstMedia('file');
	}

	/**
	 * Возвращает ознакомительный файл рабочего листа.
	 *
	 * @return Media|null Медиа-объект бесплатной копии или null, если файл не загружен.
	 */
	public function previewFile(): ?Media
	{
		return $this->getFirstMedia('preview_file');
	}

	/**
	 * Возвращает превью-изображение рабочего листа.
	 *
	 * @return Media|null Медиа-объект превью-изображения или null, если файл не загружен.
	 */
	public function previewImage(): ?Media
	{
		return $this->getFirstMedia('preview_image');
	}
}
