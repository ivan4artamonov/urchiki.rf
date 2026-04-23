<?php

namespace App\Livewire\Admin\Forms;

use App\Actions\Worksheet\SaveWorksheetAction;
use App\Data\WorksheetData;
use App\Models\Quarter;
use App\Models\Topic;
use App\Models\Worksheet;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;
use Illuminate\Validation\ValidationException;

/**
 * Форма для создания и редактирования рабочего листа в админке.
 */
class WorksheetForm extends Form
{
	public ?Worksheet $worksheet = null;

	#[Validate('required|integer|exists:subjects,id')]
	public int|string|null $subjectId = null;

	#[Validate('required|integer|exists:topics,id')]
	public int|string|null $topicId = null;

	#[Validate('required|integer|exists:grades,id')]
	public int|string|null $gradeId = null;

	#[Validate('required|integer|exists:quarters,id')]
	public int|string|null $quarterId = null;

	#[Validate('required|string|min:2|max:255')]
	public string $title = '';

	public bool $isActive = true;

	#[Validate('nullable|string|max:128')]
	public ?string $slug = null;

	#[Validate('nullable|string|max:255')]
	public ?string $seoTitle = null;

	#[Validate('nullable|string|max:2000')]
	public ?string $seoDescription = null;

	#[Validate('nullable|string|max:2000')]
	public ?string $seoKeywords = null;

	#[Validate('nullable|string|max:20000')]
	public ?string $article = null;

	#[Validate('nullable|file|mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/octet-stream,application/zip|max:51200')]
	public ?TemporaryUploadedFile $file = null;

	#[Validate('nullable|file|mimetypes:application/pdf|max:20480')]
	public ?TemporaryUploadedFile $previewFile = null;

	#[Validate('nullable|file|mimetypes:image/jpeg,image/png,image/webp|max:8192')]
	public ?TemporaryUploadedFile $previewImage = null;

	public ?string $currentFileName = null;
	public ?string $currentPreviewFileName = null;
	public ?string $currentPreviewImageName = null;
	public ?string $currentFileUrl = null;
	public ?string $currentPreviewFileUrl = null;
	public ?string $currentPreviewImageUrl = null;

	/**
	 * Пользовательские сообщения валидации полей формы.
	 *
	 * @return array<string, string>
	 */
	public function messages(): array
	{
		return [
			'subjectId.required' => 'Поле "Предмет" обязательно для заполнения.',
			'subjectId.integer' => 'Поле "Предмет" заполнено некорректно.',
			'subjectId.exists' => 'Выбранный предмет не найден.',
			'topicId.required' => 'Поле "Тема" обязательно для заполнения.',
			'topicId.integer' => 'Поле "Тема" заполнено некорректно.',
			'topicId.exists' => 'Выбранная тема не найдена.',
			'gradeId.required' => 'Поле "Класс" обязательно для заполнения.',
			'gradeId.integer' => 'Поле "Класс" заполнено некорректно.',
			'gradeId.exists' => 'Выбранный класс не найден.',
			'quarterId.required' => 'Поле "Четверть" обязательно для заполнения.',
			'quarterId.integer' => 'Поле "Четверть" заполнено некорректно.',
			'quarterId.exists' => 'Выбранная четверть не найдена.',
			'title.required' => 'Поле "Название" обязательно для заполнения.',
			'title.string' => 'Поле "Название" должно быть строкой.',
			'title.min' => 'Поле "Название" должно содержать не менее 2 символов.',
			'title.max' => 'Поле "Название" не должно превышать 255 символов.',
			'slug.string' => 'Поле "Slug" должно быть строкой.',
			'slug.max' => 'Поле "Slug" не должно превышать 128 символов.',
			'seoTitle.string' => 'Поле "SEO-заголовок" должно быть строкой.',
			'seoTitle.max' => 'Поле "SEO-заголовок" не должно превышать 255 символов.',
			'seoDescription.string' => 'Поле "SEO-описание" должно быть строкой.',
			'seoDescription.max' => 'Поле "SEO-описание" не должно превышать 2000 символов.',
			'seoKeywords.string' => 'Поле "SEO-ключевые слова" должно быть строкой.',
			'seoKeywords.max' => 'Поле "SEO-ключевые слова" не должно превышать 2000 символов.',
			'article.string' => 'Поле "Статья" должно быть строкой.',
			'article.max' => 'Поле "Статья" не должно превышать 20000 символов.',
			'file.file' => 'Поле "Файл рабочего листа" должно содержать файл.',
			'file.mimetypes' => 'Файл рабочего листа должен быть DOC или DOCX.',
			'file.max' => 'Размер файла рабочего листа не должен превышать 50 МБ.',
			'previewFile.file' => 'Поле "Файл предпросмотра" должно содержать файл.',
			'previewFile.mimetypes' => 'Файл предпросмотра должен быть PDF.',
			'previewFile.max' => 'Размер файла предпросмотра не должен превышать 20 МБ.',
			'previewImage.file' => 'Поле "Изображение предпросмотра" должно содержать файл.',
			'previewImage.mimetypes' => 'Изображение предпросмотра должно быть JPG, PNG или WEBP.',
			'previewImage.max' => 'Размер изображения предпросмотра не должен превышать 8 МБ.',
		];
	}

	/**
	 * Сохраняет рабочий лист: создает новый или обновляет существующий.
	 */
	public function save(): void
	{
		$validated = $this->validate();
		$this->ensureDependentSelections(
			(int) $validated['subjectId'],
			(int) $validated['topicId'],
			(int) $validated['gradeId'],
			(int) $validated['quarterId'],
		);

		$data = WorksheetData::from([
			'topic_id' => (int) $validated['topicId'],
			'quarter_id' => (int) $validated['quarterId'],
			'title' => (string) $validated['title'],
			'is_active' => $this->isActive,
			'slug' => $validated['slug'] !== null && $validated['slug'] !== '' ? (string) $validated['slug'] : null,
			'seo_title' => $validated['seoTitle'] !== null ? (string) $validated['seoTitle'] : null,
			'seo_description' => $validated['seoDescription'] !== null ? (string) $validated['seoDescription'] : null,
			'seo_keywords' => $validated['seoKeywords'] !== null ? (string) $validated['seoKeywords'] : null,
			'article' => $validated['article'] !== null ? (string) $validated['article'] : null,
		]);

		$this->worksheet = app(SaveWorksheetAction::class)->handle($data, $this->worksheet);

		if ($this->file instanceof TemporaryUploadedFile) {
			$this->worksheet
				->addMedia($this->file->getRealPath())
				->usingFileName($this->file->getClientOriginalName())
				->toMediaCollection('file');

			$this->file = null;
		}

		if ($this->previewFile instanceof TemporaryUploadedFile) {
			$this->worksheet
				->addMedia($this->previewFile->getRealPath())
				->usingFileName($this->previewFile->getClientOriginalName())
				->toMediaCollection('preview_file');

			$this->previewFile = null;
		}

		if ($this->previewImage instanceof TemporaryUploadedFile) {
			$this->worksheet
				->addMedia($this->previewImage->getRealPath())
				->usingFileName($this->previewImage->getClientOriginalName())
				->toMediaCollection('preview_image');

			$this->previewImage = null;
		}

		$this->syncCurrentFiles();
	}

	/**
	 * Заполняет форму данными существующего рабочего листа для редактирования.
	 */
	public function fillFromWorksheet(Worksheet $worksheet): void
	{
		$this->worksheet = $worksheet;
		$this->subjectId = $worksheet->topic?->subject_id ?? (string) Topic::query()->whereKey($worksheet->topic_id)->value('subject_id');
		$this->topicId = $worksheet->topic_id;
		$this->gradeId = $worksheet->quarter?->grade_id ?? (string) Quarter::query()->whereKey($worksheet->quarter_id)->value('grade_id');
		$this->quarterId = $worksheet->quarter_id;
		$this->title = $worksheet->title;
		$this->isActive = $worksheet->is_active;
		$this->slug = $worksheet->slug;
		$this->seoTitle = $worksheet->seo_title;
		$this->seoDescription = $worksheet->seo_description;
		$this->seoKeywords = $worksheet->seo_keywords;
		$this->article = $worksheet->article;

		$this->syncCurrentFiles();
	}

	/**
	 * Обновляет имена текущих загруженных файлов рабочего листа.
	 */
	private function syncCurrentFiles(): void
	{
		if (! ($this->worksheet instanceof Worksheet)) {
			$this->currentFileName = null;
			$this->currentPreviewFileName = null;
			$this->currentPreviewImageName = null;
			$this->currentFileUrl = null;
			$this->currentPreviewFileUrl = null;
			$this->currentPreviewImageUrl = null;

			return;
		}

		$this->worksheet = $this->worksheet->fresh();
		$file = $this->worksheet->file();
		$previewFile = $this->worksheet->previewFile();
		$previewImage = $this->worksheet->previewImage();
		$this->currentFileName = $file?->file_name;
		$this->currentPreviewFileName = $previewFile?->file_name;
		$this->currentPreviewImageName = $previewImage?->file_name;
		$this->currentFileUrl = $file?->getUrl();
		$this->currentPreviewFileUrl = $previewFile?->getUrl();
		$this->currentPreviewImageUrl = $previewImage?->getUrl();
	}

	/**
	 * Проверяет, что тема принадлежит предмету, а четверть - классу.
	 */
	private function ensureDependentSelections(int $subjectId, int $topicId, int $gradeId, int $quarterId): void
	{
		$topicExistsInSubject = Topic::query()
			->whereKey($topicId)
			->where('subject_id', $subjectId)
			->exists();

		if (! $topicExistsInSubject) {
			throw ValidationException::withMessages([
				'topicId' => 'Выбранная тема не относится к выбранному предмету.',
			]);
		}

		$quarterExistsInGrade = Quarter::query()
			->whereKey($quarterId)
			->where('grade_id', $gradeId)
			->exists();

		if (! $quarterExistsInGrade) {
			throw ValidationException::withMessages([
				'quarterId' => 'Выбранная четверть не относится к выбранному классу.',
			]);
		}
	}
}
