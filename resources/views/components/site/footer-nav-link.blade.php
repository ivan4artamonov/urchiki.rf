{{-- Одна ссылка в блоках навигации футера: на текущей странице — текст без ссылки. --}}
@props([
	'href' => '#',
	'active' => false,
	'wireNavigate' => true,
])

@php
	$inactiveClass = 'text-sm text-urchiki-muted transition-colors hover:text-urchiki-accent';
	$activeClass = 'text-sm text-urchiki-text-sec';
	$currentNorm = rtrim(strtolower((string) request()->url()), '/');
	$targetNorm = rtrim(strtolower((string) $href), '/');
	$isCurrentPage = $active || ($href !== '#' && $currentNorm === $targetNorm);
	$stateClass = $isCurrentPage ? $activeClass : $inactiveClass;
@endphp

@if ($isCurrentPage)
	<span {{ $attributes->merge(['class' => $stateClass]) }}>{{ $slot }}</span>
@else
	<a
		href="{{ $href }}"
		@if ($wireNavigate) wire:navigate @endif
		{{ $attributes->merge(['class' => $stateClass]) }}
	>{{ $slot }}</a>
@endif
