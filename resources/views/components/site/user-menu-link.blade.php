{{-- По умолчанию wire:navigate; для особых случаев — :wireNavigate="false". Кнопка: :button="true" (обычно внутри form method POST). Текущая страница — span вместо ссылки (сравнение href с request). --}}
@props([
	'href' => null,
	'wireNavigate' => true,
	'button' => false,
	'type' => 'submit',
])

@php
	$shared = 'block w-full px-3.5 py-2.5 text-sm font-medium text-urchiki-text transition-colors hover:bg-urchiki-surface';
	$controlClass = $button ? $shared.' cursor-pointer border-0 bg-transparent text-left' : $shared;
	$linkHref = $button ? null : ($href ?? url('/account'));
	$currentNorm = rtrim(strtolower((string) request()->url()), '/');
	$targetNorm = $linkHref !== null ? rtrim(strtolower((string) $linkHref), '/') : '';
	$isCurrentPage = ! $button && $linkHref !== null && $currentNorm === $targetNorm;
@endphp

@if ($button)
	<button
		type="{{ $type }}"
		{{ $attributes->merge(['class' => $controlClass]) }}
	>{{ $slot }}</button>
@elseif ($isCurrentPage)
	<span
		aria-current="page"
		{{ $attributes->merge(['class' => $controlClass.' cursor-default']) }}
	>{{ $slot }}</span>
@else
	<a
		href="{{ $linkHref }}"
		@if ($wireNavigate) wire:navigate @endif
		{{ $attributes->merge(['class' => $controlClass]) }}
	>{{ $slot }}</a>
@endif
