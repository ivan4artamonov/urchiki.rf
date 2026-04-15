@props([
	'href' => '#',
	'variant' => 'primary',
])

@php
	$baseClasses = 'inline-flex items-center rounded px-3 py-2 transition cursor-pointer';
	$variantClasses = match ($variant) {
		'secondary' => 'border',
		default => 'bg-blue-600 text-white shadow-lg shadow-blue-500/40 hover:bg-blue-700 hover:shadow-blue-600/40',
	};
@endphp

<a
	href="{{ $href }}"
	wire:navigate
	{{ $attributes->merge(['class' => "{$baseClasses} {$variantClasses}"]) }}
>
	{{ $slot }}
</a>
