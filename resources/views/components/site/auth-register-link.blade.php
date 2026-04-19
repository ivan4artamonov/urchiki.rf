@php
	$href = route('site.login');
	$base = 'inline-flex items-center rounded-lg px-3.5 py-2 text-sm font-medium no-underline transition-colors md:py-1.5';
	$linkClass = $base.' cursor-pointer border border-urchiki-border bg-urchiki-card text-urchiki-text hover:border-urchiki-accent hover:bg-urchiki-accent-light hover:text-urchiki-accent';
	$currentClass = $base.' cursor-default select-none border border-urchiki-border bg-urchiki-card text-urchiki-accent pointer-events-none';
	$currentUrl = rtrim(strtolower((string) request()->url()), '/');
	$targetUrl = rtrim(strtolower((string) $href), '/');
	$isSelf = $currentUrl === $targetUrl;
@endphp

@if ($isSelf)
	<span
		aria-current="page"
		{{ $attributes->merge(['class' => $currentClass]) }}
	>Вход/Регистрация</span>
@else
	<a
		href="{{ $href }}"
		wire:navigate
		{{ $attributes->merge(['class' => $linkClass]) }}
	>Вход/Регистрация</a>
@endif
