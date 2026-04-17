<div>
	<div class="mb-6 flex items-center justify-between gap-4">
		<x-admin.page-title margin="mb-0">{{ $title }}</x-admin.page-title>

		<x-admin.button link href="{{ route('admin.users.index') }}" variant="secondary">
			<x-admin.icon name="chevron-left" class="mr-2" />
			К списку пользователей
		</x-admin.button>
	</div>

	<x-admin.card size="full">
		<form wire:submit="{{ $submitAction }}">
			<div class="grid gap-4 md:grid-cols-2">
				<div>
					<x-admin.input
						id="name"
						label="Имя"
						wire:model="form.name"
					/>
					<x-admin.validation-error field="form.name" />
				</div>

				<div>
					<x-admin.input
						id="email"
						label="Email"
						type="email"
						wire:model="form.email"
					/>
					<x-admin.validation-error field="form.email" />
				</div>

			</div>

			<div class="my-6">
				<x-admin.checkbox id="is_admin" wire:model.live="form.isAdmin" label="Администратор" />
			</div>

			@if ($form->isAdmin)
				<div class="mb-6">
					<x-admin.input
						id="password"
						label="Пароль"
						type="password"
						wire:model="form.password"
					/>
					<x-admin.validation-error field="form.password" />
					<p class="mt-2 text-sm text-body">
						Для администратора пароль обязателен. На редактировании оставьте поле пустым, чтобы не менять текущий пароль.
					</p>
				</div>
			@endif

			<x-admin.button type="submit">
				{{ $submitLabel }}
			</x-admin.button>
		</form>
	</x-admin.card>
</div>
