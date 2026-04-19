{{-- По умолчанию wire:navigate; для внешних URL и т.п. — :wireNavigate="false". При active — без ссылки на текущую страницу. --}}
@props([
	'href' => '#',
	'active' => false,
	'wireNavigate' => true,
])

@php
	$base = 'rounded-lg px-3.5 py-2 text-sm font-medium transition-colors md:py-1.5';
	$inactiveClasses = 'text-urchiki-text-sec hover:bg-urchiki-surface hover:text-urchiki-text';
	$activeClasses = 'bg-urchiki-accent-light text-urchiki-accent';
	$currentNorm = rtrim(strtolower((string) request()->url()), '/');
	$targetNorm = rtrim(strtolower((string) $href), '/');
	$isCurrentPage = $active || ($href !== '#' && $currentNorm === $targetNorm);
	$linkClass = $base.' '.($isCurrentPage ? $activeClasses : $inactiveClasses);
@endphp

@if ($isCurrentPage)
	<span
		aria-current="page"
		{{ $attributes->merge(['class' => $linkClass]) }}
	>{{ $slot }}</span>
@else
	<a
		href="{{ $href }}"
		@if ($wireNavigate) wire:navigate @endif
		{{ $attributes->merge(['class' => $linkClass]) }}
	>{{ $slot }}</a>
@endif
