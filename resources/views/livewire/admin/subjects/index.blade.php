<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">Предметы</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.subjects.create') }}" variant="secondary">
			<x-admin.icon name="plus" class="mr-2" />
			Создать предмет
		</x-admin.button>
	</div>

	<x-admin.table>
		<x-admin.table.head>
			<tr>
				<x-admin.table.compact-header />
				<x-admin.table.header>Название</x-admin.table.header>
				<x-admin.table.header>Слаг</x-admin.table.header>
				<x-admin.table.header>Тем</x-admin.table.header>
				<x-admin.table.compact-header />
			</tr>
		</x-admin.table.head>
		<x-admin.table.body x-sort="$wire.moveSubject($item, $position)">
			@forelse ($subjects as $subject)
				<x-admin.table.row wire:key="subject-{{ $subject->id }}" x-sort:item="{{ $subject->id }}">
					<x-admin.table.drag-handle-cell />
					<x-admin.table.cell>{{ $subject->name }}</x-admin.table.cell>
					<x-admin.table.cell>{{ $subject->slug }}</x-admin.table.cell>
					<x-admin.table.cell class="whitespace-nowrap">{{ $subject->topics_count }}</x-admin.table.cell>
					<x-admin.table.cell class="w-px whitespace-nowrap">
						<x-admin.button link href="{{ route('admin.subjects.edit', $subject) }}" variant="secondary" size="sm">
							<x-admin.icon name="pen" />
						</x-admin.button>
					</x-admin.table.cell>
				</x-admin.table.row>
			@empty
				<x-admin.table.row>
					<x-admin.table.cell :colspan="5" class="py-8 text-center text-body">Предметов пока нет.</x-admin.table.cell>
				</x-admin.table.row>
			@endforelse
		</x-admin.table.body>
	</x-admin.table>
</div>

