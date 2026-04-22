<x-admin.tabs.container initial-tab="properties">
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">Редактирование класса: {{ $grade->label }}</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.grades.index') }}" variant="secondary">
			<x-admin.icon name="chevron-left" class="mr-2" />
			К списку классов
		</x-admin.button>
	</div>

	<x-admin.tabs.list>
		<x-admin.tabs.button tab="properties">
			Свойства
		</x-admin.tabs.button>
		<x-admin.tabs.button tab="quarters">
			Четверти
		</x-admin.tabs.button>
	</x-admin.tabs.list>

	<x-admin.tabs.panel tab="properties">
		@include('livewire.admin.grades._form')
	</x-admin.tabs.panel>

	<x-admin.tabs.panel tab="quarters" cloak>
		@include('livewire.admin.grades._quarters')
	</x-admin.tabs.panel>
</x-admin.tabs.container>
