@props([
	'type' => 'text',
])

<input
	type="{{ $type }}"
	{{ $attributes->merge(['class' => 'w-full border rounded px-3 py-2 outline-none transition duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500']) }}
>
