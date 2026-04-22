@props([
	'innerClass' => 'w-full flex-1 pt-3 md:pt-5',
])

<div {{ $attributes->merge(['class' => 'flex flex-1 flex-col']) }}>
	<div class="{{ $innerClass }}">
		{{ $slot }}
	</div>
</div>
