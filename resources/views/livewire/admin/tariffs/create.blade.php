<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">Создание тарифа</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.tariffs.index') }}" variant="secondary">
			К списку тарифов
		</x-admin.button>
	</div>

	<x-admin.card size="full">
		<form wire:submit="createTariff">
			<div class="grid gap-4 md:grid-cols-3">
				<div class="md:col-span-3">
					<x-admin.input
						id="name"
						label="Название"
						wire:model="form.name"
					/>
					<x-admin.validation-error field="form.name" />
				</div>

				<div class="md:col-span-3">
					<x-admin.textarea
						id="description"
						label="Описание"
						rows="3"
						wire:model="form.description"
					/>
					<x-admin.validation-error field="form.description" />
				</div>

				<div>
					<x-admin.input
						id="duration_days"
						label="Длительность (дней)"
						type="number"
						wire:model="form.durationDays"
						min="1"
						step="1"
					/>
					<x-admin.validation-error field="form.durationDays" />
				</div>

				<div>
					<x-admin.input
						id="downloads_limit"
						label="Лимит скачиваний"
						type="number"
						wire:model="form.downloadsLimit"
						min="1"
						step="1"
					/>
					<x-admin.validation-error field="form.downloadsLimit" />
				</div>

				<div>
					<x-admin.input
						id="price"
						label="Цена (руб.)"
						type="number"
						wire:model="form.price"
						min="0"
						step="1"
					/>
					<x-admin.validation-error field="form.price" />
				</div>
			</div>

			<div class="my-6 flex flex-col gap-3">
				<x-admin.checkbox id="is_active" wire:model="form.isActive" label="Активный тариф" />
				<x-admin.checkbox id="is_featured" wire:model="form.isFeatured" label="Выделить как акцентный" />
			</div>

			<x-admin.button type="submit">
				Создать тариф
			</x-admin.button>
		</form>
	</x-admin.card>
</div>
