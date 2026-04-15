<div>
	<x-admin.page-title>Пользователи</x-admin.page-title>

	<x-admin.table>
		<x-admin.table.head>
			<tr>
				<x-admin.table.header>ID</x-admin.table.header>
				<x-admin.table.header>Имя</x-admin.table.header>
				<x-admin.table.header>Email</x-admin.table.header>
				<x-admin.table.header>Регистрация</x-admin.table.header>
			</tr>
		</x-admin.table.head>
		<x-admin.table.body>
			@forelse ($users as $user)
				<x-admin.table.row>
					<x-admin.table.cell class="whitespace-nowrap">{{ $user->id }}</x-admin.table.cell>
					<x-admin.table.cell>
						{{ $user->name ?: '—' }}
					</x-admin.table.cell>
					<x-admin.table.cell>{{ $user->email }}</x-admin.table.cell>
					<x-admin.table.cell class="whitespace-nowrap">{{ $user->created_at?->format('d.m.Y H:i') ?? '—' }}</x-admin.table.cell>
				</x-admin.table.row>
			@empty
				<x-admin.table.row>
					<x-admin.table.cell :colspan="4" class="py-8 text-center text-body">Пользователей пока нет.</x-admin.table.cell>
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
