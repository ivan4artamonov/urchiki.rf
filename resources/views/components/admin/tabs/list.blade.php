@props([])

<div {{ $attributes->merge(['class' => 'mb-6 flex gap-2 border-b border-default pb-3']) }}>
	{{ $slot }}
</div>
