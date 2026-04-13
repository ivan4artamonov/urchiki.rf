<div class="min-h-screen flex items-center justify-center bg-neutral-secondary-soft p-4">
	<x-card>
		<form wire:submit="authenticate">
			<h5 class="text-xl font-semibold text-heading mb-6">Вход в админку</h5>

			<div class="mb-4">
				<x-input
					id="email"
					label="Email"
					type="email"
					wire:model="email"
					autocomplete="email"
				/>
				@error('email')
					<p class="text-sm text-red-600 mt-1">{{ $message }}</p>
				@enderror
			</div>

			<div>
				<x-input
					id="password"
					label="Пароль"
					type="password"
					wire:model="password"
					autocomplete="current-password"
				/>
				@error('password')
					<p class="text-sm text-red-600 mt-1">{{ $message }}</p>
				@enderror
			</div>

			<div class="flex items-start my-6">
				<x-checkbox id="remember" wire:model="remember" label="Запомнить меня" />
			</div>

			<x-button
				type="submit"
				class="w-full"
			>
				Войти
			</x-button>
		</form>
	</x-card>
</div>
