@props([])

<tbody {{ $attributes->merge(['class' => 'divide-y divide-default']) }}>
	{{ $slot }}
</tbody>
