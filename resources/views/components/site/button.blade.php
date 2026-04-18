@props([
	'type' => 'button',
	'variant' => 'primary',
	'disabled' => false,
])

@php
	$cursor = $disabled ? 'cursor-not-allowed' : 'cursor-pointer';

	$variantClasses = match ($variant) {
		'link-accent' => "{$cursor} border-0 bg-transparent p-0 font-site-heading font-medium text-urchiki-accent underline decoration-urchiki-accent decoration-1 underline-offset-2 hover:text-urchiki-accent-hover",
		'link-muted' => "{$cursor} border-0 bg-transparent p-0 font-site-heading font-medium text-urchiki-text-sec underline decoration-urchiki-border decoration-1 underline-offset-2 hover:text-urchiki-accent",
		default => "{$cursor} w-full rounded-full border-2 border-urchiki-accent bg-urchiki-accent py-3.5 text-center font-site-heading text-sm font-bold text-white shadow-sm transition-colors hover:border-urchiki-accent-hover hover:bg-urchiki-accent-hover disabled:cursor-not-allowed disabled:opacity-60",
	};
@endphp

<button
	type="{{ $type }}"
	@disabled($disabled)
	{{ $attributes->merge(['class' => $variantClasses]) }}
>{{ $slot }}</button>
