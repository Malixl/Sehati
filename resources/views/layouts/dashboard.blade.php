<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Dashboard' }} - SEHATI</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- CSS/JS (Tailwind & Flowbite via Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased" x-data="{ sidebarOpen: false }">

    {{-- Topbar --}}
    @include('partials.dashboard.topbar')

    <div class="flex pt-16 overflow-hidden bg-gray-50">
        
        {{-- Sidebar --}}
        @include('partials.dashboard.sidebar')

        {{-- Backdrop for mobile sidebar --}}
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-10 bg-gray-900/50 lg:hidden"
             @click="sidebarOpen = false"
             x-cloak></div>

        {{-- Main Content --}}
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 transition-all duration-300">
            <main>
                <div class="px-4 pt-6 pb-8 mx-auto max-w-7xl sm:px-6 lg:px-8" 
                     x-data="{ mounted: false }" 
                     x-init="setTimeout(() => mounted = true, 100)"
                     x-show="mounted"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('scripts')

    {{-- Global Scripts for SweetAlert2 --}}
    <script>
        // Setup Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Trigger flash messages
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        // Global Delete Confirmation
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
