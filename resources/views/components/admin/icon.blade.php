@props([
	'name',
	'style' => 'solid',
])

@php
	$styleClass = match ($style) {
		'regular' => 'fa-regular',
		'brands' => 'fa-brands',
		default => 'fa-solid',
	};
@endphp

<i {{ $attributes->class([$styleClass, "fa-{$name}"]) }}></i>
