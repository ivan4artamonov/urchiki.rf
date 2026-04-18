@props([
	'align' => 'left',
])

@php
	$alignClass = match ($align) {
		'center' => 'text-center',
		'right' => 'text-right',
		'left' => 'text-left',
		default => 'text-left',
	};
@endphp

<h1 class="{{ $alignClass }} font-site-heading text-2xl font-black tracking-tight text-urchiki-text md:text-[28px] md:leading-tight">
	{{ $slot }}
</h1>
