@props([
	'href' => '#',
	'active' => false,
])

@php
	$base = 'block rounded px-3 py-2 md:border-0 md:p-0';
	$inactiveClasses = 'text-heading hover:bg-neutral-tertiary md:hover:bg-transparent md:hover:text-fg-brand';
	$activeClasses = 'bg-brand text-white md:bg-transparent md:text-fg-brand';
	$linkClass = $base.' '.($active ? $activeClasses : $inactiveClasses);
@endphp

<li>
	<a
		href="{{ $href }}"
		@if($active) aria-current="page" @endif
		{{ $attributes->merge(['class' => $linkClass]) }}
	>{{ $slot }}</a>
</li>
