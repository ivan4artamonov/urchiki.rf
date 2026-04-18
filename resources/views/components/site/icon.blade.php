@props([
	'name',
])

<svg {{ $attributes->merge(['class' => 'h-5 w-5 shrink-0']) }} aria-hidden="true">
	<use href="#icon-{{ $name }}" />
</svg>
