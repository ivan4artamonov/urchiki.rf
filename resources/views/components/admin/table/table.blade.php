@props([])

<div class="overflow-x-auto rounded-base border border-default bg-neutral-primary-soft shadow-xs">
	<table {{ $attributes->merge(['class' => 'w-full text-left text-sm text-body']) }}>
		{{ $slot }}
	</table>
</div>
