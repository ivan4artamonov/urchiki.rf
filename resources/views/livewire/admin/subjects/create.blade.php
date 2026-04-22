<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">Создание предмета</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.subjects.index') }}" variant="secondary">
			<x-admin.icon name="chevron-left" class="mr-2" />
			К списку предметов
		</x-admin.button>
	</div>

	@include('livewire.admin.subjects._form', [
		'submitAction' => 'createSubject',
		'submitLabel' => 'Создать предмет',
	])
</div>

