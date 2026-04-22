<x-admin.card size="full">
	<form wire:submit="{{ $submitAction }}">
		<div class="grid gap-4 md:grid-cols-2">
			<div class="md:col-span-2">
				<x-admin.input id="name" label="Название" wire:model="form.name" />
				<x-admin.validation-error field="form.name" />
			</div>
			<div class="md:col-span-2">
				<x-admin.input id="slug" label="Слаг" wire:model="form.slug" />
				<x-admin.validation-error field="form.slug" />
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
			<x-admin.button type="submit">{{ $submitLabel }}</x-admin.button>
		</div>
	</form>
</x-admin.card>
