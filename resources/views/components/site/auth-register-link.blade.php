@props([
	'href' => null,
	'wireNavigate' => false,
])

@php
	$href = $href ?? url('/login');
	$linkClass = 'inline-flex items-center rounded-lg border border-urchiki-border bg-urchiki-card px-3.5 py-2 text-sm font-medium text-urchiki-text no-underline transition-colors md:py-1.5 hover:border-urchiki-accent hover:bg-urchiki-accent-light hover:text-urchiki-accent';
@endphp

<a
	href="{{ $href }}"
	@if ($wireNavigate) wire:navigate @endif
	{{ $attributes->merge(['class' => $linkClass]) }}
>Вход/Регистрация</a>
