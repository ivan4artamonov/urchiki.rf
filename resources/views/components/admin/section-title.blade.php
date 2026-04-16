@props([
	'margin' => 'mb-4',
])

<h2 {{ $attributes->class([$margin, 'text-lg font-semibold text-heading']) }}>
	{{ $slot }}
</h2>
