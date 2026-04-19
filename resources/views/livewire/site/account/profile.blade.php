<div class="flex flex-1 flex-col">
	<x-site.breadcrumbs :items="[
		'Профиль' => '',
	]" />

	<div class="mx-auto w-full max-w-[480px] flex-1 pt-6 md:pt-10">
		<x-site.page-title align="center">
			Личные данные
		</x-site.page-title>

		<form wire:submit="saveProfile" class="mt-8 space-y-5">
			<x-site.input
				id="profile-name"
				label="Имя"
				type="text"
				error-field="form.name"
				wire:model="form.name"
				autocomplete="name"
			/>
			<x-site.input
				id="profile-email"
				label="Электронная почта"
				type="email"
				error-field="form.email"
				wire:model="form.email"
				autocomplete="email"
			/>
			<x-site.button type="submit" variant="primary">
				Сохранить
			</x-site.button>
		</form>
	</div>
</div>
