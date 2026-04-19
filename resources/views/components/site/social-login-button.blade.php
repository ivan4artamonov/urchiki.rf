@props([
	'href' => null,
	'icon' => null,
])

@php
	$baseClass = 'flex flex-1 items-center justify-center gap-2 rounded-[10px] border border-urchiki-border bg-urchiki-card py-3 font-site-heading text-xs font-semibold text-urchiki-text-sec md:text-[13px]';
	$stateClasses = $href
		? 'cursor-pointer opacity-100'
		: 'cursor-not-allowed opacity-60';
@endphp

@if ($href)
	<a
		href="{{ $href }}"
		{{ $attributes->merge(['class' => "{$baseClass} {$stateClasses}"]) }}
	>
		@if ($icon)
			<x-site.icon :name="$icon" />
		@endif
		<span>{{ $slot }}</span>
	</a>
@else
	<button
		type="button"
		disabled
		{{ $attributes->merge(['class' => "{$baseClass} {$stateClasses}"]) }}
	>
		@if ($icon)
			<x-site.icon :name="$icon" />
		@endif
		<span>{{ $slot }}</span>
	</button>
@endif
