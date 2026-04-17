<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">{{ $title }}</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.faq.index') }}" variant="secondary">
			<x-admin.icon name="chevron-left" class="mr-2" />
			К списку вопросов
		</x-admin.button>
	</div>

	<x-admin.card size="full">
		<form wire:submit="{{ $submitAction }}">
			<div class="grid gap-4">
				<div>
					<x-admin.input
						id="question"
						label="Вопрос"
						wire:model="form.question"
					/>
					<x-admin.validation-error field="form.question" />
				</div>

				<div>
					<x-admin.textarea
						id="answer"
						label="Ответ"
						rows="6"
						wire:model="form.answer"
					/>
					<x-admin.validation-error field="form.answer" />
				</div>
			</div>

			<div class="my-6">
				<x-admin.checkbox id="is_active" wire:model="form.isActive" label="Показывать на сайте" />
			</div>

			<x-admin.button type="submit">
				{{ $submitLabel }}
			</x-admin.button>
		</form>
	</x-admin.card>
</div>
