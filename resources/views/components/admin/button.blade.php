@props([
	'button' => false,
	'link' => false,
	'href' => '#',
	'type' => 'button',
	'variant' => 'primary',
	'size' => 'md',
])

@php
	$isLink = $link === true;
	$baseClasses = match ($isLink) {
		true => 'inline-flex items-center rounded transition cursor-pointer',
		default => 'rounded transition cursor-pointer',
	};
	$sizeClasses = match ($size) {
		'sm' => 'px-2 py-1 text-sm',
		default => 'px-3 py-2',
	};
	$variantClasses = match ($variant) {
		'secondary' => 'border border-slate-300 text-slate-700 hover:bg-slate-100 hover:border-slate-400',
		default => 'bg-blue-600 text-white shadow-lg shadow-blue-500/40 hover:bg-blue-700 hover:shadow-blue-600/40',
	};
@endphp

@if ($isLink)
	<a
		href="{{ $href }}"
		wire:navigate
		{{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}
	>
		{{ $slot }}
	</a>
@else
	<button {{ $attributes->merge(['type' => $type, 'class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}>
		{{ $slot }}
	</button>
@endif
