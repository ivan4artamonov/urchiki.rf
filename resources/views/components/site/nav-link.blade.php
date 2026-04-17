{{-- По умолчанию wire:navigate; для внешних URL и т.п. — :wireNavigate="false" --}}
@props([
	'href' => '#',
	'active' => false,
	'wireNavigate' => true,
])

@php
	$base = 'rounded-lg px-3.5 py-2 text-sm font-medium transition-colors md:py-1.5';
	$inactiveClasses = 'text-urchiki-text-sec hover:bg-urchiki-surface hover:text-urchiki-text';
	$activeClasses = 'bg-urchiki-accent-light text-urchiki-accent';
	$linkClass = $base.' '.($active ? $activeClasses : $inactiveClasses);
@endphp

<a
	href="{{ $href }}"
	@if ($wireNavigate) wire:navigate @endif
	@if ($active) aria-current="page" @endif
	{{ $attributes->merge(['class' => $linkClass]) }}
>{{ $slot }}</a>
