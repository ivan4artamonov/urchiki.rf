{{-- Хвост цепочки: ключ — подпись (метка), значение — URL; пустая строка — неактивный пункт (текущая страница). Ссылка на главную зашита в компонент. --}}
@props([
	'items' => [],
])

<nav class="flex flex-wrap items-center gap-x-1.5 text-sm text-urchiki-muted">
	<a
		href="{{ route('site.home') }}"
		wire:navigate
		class="text-urchiki-muted underline decoration-urchiki-border decoration-1 underline-offset-2 hover:text-urchiki-accent"
	>{{ config('app.name') }}</a>
	@foreach ($items as $label => $href)
		<span class="select-none text-urchiki-muted/60">›</span>
		@if (is_string($href) && $href !== '')
			<a
				href="{{ $href }}"
				wire:navigate
				class="text-urchiki-muted underline decoration-urchiki-border decoration-1 underline-offset-2 hover:text-urchiki-accent"
			>{{ $label }}</a>
		@else
			<span class="font-medium text-urchiki-text-sec">{{ $label }}</span>
		@endif
	@endforeach
</nav>
