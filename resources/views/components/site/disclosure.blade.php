{{-- Раскрывающийся блок: слот trigger — заголовок кнопки, основной слот — содержимое панели. Внутри disclosure-group — режим аккордеона по index; standalone — одиночный спойлер. --}}
@props([
	'index' => 0,
	'standalone' => false,
])

@php
	$cardClass = 'overflow-hidden rounded-[14px] border border-urchiki-border bg-urchiki-card';
	$buttonClass = 'flex w-full cursor-pointer items-center justify-between gap-3 px-5 py-4 text-left font-site-heading text-[15px] font-bold text-urchiki-text transition-colors hover:bg-urchiki-surface/60';
	$panelClass = 'border-t border-urchiki-border px-5 pb-4 pt-4 text-sm leading-relaxed text-urchiki-text-sec';
	$chevronClass = 'shrink-0 text-lg leading-none text-urchiki-muted transition-transform duration-200';
@endphp

@if ($standalone)
	<div x-data="{ disclosurePanelOpen: false }" {{ $attributes->merge(['class' => $cardClass]) }}>
		<button
			type="button"
			class="{{ $buttonClass }}"
			x-on:click="disclosurePanelOpen = ! disclosurePanelOpen"
			x-bind:aria-expanded="disclosurePanelOpen ? 'true' : 'false'"
		>
			<span class="min-w-0">{{ $trigger }}</span>
			<span class="{{ $chevronClass }}" x-bind:class="disclosurePanelOpen ? 'rotate-180' : ''">▾</span>
		</button>
		<div class="{{ $panelClass }}" x-show="disclosurePanelOpen" style="display: none;">
			{{ $slot }}
		</div>
	</div>
@else
	<div {{ $attributes->merge(['class' => $cardClass]) }}>
		<button
			type="button"
			class="{{ $buttonClass }}"
			x-on:click="disclosureOpen = disclosureOpen === {{ $index }} ? null : {{ $index }}"
			x-bind:aria-expanded="disclosureOpen === {{ $index }} ? 'true' : 'false'"
		>
			<span class="min-w-0">{{ $trigger }}</span>
			<span class="{{ $chevronClass }}" x-bind:class="disclosureOpen === {{ $index }} ? 'rotate-180' : ''">▾</span>
		</button>
		<div class="{{ $panelClass }}" x-show="disclosureOpen === {{ $index }}" style="display: none;">
			{{ $slot }}
		</div>
	</div>
@endif
