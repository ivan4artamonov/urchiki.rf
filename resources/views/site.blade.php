<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ $title ?? config('app.name', 'Laravel') }}</title>
		<link rel="icon" type="image/svg+xml" href="{{ asset('i/favicon.svg') }}">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@400;500;600;700&family=Merriweather:wght@700;900&display=swap" rel="stylesheet">
		@vite(['resources/css/site.css', 'resources/js/site.js'])
		@livewireStyles
	</head>
	<body class="flex min-h-screen flex-col bg-urchiki-bg font-sans text-urchiki-text antialiased">
		<x-site.header />

		<main id="site-content" class="flex flex-1 flex-col">
			<x-site.page-container class="flex flex-1 flex-col py-8">
				{{ $slot }}
			</x-site.page-container>
		</main>

		<x-site.footer />

		@livewireScripts
	</body>
</html>
