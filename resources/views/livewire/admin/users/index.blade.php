<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">Пользователи</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.users.create') }}" variant="secondary">
			<x-admin.icon name="plus" class="mr-2" />
			Создать пользователя
		</x-admin.button>
	</div>

	<x-admin.table>
		<x-admin.table.head>
			<tr>
				<x-admin.table.header>ID</x-admin.table.header>
				<x-admin.table.header>Имя</x-admin.table.header>
				<x-admin.table.header>Email</x-admin.table.header>
				<x-admin.table.header>Админ</x-admin.table.header>
				<x-admin.table.header>Регистрация</x-admin.table.header>
				<x-admin.table.compact-header />
			</tr>
		</x-admin.table.head>
		<x-admin.table.body>
			@forelse ($users as $user)
				<x-admin.table.row>
					<x-admin.table.cell class="whitespace-nowrap">{{ $user->id }}</x-admin.table.cell>
					<x-admin.table.cell>{{ $user->name ?: '—' }}</x-admin.table.cell>
					<x-admin.table.cell>{{ $user->email }}</x-admin.table.cell>
					<x-admin.table.cell class="whitespace-nowrap">{{ $user->is_admin ? 'Да' : 'Нет' }}</x-admin.table.cell>
					<x-admin.table.cell class="whitespace-nowrap">{{ $user->created_at?->format('d.m.Y H:i') ?? '—' }}</x-admin.table.cell>
					<x-admin.table.cell class="w-px whitespace-nowrap">
						<x-admin.button link href="{{ route('admin.users.edit', $user) }}" variant="secondary" size="sm">
							<x-admin.icon name="pen" />
						</x-admin.button>
					</x-admin.table.cell>
				</x-admin.table.row>
			@empty
				<x-admin.table.row>
					<x-admin.table.cell :colspan="6" class="py-8 text-center text-body">Пользователей пока нет.</x-admin.table.cell>
				</x-admin.table.row>
			@endforelse
		</x-admin.table.body>
	</x-admin.table>

	@if ($users->hasPages())
		<div class="mt-4">
			{{ $users->links() }}
		</div>
	@endif
</div>
