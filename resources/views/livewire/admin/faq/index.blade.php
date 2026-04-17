<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">ЧаВо</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.faq.create') }}" variant="secondary">
			<x-admin.icon name="plus" class="mr-2" />
			Создать вопрос
		</x-admin.button>
	</div>

	<div class="space-y-8">
		<section>
			<x-admin.section-title>Активные вопросы</x-admin.section-title>

			<x-admin.table>
				<x-admin.table.head>
					<tr>
						<x-admin.table.compact-header />
						<x-admin.table.header>Вопрос</x-admin.table.header>
						<x-admin.table.header>Ответ</x-admin.table.header>
						<x-admin.table.compact-header />
					</tr>
				</x-admin.table.head>
				<x-admin.table.body
					x-sort="$wire.moveFaqItem($item, $position)"
				>
					@forelse ($activeFaqItems as $faqItem)
						<x-admin.table.row wire:key="active-faq-item-{{ $faqItem->id }}" x-sort:item="{{ $faqItem->id }}">
							<x-admin.table.drag-handle-cell />
							<x-admin.table.cell>{{ $faqItem->question }}</x-admin.table.cell>
							<x-admin.table.cell class="max-w-xl truncate">{{ $faqItem->answer }}</x-admin.table.cell>
							<x-admin.table.cell class="w-px whitespace-nowrap">
								<x-admin.button link href="{{ route('admin.faq.edit', $faqItem) }}" variant="secondary" size="sm">
									<x-admin.icon name="pen" />
								</x-admin.button>
							</x-admin.table.cell>
						</x-admin.table.row>
					@empty
						<x-admin.table.row>
							<x-admin.table.cell :colspan="4" class="py-8 text-center text-body">Активных вопросов пока нет.</x-admin.table.cell>
						</x-admin.table.row>
					@endforelse
				</x-admin.table.body>
			</x-admin.table>
		</section>

		@if ($archivedFaqItems->isNotEmpty())
			<section>
				<x-admin.section-title>Архивные вопросы</x-admin.section-title>

				<x-admin.table>
					<x-admin.table.head>
						<tr>
							<x-admin.table.compact-header />
							<x-admin.table.header>Вопрос</x-admin.table.header>
							<x-admin.table.header>Ответ</x-admin.table.header>
							<x-admin.table.compact-header />
						</tr>
					</x-admin.table.head>
					<x-admin.table.body
						x-sort="$wire.moveFaqItem($item, $position)"
					>
						@foreach ($archivedFaqItems as $faqItem)
							<x-admin.table.row wire:key="archived-faq-item-{{ $faqItem->id }}" x-sort:item="{{ $faqItem->id }}">
								<x-admin.table.drag-handle-cell />
								<x-admin.table.cell>{{ $faqItem->question }}</x-admin.table.cell>
								<x-admin.table.cell class="max-w-xl truncate">{{ $faqItem->answer }}</x-admin.table.cell>
								<x-admin.table.cell class="w-px whitespace-nowrap">
									<x-admin.button link href="{{ route('admin.faq.edit', $faqItem) }}" variant="secondary" size="sm">
										<x-admin.icon name="pen" />
									</x-admin.button>
								</x-admin.table.cell>
							</x-admin.table.row>
						@endforeach
					</x-admin.table.body>
				</x-admin.table>
			</section>
		@endif
	</div>
</div>
