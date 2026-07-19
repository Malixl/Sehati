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
            deleteForms.forEach((form, index) => {
                if (!form.id) {
                    form.id = 'delete-form-' + index;
                }
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: form.id }));
                });
            });
        });
    </script>

    <!-- Global Delete Modal (Alpine + Flowbite) -->
    <div x-data="{ show: false, formId: '' }" 
         @open-delete-modal.window="show = true; formId = $event.detail" 
         @close-modal.window="show = false" 
         x-show="show" 
         class="fixed inset-0 z-[100] flex items-center justify-center overflow-auto bg-black bg-opacity-50" 
         style="display: none;"
         x-transition.opacity>
        <div @click.away="show = false" class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-5 text-center">
            <button @click="show = false" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Tutup</span>
            </button>
            <svg class="mx-auto mb-4 text-red-500 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <h3 class="mb-5 text-lg font-normal text-gray-700">Apakah Anda yakin? Data yang dihapus tidak dapat dikembalikan!</h3>
            <div class="flex justify-center gap-3">
                <button @click="document.getElementById(formId).submit()" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Ya, hapus!
                </button>
                <button @click="show = false" type="button" class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5 hover:text-gray-900">
                    Batal
                </button>
            </div>
        </div>
    </div>
</body>
</html>
