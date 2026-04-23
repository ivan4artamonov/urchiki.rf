@props([
	'id' => null,
	'label' => '',
	'options' => [],
	'placeholder' => null,
	'valueKey' => 'value',
	'labelKey' => 'label',
	'disabled' => false,
])

@php
	$isDisabled = (bool) $disabled;
	$baseClasses = 'block w-full rounded border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 outline-none transition duration-200 focus:border-blue-500 focus:ring-blue-500';
	$disabledClasses = $isDisabled ? ' cursor-not-allowed bg-gray-200 text-gray-400' : '';
@endphp

<div>
	@if ($label !== '')
		<label for="{{ $id }}" class="mb-2.5 block text-sm font-medium text-heading">
			{{ $label }}
		</label>
	@endif

	<select
		id="{{ $id }}"
		@disabled($isDisabled)
		{{ $attributes->merge(['class' => $baseClasses . $disabledClasses]) }}
	>
		@if ($placeholder !== null)
			<option value="">{{ $placeholder }}</option>
		@endif

		@if (! $slot->isEmpty())
			{{ $slot }}
		@else
			@foreach ($options as $option)
				<option value="{{ $option[$valueKey] ?? '' }}">{{ $option[$labelKey] ?? '' }}</option>
			@endforeach
		@endif
	</select>
</div>
