@extends('layouts.app')

@section('title', 'Skrining Ditutup — Sehati')

@section('content')
    <main class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4 py-12">
        <div class="w-full max-w-md mx-auto text-center">

            <!-- {{-- Logo Kecil --}}
                <div class="flex justify-center mb-8">
                    <a href="/" class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-800 tracking-wide">SEHATI</span>
                    </a>
                </div> -->

            {{-- Animasi / Ikon Status (Lebih Kecil) --}}
            <div class="flex justify-center mb-6">
                <div
                    class="w-24 h-24 sm:w-32 sm:h-32 bg-white rounded-full flex items-center justify-center shadow-md border border-gray-100 p-4">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Periode Skrining Belum Dibuka</h1>

            <p class="text-base sm:text-lg text-gray-600 mb-8 leading-relaxed">
                Mohon maaf, Puskesmas saat ini sedang tidak membuka periode skrining kesehatan mandiri.
                <br><br>
                Silakan pantau kembali halaman ini secara berkala.
            </p>

            <div class="flex justify-center">
                <a href="/"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-sm sm:text-base font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </main>
@endsection