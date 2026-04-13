@props([
	'href' => null,
	'action' => null,
])

@php
	$linkClass = 'inline-flex w-full items-center rounded p-2 hover:bg-neutral-tertiary-medium hover:text-heading';
	$buttonClass = $linkClass.' cursor-pointer border-0 bg-transparent text-left text-sm font-medium text-body';
@endphp

<li>
	@if(filled($action))
		<form method="POST" action="{{ $action }}" class="w-full">
			@csrf
			<button type="submit" {{ $attributes->merge(['class' => $buttonClass]) }}>
				{{ $slot }}
			</button>
		</form>
	@else
		<a href="{{ $href ?? '#' }}" {{ $attributes->merge(['class' => $linkClass]) }}>
			{{ $slot }}
		</a>
	@endif
</li>
