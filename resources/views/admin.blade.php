<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Админцентр: {{ $adminSectionTitle ?? 'вход' }}</title>
		<link rel="icon" type="image/svg+xml" href="{{ asset('i/favicon.svg') }}">
		@vite(['resources/css/admin.css', 'resources/js/admin.js'])
		@livewireStyles
	</head>
	<body class="min-h-screen bg-neutral-secondary-soft">
		@unless($hideAdminHeader ?? false)
		<nav class="fixed start-0 top-0 z-20 w-full border-b border-default bg-neutral-primary">
			<div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between px-4">
				<a href="{{ auth()->check() ? route('admin.dashboard') : route('admin.login') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
					<span class="self-center whitespace-nowrap text-xl font-semibold text-heading">{{ config('app.name', 'Laravel') }}</span>
				</a>
				@auth
					<div class="flex items-center space-x-3 md:order-2 rtl:space-x-reverse md:space-x-0">
						<a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="me-3 inline-flex items-center gap-1 text-sm font-medium text-body transition hover:text-heading md:me-4">
							<span>Сайт</span>
							<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
								<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.5 3H17v5.5m0-5.5-7 7M8.5 5H6a3 3 0 0 0-3 3v6a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3v-2.5"/>
							</svg>
						</a>
						<button type="button" class="flex cursor-pointer rounded-full bg-neutral-primary text-sm md:me-0 focus:ring-4 focus:ring-neutral-tertiary" id="admin-user-menu-button" data-dropdown-toggle="admin-user-dropdown" data-dropdown-placement="bottom">
							<span class="sr-only">Открыть меню пользователя</span>
							<span class="flex h-8 w-8 items-center justify-center rounded-full bg-neutral-tertiary text-sm font-medium text-heading">{{ auth()->user()->initial }}</span>
						</button>
						<div class="z-50 hidden w-44 overflow-hidden rounded-base border border-default-medium bg-neutral-primary-medium shadow-lg" id="admin-user-dropdown">
							<a href="{{ route('admin.users.edit', auth()->user()) }}" class="block border-b border-default px-4 py-3 text-sm hover:bg-neutral-secondary-soft">
								<span class="block font-medium text-heading">{{ auth()->user()->name }}</span>
								<span class="block truncate text-body">{{ auth()->user()->email }}</span>
							</a>
							<ul class="text-sm font-medium text-body">
								<li>
									<form method="POST" action="{{ route('admin.logout') }}" class="w-full">
										@csrf
										<button type="submit" class="w-full cursor-pointer px-4 py-3 text-left hover:bg-neutral-secondary-soft">
											Выйти
										</button>
									</form>
								</li>
							</ul>
						</div>
						<button data-collapse-toggle="navbar-admin" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-base p-2 text-sm text-body hover:bg-neutral-secondary-soft hover:text-heading focus:ring-2 focus:ring-neutral-tertiary focus:outline-none md:hidden">
							<span class="sr-only">Открыть главное меню</span>
							<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/></svg>
						</button>
					</div>
					<div class="hidden w-full items-center justify-between md:order-1 md:flex md:w-auto" id="navbar-admin">
						<ul class="mt-4 flex flex-col rounded-base border border-default bg-neutral-secondary-soft p-4 font-medium md:mt-0 md:flex-row md:space-x-8 md:border-0 md:bg-neutral-primary rtl:space-x-reverse">
							@foreach($adminNavItems ?? [] as $label => $href)
								<x-admin.nav-link :href="$href">{{ $label }}</x-admin.nav-link>
							@endforeach
						</ul>
					</div>
				@endauth
			</div>
		</nav>
		@endunless
		<main @class([
			'pb-10',
			'pt-20' => !($hideAdminHeader ?? false),
			'pt-6' => $hideAdminHeader ?? false,
		])>
			<div class="mx-auto max-w-screen-xl px-4 py-6">
				<x-admin.notification />

				{{ $slot }}
			</div>
		</main>
		@livewireScripts
	</body>
</html>
