<div class="flex min-h-[calc(100dvh-5rem)] w-full items-center justify-center">
	<x-admin.card>
		<form wire:submit="authenticate">
			<h5 class="text-xl font-semibold text-heading mb-6">Вход в админку</h5>

			<div class="mb-4">
				<x-admin.input
					id="email"
					label="Email"
					type="email"
					wire:model="email"
					autocomplete="email"
				/>
				<x-admin.validation-error field="email" />
			</div>

			<div>
				<x-admin.input
					id="password"
					label="Пароль"
					type="password"
					wire:model="password"
					autocomplete="current-password"
				/>
				<x-admin.validation-error field="password" />
			</div>

			<div class="flex items-start my-6">
				<x-admin.checkbox id="remember" wire:model="remember" label="Запомнить меня" />
			</div>

			<x-admin.button
				type="submit"
				class="w-full"
			>
				Войти
			</x-admin.button>
		</form>
	</x-admin.card>
</div>
