{{-- По умолчанию wire:navigate; для особых случаев — :wireNavigate="false". Кнопка: :button="true" (обычно внутри form method POST). --}}
@props([
	'href' => '#',
	'wireNavigate' => true,
	'button' => false,
	'type' => 'submit',
])

@php
	$shared = 'block w-full px-3.5 py-2.5 text-sm font-medium text-urchiki-text transition-colors hover:bg-urchiki-surface';
	$controlClass = $button ? $shared.' cursor-pointer border-0 bg-transparent text-left' : $shared;
@endphp

@if ($button)
	<button
		type="{{ $type }}"
		{{ $attributes->merge(['class' => $controlClass]) }}
	>{{ $slot }}</button>
@else
	<a
		href="{{ $href }}"
		@if ($wireNavigate) wire:navigate @endif
		{{ $attributes->merge(['class' => $controlClass]) }}
	>{{ $slot }}</a>
@endif
