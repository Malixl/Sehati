<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sehati')</title>

    {{-- Inter Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('meta')
</head>

<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex items-center justify-center p-6 @yield('body-class')">

    <div class="w-full max-w-md">
        {{-- Branding --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <span class="text-2xl font-bold text-primary-700">Sehati</span>
            </a>
        </div>

        {{-- Content Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>

</html>
