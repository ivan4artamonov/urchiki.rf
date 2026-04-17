@use('App\Support\CopyrightYearRange')

@php
	$home = route('site.home');
	$navActiveHome = request()->routeIs('site.home');
@endphp

<footer class="mt-auto border-t border-urchiki-border bg-urchiki-surface py-12 pb-8">
	<x-site.page-container>
		<div class="mb-9 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
			<div>
				@if ($navActiveHome)
					<span class="inline-flex items-center">
						<img src="{{ asset('i/logo.svg') }}" alt="{{ config('app.name') }}" width="180" height="60" class="block h-8 w-auto">
					</span>
				@else
					<a href="{{ $home }}" wire:navigate class="inline-flex items-center no-underline">
						<img src="{{ asset('i/logo.svg') }}" alt="{{ config('app.name') }}" width="180" height="60" class="block h-8 w-auto">
					</a>
				@endif
				<p class="mt-2 text-sm leading-relaxed text-urchiki-muted">
					Готовые рабочие листы для учителей,<br>репетиторов и родителей. 1–11 класс.
				</p>
			</div>
			<div>
				<h2 class="mb-3 font-site-heading text-sm font-extrabold text-urchiki-text">Предметы</h2>
				<ul class="space-y-1 text-sm text-urchiki-muted">
					<li><a href="{{ url('/matematika') }}" class="hover:text-urchiki-accent">Математика</a></li>
					<li><a href="{{ url('/russkij-yazyk') }}" class="hover:text-urchiki-accent">Русский язык</a></li>
					<li><a href="{{ url('/okruzhayushchij-mir') }}" class="hover:text-urchiki-accent">Окружающий мир</a></li>
					<li><a href="{{ url('/anglijskij-yazyk') }}" class="hover:text-urchiki-accent">Английский язык</a></li>
				</ul>
			</div>
			<div>
				<h2 class="mb-3 font-site-heading text-sm font-extrabold text-urchiki-text">Классы</h2>
				<ul class="space-y-1 text-sm text-urchiki-muted">
					@foreach ($footerGrades as $grade)
						<li><a href="{{ url('/' . $grade->slug) }}" class="hover:text-urchiki-accent">{{ $grade->label }}</a></li>
					@endforeach
				</ul>
			</div>
			<div>
				<h2 class="mb-3 font-site-heading text-sm font-extrabold text-urchiki-text">Сервис</h2>
				<ul class="space-y-1 text-sm text-urchiki-muted">
					<li><a href="{{ url('/subscribe') }}" class="hover:text-urchiki-accent">Тарифы</a></li>
					<li><a href="{{ url('/faq') }}" class="hover:text-urchiki-accent">Вопросы и ответы</a></li>
					<li><span class="text-urchiki-muted">Политика конфиденциальности</span></li>
				</ul>
			</div>
		</div>
		<div class="flex flex-col gap-4 border-t border-urchiki-border pt-5 text-xs text-urchiki-muted">
			<div class="flex flex-col flex-wrap justify-between gap-3 sm:flex-row sm:items-center">
				<span>© {{ CopyrightYearRange::format() }} {{ config('app.name') }}</span>
				<span>Все материалы соответствуют ФГОС</span>
			</div>
			<div class="leading-relaxed">
				<div>{{ config('site.legal.sole_proprietor_name') }}</div>
				<div>ИНН {{ config('site.legal.inn') }}</div>
				<div>ОГРНИП {{ config('site.legal.ogrnip') }}</div>
			</div>
		</div>
	</x-site.page-container>
</footer>
