@props([
	'disabled' => true,
	'icon' => null,
])

@php
	$stateClasses = $disabled
		? 'cursor-not-allowed opacity-60'
		: 'cursor-pointer opacity-100';
@endphp

<button
	type="button"
	@disabled($disabled)
	{{ $attributes->merge(['class' => "flex flex-1 items-center justify-center gap-2 rounded-[10px] border border-urchiki-border bg-urchiki-card py-3 font-site-heading text-xs font-semibold text-urchiki-text-sec md:text-[13px] {$stateClasses}"]) }}
>
	@if ($icon)
		<x-site.icon :name="$icon" />
	@endif
	<span>{{ $slot }}</span>
</button>
