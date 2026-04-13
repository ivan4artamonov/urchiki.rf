@props([
	'type' => 'text',
	'id' => null,
	'label' => '',
])

<div>
	@if ($label !== '')
		<label for="{{ $id }}" class="block mb-2.5 text-sm font-medium text-heading">
			{{ $label }}
		</label>
	@endif

	<input
		type="{{ $type }}"
		id="{{ $id }}"
		{{ $attributes->merge(['class' => 'block w-full p-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded outline-none transition duration-200 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500']) }}
	>
</div>
