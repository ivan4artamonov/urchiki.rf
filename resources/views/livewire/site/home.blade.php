<x-site.section-block>
		<x-site.section-title align="center">Предметы</x-site.section-title>

		@if ($subjects->isEmpty())
			<x-site.message-card message="Список предметов пока пуст. Мы уже готовим наполнение." />
		@else
			<div class="mt-7 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
				@foreach ($subjects as $subject)
					<a href="{{ route('site.subject', $subject) }}" wire:navigate class="group block rounded-2xl border border-urchiki-border bg-urchiki-card p-2.5 transition-colors hover:border-urchiki-accent">
						<span class="flex items-start gap-2">
							@if ($subject->icon_url)
								<img
									src="{{ $subject->icon_url }}"
									alt="{{ $subject->name }}"
									class="h-11 w-11 rounded-xl object-cover"
									loading="lazy"
								>
							@else
								<span class="flex h-11 w-11 items-center justify-center rounded-xl border border-urchiki-border bg-urchiki-surface font-site-heading text-xs font-bold uppercase tracking-wide text-urchiki-text-sec">
									{{ mb_substr($subject->name, 0, 2) }}
								</span>
							@endif

							<span class="min-w-0">
								<span class="block font-site-heading text-lg font-extrabold leading-tight text-urchiki-text">
									{{ $subject->name }}
								</span>
								<span class="mt-1 block text-sm text-urchiki-muted">
									Тем: {{ $subject->topics_count }}
								</span>
							</span>
						</span>
					</a>
				@endforeach
			</div>
		@endif
</x-site.section-block>
