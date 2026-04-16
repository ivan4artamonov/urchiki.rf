@props([
	'margin' => 'mb-6',
])

<h1 {{ $attributes->class([$margin, 'text-2xl font-semibold text-heading']) }}>
	{{ $slot }}
</h1>
