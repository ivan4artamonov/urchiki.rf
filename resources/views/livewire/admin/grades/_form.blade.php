<x-admin.card size="full">
	<form wire:submit="updateGrade">
		<div class="grid gap-4 md:grid-cols-2">
			<div class="md:col-span-2">
				<x-admin.input id="number" label="Номер класса" value="{{ $grade->number }}" readonly />
			</div>
			<div class="md:col-span-2">
				<x-admin.input id="slug" label="Слаг" value="{{ $grade->slug }}" readonly />
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
