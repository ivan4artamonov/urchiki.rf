@props([
	'tab',
	'cloak' => false,
])

<div x-bind="tabPanelAttrs(@js($tab), @js($cloak === true))" {{ $attributes }}>
	{{ $slot }}
</div>
