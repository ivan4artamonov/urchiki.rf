@props([])

<div {{ $attributes->merge(['class' => 'w-full max-w-sm bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs']) }}>
	{{ $slot }}
</div>
