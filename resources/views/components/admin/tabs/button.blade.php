@props([
	'tab',
])

<button x-bind="tabButtonAttrs(@js($tab))" {{ $attributes }}>
	{{ $slot }}
</button>
