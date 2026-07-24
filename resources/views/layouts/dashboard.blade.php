<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Dashboard' }} - SEHATI</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/Blue.svg') }}">

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
         class="fixed inset-0 z-100 flex items-center justify-center overflow-auto bg-gray-900/50 backdrop-blur-sm" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div @click.away="show = false" 
             class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 p-6 text-center"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <button @click="show = false" type="button" class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Tutup</span>
            </button>
            
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4 animate-bounce">
                <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <h3 class="mb-5 text-lg font-bold text-gray-900">Apakah Anda yakin?</h3>
            <p class="mb-6 text-sm text-gray-500">Data yang dihapus tidak dapat dikembalikan!</p>
            
            <div class="flex justify-center gap-4">
                <button @click="document.getElementById(formId).submit()" type="button" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center transition-colors shadow-lg shadow-red-500/50">
                    Ya, hapus!
                </button>
                <button @click="show = false" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</body>
</html>
