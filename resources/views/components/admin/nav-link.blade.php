@props([
	'href' => '#',
	'active' => false,
])

@php
	$base = 'block rounded px-3 py-2 text-sm font-medium text-body transition hover:text-heading md:border-0 md:p-0';
	$inactiveClasses = 'hover:bg-neutral-tertiary md:hover:bg-transparent';
	$activeClasses = 'text-heading md:text-heading';
	$linkClass = $base.' '.($active ? $activeClasses : $inactiveClasses);
@endphp

<li>
	<a
		href="{{ $href }}"
		@if($active) aria-current="page" @endif
		{{ $attributes->merge(['class' => $linkClass]) }}
	>{{ $slot }}</a>
</li>
