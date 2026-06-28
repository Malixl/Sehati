<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta-description', 'Sehati — Sistem Skrining Kesehatan untuk Diabetes dan Hipertensi')">

    <title>@yield('title', 'Sehati — Skrining Kesehatan')</title>

    {{-- Inter Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('meta')
</head>

<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col @yield('body-class')">

    @yield('content')

    @stack('scripts')
</body>

</html>
