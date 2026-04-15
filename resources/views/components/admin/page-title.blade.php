@props([])

<h1 {{ $attributes->merge(['class' => 'mb-6 text-2xl font-semibold text-heading']) }}>
	{{ $slot }}
</h1>
