<x-admin.tabs.container initial-tab="properties">
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">Редактирование предмета: {{ $subject->name }}</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.subjects.index') }}" variant="secondary">
			<x-admin.icon name="chevron-left" class="mr-2" />
			К списку предметов
		</x-admin.button>
	</div>

	<x-admin.tabs.list>
		<x-admin.tabs.button tab="properties">
			Свойства
		</x-admin.tabs.button>
		<x-admin.tabs.button tab="topics">
			Темы
		</x-admin.tabs.button>
	</x-admin.tabs.list>

	<x-admin.tabs.panel tab="properties">
		@include('livewire.admin.subjects._form', [
			'submitAction' => 'updateSubject',
			'submitLabel' => 'Сохранить изменения',
		])
	</x-admin.tabs.panel>

	<x-admin.tabs.panel tab="topics" cloak>
		@include('livewire.admin.subjects._topics')
	</x-admin.tabs.panel>
</x-admin.tabs.container>

