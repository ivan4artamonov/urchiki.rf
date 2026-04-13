@props([
	'type' => 'button',
	'variant' => 'primary',
])

@php
	$baseClasses = 'rounded px-3 py-2 transition cursor-pointer';
	$variantClasses = match ($variant) {
		'secondary' => 'border',
		default => 'bg-blue-600 text-white shadow-lg shadow-blue-500/40 hover:bg-blue-700 hover:shadow-blue-600/40',
	};
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => "{$baseClasses} {$variantClasses}"]) }}>
	{{ $slot }}
</button>
