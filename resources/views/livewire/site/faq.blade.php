<div class="flex flex-1 flex-col">
	<x-site.breadcrumbs :items="[
		'Вопросы и ответы' => '',
	]" />

	<div class="mx-auto w-full max-w-[720px] flex-1 pt-5 md:pt-6">
		<x-site.page-title>Вопросы и ответы</x-site.page-title>
		<p class="mt-2 text-[15px] leading-snug text-urchiki-muted">
			Всё, что нужно знать о сервисе Урчики
		</p>

		@if ($faqItems->isEmpty())
			<p class="mt-8 text-center text-sm text-urchiki-muted">
				Пока нет опубликованных вопросов — загляните позже.
			</p>
		@else
			<x-site.disclosure-group class="mt-6">
				@foreach ($faqItems as $item)
					<x-site.disclosure :index="$loop->index" wire:key="site-faq-{{ $item->id }}">
						<x-slot:trigger>{{ $item->question }}</x-slot:trigger>
						{!! nl2br(e($item->answer)) !!}
					</x-site.disclosure>
				@endforeach
			</x-site.disclosure-group>
		@endif
	</div>
</div>
