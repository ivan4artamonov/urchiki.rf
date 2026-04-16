@props([])

<x-admin.table.header {{ $attributes->merge(['class' => 'w-px']) }}>
	{{ $slot }}
</x-admin.table.header>
