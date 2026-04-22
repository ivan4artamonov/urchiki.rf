@props([
	'id' => null,
	'label' => '',
])

<div>
	@if ($label !== '')
		<label for="{{ $id }}" class="mb-2.5 block text-sm font-medium text-heading">
			{{ $label }}
		</label>
	@endif

	<input
		id="{{ $id }}"
		type="file"
		{{ $attributes->merge(['class' => 'block w-full cursor-pointer text-sm text-body']) }}
	>
</div>
