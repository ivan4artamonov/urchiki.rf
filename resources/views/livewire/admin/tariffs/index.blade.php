<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">Тарифы</x-admin.page-title>

		<x-admin.button-link href="{{ route('admin.tariffs.create') }}" variant="secondary">
			Создать тариф
		</x-admin.button-link>
	</div>

	<div class="space-y-8">
		<section>
			<x-admin.section-title>Активные тарифы</x-admin.section-title>

			<x-admin.table>
				<x-admin.table.head>
					<tr>
						<x-admin.table.compact-header />
						<x-admin.table.header>Название</x-admin.table.header>
						<x-admin.table.header>Срок (дни)</x-admin.table.header>
						<x-admin.table.header>Лимит скачиваний</x-admin.table.header>
						<x-admin.table.header>Цена (руб.)</x-admin.table.header>
						<x-admin.table.header>Акцентный</x-admin.table.header>
					</tr>
				</x-admin.table.head>
				<x-admin.table.body
					x-data="sortableByNeighbors($wire, 'reorderActiveTariff')"
					x-sort="reorder($item)"
				>
					@forelse ($activeTariffs as $tariff)
						<x-admin.table.row x-sort:item="{{ $tariff->id }}" data-tariff-id="{{ $tariff->id }}">
							<x-admin.table.drag-handle-cell />
							<x-admin.table.cell>{{ $tariff->name }}</x-admin.table.cell>
							<x-admin.table.cell class="whitespace-nowrap">{{ $tariff->duration_days }}</x-admin.table.cell>
							<x-admin.table.cell class="whitespace-nowrap">{{ $tariff->downloads_limit }}</x-admin.table.cell>
							<x-admin.table.cell class="whitespace-nowrap">{{ $tariff->price }}</x-admin.table.cell>
							<x-admin.table.cell class="whitespace-nowrap">{{ $tariff->is_featured ? 'Да' : 'Нет' }}</x-admin.table.cell>
						</x-admin.table.row>
					@empty
						<x-admin.table.row>
							<x-admin.table.cell :colspan="6" class="py-8 text-center text-body">Активных тарифов пока нет.</x-admin.table.cell>
						</x-admin.table.row>
					@endforelse
				</x-admin.table.body>
			</x-admin.table>
		</section>

		@if ($archivedTariffs->isNotEmpty())
			<section>
				<x-admin.section-title>Архивные тарифы</x-admin.section-title>

				<x-admin.table>
					<x-admin.table.head>
						<tr>
							<x-admin.table.compact-header />
							<x-admin.table.header>Название</x-admin.table.header>
							<x-admin.table.header>Срок (дни)</x-admin.table.header>
							<x-admin.table.header>Лимит скачиваний</x-admin.table.header>
							<x-admin.table.header>Цена (руб.)</x-admin.table.header>
							<x-admin.table.header>Акцентный</x-admin.table.header>
						</tr>
					</x-admin.table.head>
					<x-admin.table.body
						x-data="sortableByNeighbors($wire, 'reorderArchivedTariff')"
						x-sort="reorder($item)"
					>
						@foreach ($archivedTariffs as $tariff)
							<x-admin.table.row x-sort:item="{{ $tariff->id }}" data-tariff-id="{{ $tariff->id }}">
								<x-admin.table.drag-handle-cell />
								<x-admin.table.cell>{{ $tariff->name }}</x-admin.table.cell>
								<x-admin.table.cell class="whitespace-nowrap">{{ $tariff->duration_days }}</x-admin.table.cell>
								<x-admin.table.cell class="whitespace-nowrap">{{ $tariff->downloads_limit }}</x-admin.table.cell>
								<x-admin.table.cell class="whitespace-nowrap">{{ $tariff->price }}</x-admin.table.cell>
								<x-admin.table.cell class="whitespace-nowrap">{{ $tariff->is_featured ? 'Да' : 'Нет' }}</x-admin.table.cell>
							</x-admin.table.row>
						@endforeach
					</x-admin.table.body>
				</x-admin.table>
			</section>
		@endif
	</div>
</div>
