{{-- Обёртка для связанных раскрывающихся блоков: одновременно открыт не больше одного (паттерн disclosure). --}}
<div {{ $attributes->merge(['class' => 'space-y-2']) }} x-data="{ disclosureOpen: null }">
	{{ $slot }}
</div>
