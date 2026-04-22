<x-admin.table>
	<x-admin.table.head>
		<tr>
			<x-admin.table.header>Четверть</x-admin.table.header>
			<x-admin.table.compact-header />
		</tr>
	</x-admin.table.head>
	<x-admin.table.body>
		@forelse ($quarters as $quarter)
			<x-admin.table.row wire:key="grade-quarter-{{ $quarter->id }}">
				<x-admin.table.cell>{{ $quarter->full_label }}</x-admin.table.cell>
				<x-admin.table.cell class="w-px whitespace-nowrap">
					<x-admin.button link href="{{ route('admin.quarters.edit', $quarter) }}" variant="secondary" size="sm">
						<x-admin.icon name="pen" />
					</x-admin.button>
				</x-admin.table.cell>
			</x-admin.table.row>
		@empty
			<x-admin.table.row>
				<x-admin.table.cell :colspan="2" class="py-8 text-center text-body">Четвертей в этом классе пока нет.</x-admin.table.cell>
			</x-admin.table.row>
		@endforelse
	</x-admin.table.body>
</x-admin.table>
