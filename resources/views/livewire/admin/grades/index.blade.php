<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">Классы</x-admin.page-title>
	</div>

	<x-admin.table>
		<x-admin.table.head>
			<tr>
				<x-admin.table.header>Класс</x-admin.table.header>
				<x-admin.table.header>Слаг</x-admin.table.header>
				<x-admin.table.compact-header />
			</tr>
		</x-admin.table.head>
		<x-admin.table.body>
			@forelse ($grades as $grade)
				<x-admin.table.row wire:key="grade-{{ $grade->id }}">
					<x-admin.table.cell>{{ $grade->label }}</x-admin.table.cell>
					<x-admin.table.cell>{{ $grade->slug }}</x-admin.table.cell>
					<x-admin.table.cell class="w-px whitespace-nowrap">
						<x-admin.button link href="{{ route('admin.grades.edit', $grade) }}" variant="secondary" size="sm">
							<x-admin.icon name="pen" />
						</x-admin.button>
					</x-admin.table.cell>
				</x-admin.table.row>
			@empty
				<x-admin.table.row>
					<x-admin.table.cell :colspan="3" class="py-8 text-center text-body">Классов пока нет.</x-admin.table.cell>
				</x-admin.table.row>
			@endforelse
		</x-admin.table.body>
	</x-admin.table>
</div>
