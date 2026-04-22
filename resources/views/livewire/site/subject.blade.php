<x-site.section-block>
	<x-site.page-title>
		Рабочие листы по предмету {{ $subject->name }}
	</x-site.page-title>

	<nav class="mt-6 flex flex-wrap gap-2" aria-label="Классы по предмету">
		@foreach ($footerGrades as $grade)
			<x-site.grade-link-button href="{{ url('/' . $subject->slug . '/' . $grade->slug . '/') }}">
				{{ $grade->label }}
			</x-site.grade-link-button>
		@endforeach
	</nav>
</x-site.section-block>
