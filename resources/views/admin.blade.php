<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Админцентр: {{ $adminSectionTitle ?? 'вход' }}</title>
		@vite(['resources/css/app.css', 'resources/js/app.js'])
		@livewireStyles
	</head>
	<body class="min-h-screen bg-neutral-secondary-soft">
		<nav class="fixed start-0 top-0 z-20 w-full border-b border-default bg-neutral-primary">
			<div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between px-4">
				<a href="{{ auth()->check() ? route('admin.dashboard') : route('admin.login') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
					<span class="self-center whitespace-nowrap text-xl font-semibold text-heading">{{ config('app.name', 'Laravel') }}</span>
				</a>
				@auth
					<div class="flex items-center space-x-3 md:order-2 rtl:space-x-reverse md:space-x-0">
						<button type="button" class="flex rounded-full bg-neutral-primary text-sm md:me-0 focus:ring-4 focus:ring-neutral-tertiary" id="admin-user-menu-button" aria-expanded="false" data-dropdown-toggle="admin-user-dropdown" data-dropdown-placement="bottom">
							<span class="sr-only">Открыть меню пользователя</span>
							<span class="flex h-8 w-8 items-center justify-center rounded-full bg-neutral-tertiary text-sm font-medium text-heading">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name ?: auth()->user()->email, 0, 1)) }}</span>
						</button>
						<div class="z-50 hidden w-44 rounded-base border border-default-medium bg-neutral-primary-medium shadow-lg" id="admin-user-dropdown">
							<div class="border-b border-default px-4 py-3 text-sm">
								<span class="block font-medium text-heading">{{ auth()->user()->name }}</span>
								<span class="block truncate text-body">{{ auth()->user()->email }}</span>
							</div>
							<ul class="p-2 text-sm font-medium text-body" aria-labelledby="admin-user-menu-button">
								<x-admin.dropdown-item :href="route('admin.dashboard')">Панель</x-admin.dropdown-item>
								<x-admin.dropdown-item href="#">Настройки</x-admin.dropdown-item>
								<x-admin.dropdown-item :action="route('admin.logout')">Выйти</x-admin.dropdown-item>
							</ul>
						</div>
						<button data-collapse-toggle="navbar-admin" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-base p-2 text-sm text-body hover:bg-neutral-secondary-soft hover:text-heading focus:ring-2 focus:ring-neutral-tertiary focus:outline-none md:hidden" aria-controls="navbar-admin" aria-expanded="false">
							<span class="sr-only">Открыть главное меню</span>
							<svg class="h-6 w-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/></svg>
						</button>
					</div>
					<div class="hidden w-full items-center justify-between md:order-1 md:flex md:w-auto" id="navbar-admin">
						@php
							$adminNavItems = [
								['label' => 'Рабочие листы', 'href' => route('admin.worksheets'), 'active' => request()->routeIs('admin.worksheets')],
								['label' => 'Пользователи', 'href' => route('admin.users'), 'active' => request()->routeIs('admin.users')],
								['label' => 'ЧаВо', 'href' => route('admin.faq'), 'active' => request()->routeIs('admin.faq')],
								['label' => 'Тарифы', 'href' => route('admin.tariffs'), 'active' => request()->routeIs('admin.tariffs')],
							];
						@endphp
						<ul class="mt-4 flex flex-col rounded-base border border-default bg-neutral-secondary-soft p-4 font-medium md:mt-0 md:flex-row md:space-x-8 md:border-0 md:bg-neutral-primary rtl:space-x-reverse">
							@foreach($adminNavItems as $item)
								<x-admin.nav-link :href="$item['href']" :active="$item['active'] ?? false">{{ $item['label'] }}</x-admin.nav-link>
							@endforeach
						</ul>
					</div>
				@endauth
			</div>
		</nav>
		<main class="pt-20 pb-10">
			<div class="mx-auto max-w-screen-xl px-4 py-6">
				{{ $slot }}
			</div>
		</main>
		@livewireScripts
	</body>
</html>
