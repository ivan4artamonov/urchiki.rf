<x-site.section-block>
	<x-site.breadcrumbs :items="$breadcrumbs" />

	<x-site.page-title>
		{{ $pageTitle }}
	</x-site.page-title>

	@if ($links->isNotEmpty())
		<nav class="mt-6 flex flex-wrap gap-2" aria-label="Дочерние разделы каталога">
			@foreach ($links as $item)
				<x-site.grade-link-button href="{{ $item['href'] }}">
					{{ $item['label'] }}
				</x-site.grade-link-button>
			@endforeach
		</nav>
	@endif
</x-site.section-block>
