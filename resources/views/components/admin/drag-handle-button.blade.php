@props([])

<button
	type="button"
	x-sort:handle
	{{ $attributes->merge(['class' => 'cursor-move text-body/70 hover:text-heading']) }}
>
	<x-admin.icon name="grip-vertical" />
</button>
