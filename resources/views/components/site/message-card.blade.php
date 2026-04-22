@props([
	'message',
])

<div {{ $attributes->merge(['class' => 'mt-8 rounded-2xl border border-urchiki-border bg-urchiki-card px-5 py-7 text-center text-sm text-urchiki-muted']) }}>
	{{ $message }}
</div>
