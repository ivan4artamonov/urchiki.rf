@use('App\Enums\NotificationType')
@use('App\Support\Notification')

@php
	$notificationType = Notification::type() ?? NotificationType::Info;
	$notificationMessage = Notification::message();
	$notificationClasses = match ($notificationType) {
		NotificationType::Success => 'border-green-200 bg-green-50 text-green-800',
		NotificationType::Error => 'border-red-200 bg-red-50 text-red-800',
		NotificationType::Warning => 'border-yellow-200 bg-yellow-50 text-yellow-800',
		default => 'border-blue-200 bg-blue-50 text-blue-800',
	};
@endphp

@if(Notification::has())
	<div class="mb-6 rounded-base border px-4 py-3 text-sm font-medium {{ $notificationClasses }}">
		{{ $notificationMessage }}
	</div>
@endif
