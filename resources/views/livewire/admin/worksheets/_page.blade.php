<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">{{ $title }}</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.worksheets.index') }}" variant="secondary">
			<x-admin.icon name="chevron-left" class="mr-2" />
			К списку рабочих листов
		</x-admin.button>
	</div>

	<x-admin.card size="full">
		<form wire:submit="{{ $submitAction }}">
			<div class="grid gap-4 md:grid-cols-2">
				<div class="md:col-span-2">
					<x-admin.input id="title" label="Название" wire:model="form.title" />
					<x-admin.validation-error field="form.title" />
				</div>

				<div>
					<x-admin.select
						id="subject_id"
						label="Предмет"
						wire:model.live="form.subjectId"
						placeholder="Выберите предмет"
						:options="$subjects->map(fn ($subject) => ['value' => $subject->id, 'label' => $subject->name])->all()"
					/>
					<x-admin.validation-error field="form.subjectId" />
				</div>

				<div>
					<x-admin.select
						id="topic_id"
						label="Тема"
						wire:model="form.topicId"
						:disabled="($form->subjectId ?? '') === ''"
						:placeholder="($form->subjectId ?? '') === '' ? 'Сначала выберите предмет' : 'Выберите тему'"
						:options="$topics->map(fn ($topic) => ['value' => $topic->id, 'label' => $topic->name])->all()"
					/>
					<x-admin.validation-error field="form.topicId" />
				</div>

				<div>
					<x-admin.select
						id="grade_id"
						label="Класс"
						wire:model.live="form.gradeId"
						placeholder="Выберите класс"
						:options="$grades->map(fn ($grade) => ['value' => $grade->id, 'label' => $grade->number . ' класс'])->all()"
					/>
					<x-admin.validation-error field="form.gradeId" />
				</div>

				<div>
					<x-admin.select
						id="quarter_id"
						label="Четверть"
						wire:model="form.quarterId"
						:disabled="($form->gradeId ?? '') === ''"
						:placeholder="($form->gradeId ?? '') === '' ? 'Сначала выберите класс' : 'Выберите четверть'"
						:options="$quarters->map(fn ($quarter) => ['value' => $quarter->id, 'label' => $quarter->number . ' четверть'])->all()"
					/>
					<x-admin.validation-error field="form.quarterId" />
				</div>

				<div class="md:col-span-2">
					<x-admin.input id="slug" label="Slug" wire:model="form.slug" />
					<x-admin.validation-error field="form.slug" />
				</div>

				<div class="md:col-span-2">
					<x-admin.checkbox id="is_active" wire:model="form.isActive" label="Опубликовать рабочий лист" />
				</div>

				<div class="md:col-span-2">
					<x-admin.input id="seo_title" label="SEO title" wire:model="form.seoTitle" />
					<x-admin.validation-error field="form.seoTitle" />
				</div>

				<div class="md:col-span-2">
					<x-admin.textarea id="seo_description" label="SEO description" rows="3" wire:model="form.seoDescription" />
					<x-admin.validation-error field="form.seoDescription" />
				</div>

				<div class="md:col-span-2">
					<x-admin.textarea id="seo_keywords" label="SEO keywords" rows="3" wire:model="form.seoKeywords" />
					<x-admin.validation-error field="form.seoKeywords" />
				</div>

				<div class="md:col-span-2">
					<x-admin.textarea id="article" label="Статья" rows="8" wire:model="form.article" />
					<x-admin.validation-error field="form.article" />
				</div>

				<div class="md:col-span-2 grid gap-4 md:grid-cols-3">
					<div>
						<x-admin.file-input id="file" label="Файл рабочего листа (DOC/DOCX)" wire:model="form.file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
						<x-admin.validation-error field="form.file" />
						@if ($form->currentFileName !== null && $form->currentFileUrl !== null)
							<p class="mt-1 text-xs text-body">
								Текущий файл:
								<a href="{{ $form->currentFileUrl }}" class="text-blue-700 hover:underline" target="_blank">
									{{ $form->currentFileName }}
								</a>
							</p>
						@endif
					</div>

					<div>
						<x-admin.file-input id="preview_file" label="Файл предпросмотра (PDF)" wire:model="form.previewFile" accept=".pdf,application/pdf" />
						<x-admin.validation-error field="form.previewFile" />
						@if ($form->currentPreviewFileName !== null && $form->currentPreviewFileUrl !== null)
							<p class="mt-1 text-xs text-body">
								Текущий файл:
								<a href="{{ $form->currentPreviewFileUrl }}" class="text-blue-700 hover:underline" target="_blank">
									{{ $form->currentPreviewFileName }}
								</a>
							</p>
						@endif
					</div>

					<div>
						<x-admin.file-input id="preview_image" label="Изображение предпросмотра" wire:model="form.previewImage" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" />
						<x-admin.validation-error field="form.previewImage" />
						@if ($form->currentPreviewImageName !== null && $form->currentPreviewImageUrl !== null)
							<p class="mt-1 text-xs text-body">
								Текущий файл:
								<a href="{{ $form->currentPreviewImageUrl }}" class="text-blue-700 hover:underline" target="_blank">
									{{ $form->currentPreviewImageName }}
								</a>
							</p>
						@endif
					</div>
				</div>
			</div>

			<div class="mt-6 flex items-center justify-between gap-4">
				<x-admin.button type="submit">
					{{ $submitLabel }}
				</x-admin.button>

				@if ($showDeleteButton)
					<x-admin.button type="button" variant="secondary" x-on:click="if (confirm('Удалить рабочий лист?')) { $wire.deleteWorksheet() }">
						Удалить рабочий лист
					</x-admin.button>
				@endif
			</div>
		</form>
	</x-admin.card>
</div>
