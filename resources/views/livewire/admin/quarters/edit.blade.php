<x-admin.card size="full">
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">Редактирование четверти: {{ $quarter->full_label }}</x-admin.page-title>

		<x-admin.button link href="{{ $backUrl }}" variant="secondary">
			<x-admin.icon name="chevron-left" class="mr-2" />
			К классу {{ $grade->label }}
		</x-admin.button>
	</div>

	<form wire:submit="updateQuarter">
		<div class="grid gap-4 md:grid-cols-2">
			<div class="md:col-span-2">
				<x-admin.input id="number" label="Номер четверти" value="{{ $quarter->number }}" readonly />
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
		</div>

		<div class="mt-6">
			<x-admin.button type="submit">Сохранить изменения</x-admin.button>
		</div>
	</form>
</x-admin.card>
