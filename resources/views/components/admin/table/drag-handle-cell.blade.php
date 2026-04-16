@props([])

<x-admin.table.cell {{ $attributes->merge(['class' => 'w-px whitespace-nowrap']) }}>
	<x-admin.drag-handle-button />
</x-admin.table.cell>
