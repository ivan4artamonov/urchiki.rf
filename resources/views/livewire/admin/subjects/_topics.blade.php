<div class="mb-4 flex justify-end">
	<x-admin.button link href="{{ route('admin.topics.create', $subject) }}" variant="secondary">
		<x-admin.icon name="plus" class="mr-2" />
		Создать тему
	</x-admin.button>
</div>

<x-admin.table>
	<x-admin.table.head>
		<tr>
			<x-admin.table.compact-header />
			<x-admin.table.header>Название</x-admin.table.header>
			<x-admin.table.compact-header />
		</tr>
	</x-admin.table.head>
	<x-admin.table.body x-sort="$wire.moveTopic($item, $position)">
		@forelse ($topics as $topic)
			<x-admin.table.row wire:key="subject-topic-{{ $topic->id }}" x-sort:item="{{ $topic->id }}">
				<x-admin.table.drag-handle-cell />
				<x-admin.table.cell>{{ $topic->name }}</x-admin.table.cell>
				<x-admin.table.cell class="w-px whitespace-nowrap">
					<x-admin.button link href="{{ route('admin.topics.edit', $topic) }}" variant="secondary" size="sm">
						<x-admin.icon name="pen" />
					</x-admin.button>
				</x-admin.table.cell>
			</x-admin.table.row>
		@empty
			<x-admin.table.row>
				<x-admin.table.cell :colspan="3" class="py-8 text-center text-body">Тем в этом предмете пока нет.</x-admin.table.cell>
			</x-admin.table.row>
		@endforelse
	</x-admin.table.body>
</x-admin.table>
