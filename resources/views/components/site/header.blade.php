@php
	$home = route('site.home');
	$navActiveHome = request()->routeIs('site.home');
@endphp

<header class="sticky top-0 z-50 border-b border-urchiki-border bg-urchiki-bg/90 backdrop-blur-md">
	<x-site.page-container class="flex flex-wrap items-center justify-between gap-3 py-2.5">
		@if ($navActiveHome)
			<span class="flex shrink-0 items-center">
				<img src="{{ asset('i/logo.svg') }}" alt="{{ config('app.name') }}" width="180" height="60" class="block h-9 w-auto md:h-10">
			</span>
		@else
			<a href="{{ $home }}" wire:navigate class="flex shrink-0 items-center no-underline">
				<img src="{{ asset('i/logo.svg') }}" alt="{{ config('app.name') }}" width="180" height="60" class="block h-9 w-auto md:h-10">
			</a>
		@endif

		<div class="flex items-center gap-2 md:order-2">
			<button type="button" class="inline-flex h-10 items-center gap-1 rounded-lg px-2.5 text-sm font-medium text-urchiki-text-sec hover:bg-urchiki-accent-light hover:text-urchiki-accent md:hidden" data-collapse-toggle="site-nav">
				<svg class="h-6 w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/></svg>
				<span>Меню</span>
			</button>

			@guest
				<x-site.auth-register-link />
			@endguest

			@auth
				<div class="relative">
					<button type="button" class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border-2 border-transparent bg-urchiki-accent-light text-urchiki-accent transition-colors hover:border-urchiki-accent" id="site-user-menu-button" data-dropdown-toggle="site-user-dropdown" data-dropdown-placement="bottom-end" data-dropdown-offset-distance="10" data-dropdown-offset-skidding="0">
						<span class="sr-only">Меню аккаунта</span>
						{{-- Heroicons v2 solid user (MIT) — ~10% меньше прежнего h-7 --}}
						<svg class="h-[1.575rem] w-[1.575rem] shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"/></svg>
					</button>
					<div class="z-50 hidden w-48 rounded-xl border border-urchiki-border bg-urchiki-card py-1.5 shadow-lg" id="site-user-dropdown">
						<x-site.user-menu-link>Профиль</x-site.user-menu-link>
						<x-site.user-menu-link :href="url('/account/worksheets')">Мои листы</x-site.user-menu-link>
						<x-site.user-menu-link :href="url('/subscribe')">Подписка и оплата</x-site.user-menu-link>
						<form method="POST" action="{{ route('site.logout') }}" class="m-0">
							@csrf
							<x-site.user-menu-link :button="true">Выйти</x-site.user-menu-link>
						</form>
					</div>
				</div>
			@endauth
		</div>

		<nav class="mt-3 hidden w-full border-t border-urchiki-border pt-5 md:order-1 md:mt-0 md:flex md:w-auto md:flex-row md:items-center md:border-0 md:pt-0" id="site-nav">
			<div class="flex flex-col gap-y-2 md:flex md:flex-row md:items-center md:gap-x-2 md:gap-y-0">
				<x-site.nav-link :href="$home">Предметы</x-site.nav-link>
				<x-site.nav-link :href="$home">Классы</x-site.nav-link>
				<x-site.nav-link :href="url('/subscribe')">Подписка</x-site.nav-link>
				<x-site.nav-link :href="url('/faq')">FAQ</x-site.nav-link>
			</div>
		</nav>
	</x-site.page-container>
</header>
