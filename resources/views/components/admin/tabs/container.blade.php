@props([
	'initialTab' => 'default',
])

<div x-data="tabs(@js($initialTab))" {{ $attributes }}>
	{{ $slot }}
</div>
