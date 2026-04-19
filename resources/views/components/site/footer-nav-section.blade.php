{{-- Колонка футера: заголовок и список ссылок (каждая через footer-nav-link). --}}
@props([
	'title',
	'items' => [],
])

<div>
	<h2 class="mb-3 font-site-heading text-sm font-extrabold text-urchiki-text">{{ $title }}</h2>
	<ul class="space-y-1">
		@foreach ($items as $item)
			<li>
				<x-site.footer-nav-link
					:href="$item['href']"
					:wire-navigate="$item['wireNavigate'] ?? true"
				>{{ $item['label'] }}</x-site.footer-nav-link>
			</li>
		@endforeach
	</ul>
</div>
