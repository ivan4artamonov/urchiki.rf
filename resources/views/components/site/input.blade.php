@props([
	'type' => 'text',
	'id' => null,
	'label' => '',
	'errorField' => null,
])

<div>
	@if ($label !== '')
		<label for="{{ $id }}" class="mb-1.5 block font-site-heading text-sm font-bold text-urchiki-text">
			{{ $label }}
		</label>
	@endif

	<input
		type="{{ $type }}"
		@if (filled($id)) id="{{ $id }}" @endif
		{{ $attributes->merge(['class' => 'w-full rounded-[10px] border border-urchiki-border bg-white px-4 py-3 text-[15px] text-urchiki-text placeholder:text-urchiki-muted/45 placeholder:font-normal outline-none ring-urchiki-accent focus:border-urchiki-accent focus:ring-1']) }}
	/>

	@if (filled($errorField))
		<x-site.validation-error :field="$errorField" />
	@endif
</div>
