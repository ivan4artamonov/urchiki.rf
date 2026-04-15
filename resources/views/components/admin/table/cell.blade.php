@props([
	'colspan' => null,
])

<td {{ $attributes->merge(['class' => 'px-4 py-3']) }} @if ($colspan !== null) colspan="{{ $colspan }}" @endif>
	{{ $slot }}
</td>
