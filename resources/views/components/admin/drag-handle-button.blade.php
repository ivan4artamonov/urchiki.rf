@props([])

<button
	type="button"
	x-sort:handle
	{{ $attributes->merge(['class' => 'cursor-move text-body/70 hover:text-heading']) }}
>
	<i class="fa-solid fa-grip-vertical"></i>
</button>
