<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">Рабочие листы</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.worksheets.create') }}" variant="secondary">
			<x-admin.icon name="plus" class="mr-2" />
			Добавить рабочий лист
		</x-admin.button>
	</div>

	<x-admin.card size="full" class="mb-6">
		<div class="grid gap-4" style="grid-template-columns: repeat(5, minmax(0, 1fr));">
			<x-admin.select
				id="filter_subject"
				label="Предмет"
				wire:model.live="subjectId"
				placeholder="Все предметы"
				:options="$subjects->map(fn ($subject) => ['value' => $subject->id, 'label' => $subject->name])->all()"
			/>

			<x-admin.select
				id="filter_topic"
				label="Тема"
				wire:model.live="topicId"
				:disabled="$subjectId === ''"
				:placeholder="$subjectId === '' ? 'Сначала выберите предмет' : 'Все темы'"
				:options="$topics->map(fn ($topic) => ['value' => $topic->id, 'label' => $topic->name])->all()"
			/>

			<x-admin.select
				id="filter_grade"
				label="Класс"
				wire:model.live="gradeId"
				placeholder="Все классы"
				:options="$grades->map(fn ($grade) => ['value' => $grade->id, 'label' => $grade->number . ' класс'])->all()"
			/>

			<x-admin.select
				id="filter_quarter"
				label="Четверть"
				wire:model.live="quarterId"
				:disabled="$gradeId === ''"
				:placeholder="$gradeId === '' ? 'Сначала выберите класс' : 'Все четверти'"
				:options="$quarters->map(fn ($quarter) => ['value' => $quarter->id, 'label' => $quarter->number . ' четверть'])->all()"
			/>

			<x-admin.input
				id="filter_title"
				label="Название"
				placeholder="Часть названия..."
				wire:model.live.debounce.300ms="titleSearch"
			/>
		</div>
	</x-admin.card>

	<x-admin.table>
		<x-admin.table.head>
			<tr>
				<x-admin.table.header>Название</x-admin.table.header>
				<x-admin.table.header>Предмет</x-admin.table.header>
				<x-admin.table.header>Тема</x-admin.table.header>
				<x-admin.table.header>Класс</x-admin.table.header>
				<x-admin.table.header>Четверть</x-admin.table.header>
				<x-admin.table.header>Публикация</x-admin.table.header>
				<x-admin.table.header>Загружен</x-admin.table.header>
				<x-admin.table.compact-header />
			</tr>
		</x-admin.table.head>
		<x-admin.table.body>
			@forelse ($worksheets as $worksheet)
				<x-admin.table.row>
					<x-admin.table.cell>{{ $worksheet->title }}</x-admin.table.cell>
					<x-admin.table.cell>{{ $worksheet->topic?->subject?->name ?? '—' }}</x-admin.table.cell>
					<x-admin.table.cell>{{ $worksheet->topic?->name ?? '—' }}</x-admin.table.cell>
					<x-admin.table.cell class="whitespace-nowrap">{{ $worksheet->quarter?->grade?->number ? $worksheet->quarter->grade->number . ' класс' : '—' }}</x-admin.table.cell>
					<x-admin.table.cell class="whitespace-nowrap">{{ $worksheet->quarter?->number ? $worksheet->quarter->number . ' четверть' : '—' }}</x-admin.table.cell>
					<x-admin.table.cell class="whitespace-nowrap">{{ $worksheet->is_active ? 'Опубликован' : 'Скрыт' }}</x-admin.table.cell>
					<x-admin.table.cell class="whitespace-nowrap">{{ $worksheet->created_at?->format('d.m.Y H:i') ?? '—' }}</x-admin.table.cell>
					<x-admin.table.cell class="w-px whitespace-nowrap">
						<x-admin.button link href="{{ route('admin.worksheets.edit', $worksheet) }}" variant="secondary" size="sm">
							<x-admin.icon name="pen" />
						</x-admin.button>
					</x-admin.table.cell>
				</x-admin.table.row>
			@empty
				<x-admin.table.row>
					<x-admin.table.cell :colspan="8" class="py-8 text-center text-body">Рабочих листов пока нет.</x-admin.table.cell>
				</x-admin.table.row>
			@endforelse
		</x-admin.table.body>
	</x-admin.table>

	@if ($worksheets->hasPages())
		<div class="mt-4">
			{{ $worksheets->links() }}
		</div>
	@endif
</div>
