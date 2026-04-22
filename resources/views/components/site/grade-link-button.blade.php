@props([
	'href' => '#',
	'wireNavigate' => true,
])

<a
	href="{{ $href }}"
	@if ($wireNavigate) wire:navigate @endif
	{{ $attributes->merge([
		'class' => 'inline-flex items-center rounded-lg border border-urchiki-border bg-urchiki-surface px-3 py-1.5 text-sm font-medium text-urchiki-text-sec transition-colors hover:border-urchiki-accent hover:bg-urchiki-accent-light hover:text-urchiki-accent',
	]) }}
>{{ $slot }}</a>
