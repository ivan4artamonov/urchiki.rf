@props([
	'size' => 'sm',
])

@php
	$sizeClasses = match ($size) {
		'full' => 'max-w-full',
		default => 'max-w-sm',
	};
@endphp

<div {{ $attributes->merge(['class' => "w-full {$sizeClasses} bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs"]) }}>
	{{ $slot }}
</div>
