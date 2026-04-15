@props([
	'scope' => 'col',
])

<th scope="{{ $scope }}" {{ $attributes->merge(['class' => 'px-4 py-3']) }}>
	{{ $slot }}
</th>
