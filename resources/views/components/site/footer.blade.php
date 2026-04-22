@use('App\Support\CopyrightYearRange')
@use('App\Models\Subject')

@php
	$home = route('site.home');
	$navActiveHome = request()->routeIs('site.home');
	$footerNavSubjectItems = Subject::query()
		->ordered()
		->get()
		->map(fn (Subject $subject): array => [
			'href' => route('site.subject', $subject),
			'label' => $subject->name,
		])
		->all();
	$footerNavGradeItems = $footerGrades
		->map(fn ($grade): array => [
			'href' => url('/'.$grade->slug),
			'label' => $grade->label,
		])
		->all();
	$footerNavServiceItems = [
		['href' => url('/subscribe'), 'label' => 'Тарифы'],
		['href' => route('site.faq'), 'label' => 'Вопросы и ответы'],
		['href' => url('/privacy'), 'label' => 'Политика конфиденциальности'],
	];
@endphp

<footer class="mt-auto border-t border-urchiki-border bg-urchiki-surface py-12 pb-8">
	<x-site.page-container>
		<div class="mb-9 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
			<div>
				@if ($navActiveHome)
					<span class="inline-flex items-center">
						<img src="{{ asset('i/logo.svg') }}" alt="{{ config('app.name') }}" width="180" height="60" class="block h-8 w-auto">
					</span>
				@else
					<a href="{{ $home }}" wire:navigate class="inline-flex items-center no-underline">
						<img src="{{ asset('i/logo.svg') }}" alt="{{ config('app.name') }}" width="180" height="60" class="block h-8 w-auto">
					</a>
				@endif
				<p class="mt-2 text-sm leading-relaxed text-urchiki-muted">
					Готовые рабочие листы для учителей,<br>репетиторов и родителей. 1–11 класс.
				</p>
			</div>
			<x-site.footer-nav-section title="Предметы" :items="$footerNavSubjectItems" />
			<x-site.footer-nav-section title="Классы" :items="$footerNavGradeItems" />
			<x-site.footer-nav-section title="Сервис" :items="$footerNavServiceItems" />
		</div>
		<div class="flex flex-col gap-4 border-t border-urchiki-border pt-5 text-xs text-urchiki-muted">
			<div class="flex flex-col flex-wrap justify-between gap-3 sm:flex-row sm:items-center">
				<span>© {{ CopyrightYearRange::format() }} {{ config('app.name') }}</span>
				<span>Все материалы соответствуют ФГОС</span>
			</div>
			<div class="leading-relaxed">
				<div>{{ config('site.legal.sole_proprietor_name') }}</div>
				<div>ИНН {{ config('site.legal.inn') }}</div>
				<div>ОГРНИП {{ config('site.legal.ogrnip') }}</div>
			</div>
		</div>
	</x-site.page-container>
</footer>
