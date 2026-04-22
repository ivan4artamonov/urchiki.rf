{{-- Колонка футера: заголовок и список ссылок (каждая через footer-nav-link). --}}
{{-- $items — ассоциативный массив «подпись => URL» (label => href). --}}
@props([
	'title',
	'items' => [],
])

<div>
	<h2 class="mb-3 font-site-heading text-sm font-extrabold text-urchiki-text">{{ $title }}</h2>
	<ul class="space-y-1">
		@foreach ($items as $label => $href)
			<li>
				<x-site.footer-nav-link :href="$href">{{ $label }}</x-site.footer-nav-link>
			</li>
		@endforeach
	</ul>
</div>
