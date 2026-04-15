@props([])

<thead {{ $attributes->merge(['class' => 'border-b border-default bg-neutral-secondary-soft text-xs font-semibold uppercase tracking-wide text-heading']) }}>
	{{ $slot }}
</thead>
