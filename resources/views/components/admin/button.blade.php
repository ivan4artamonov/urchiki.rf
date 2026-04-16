@props([
	'type' => 'button',
	'variant' => 'primary',
	'size' => 'md',
])

@php
	$baseClasses = 'rounded transition cursor-pointer';
	$sizeClasses = match ($size) {
		'sm' => 'px-2 py-1 text-sm',
		default => 'px-3 py-2',
	};
	$variantClasses = match ($variant) {
		'secondary' => 'border',
		default => 'bg-blue-600 text-white shadow-lg shadow-blue-500/40 hover:bg-blue-700 hover:shadow-blue-600/40',
	};
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}>
	{{ $slot }}
</button>
