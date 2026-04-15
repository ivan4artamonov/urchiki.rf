@props([
	'id' => null,
	'label' => '',
	'rows' => 3,
])

<div>
	@if ($label !== '')
		<label for="{{ $id }}" class="block mb-2.5 text-sm font-medium text-heading">
			{{ $label }}
		</label>
	@endif

	<textarea
		id="{{ $id }}"
		rows="{{ $rows }}"
		{{ $attributes->merge(['class' => 'block w-full p-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded outline-none transition duration-200 focus:ring-blue-500 focus:border-blue-500']) }}
	></textarea>
</div>
