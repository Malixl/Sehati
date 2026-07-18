@extends('layouts.app')

@section('title', 'Riwayat Skrining - Sehati')

@section('content')
    @include('partials.navbar')

    <main class="flex-1 overflow-x-hidden bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- <div class="mb-8" data-aos="fade-right">
                    <a href="/"
                        class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div> -->

            <div class="text-center mb-10" data-aos="fade-down">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">Riwayat Skrining</h1>
                <p class="mt-3 text-lg text-gray-500">
                    Daftar hasil skrining yang pernah dilakukan melalui perangkat ini.
                </p>
            </div>

            @if($screenings->isEmpty())
                <div class="text-center bg-white rounded-2xl shadow-sm border border-gray-100 p-12" data-aos="zoom-in">
                    <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada riwayat skrining</h3>
                    <p class="text-gray-500 mb-6">Perangkat ini belum pernah digunakan untuk melakukan skrining kesehatan.</p>
                    <a href="{{ route('screening.respondent') }}"
                        class="inline-flex justify-center items-center py-2.5 px-6 text-sm font-semibold text-white rounded-lg bg-green-600 hover:bg-green-700 transition-colors shadow-sm">
                        Mulai Skrining Sekarang
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @foreach($screenings as $screening)
                        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                            data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-sm font-medium text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($screening->screened_at)->translatedFormat('d F Y') }}
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                        {{ $screening->screeningPeriod ? $screening->screeningPeriod->name : 'Skrining Mandiri' }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold text-gray-900 mb-1 truncate"
                                    title="{{ $screening->respondent->fullname }}">
                                    {{ $screening->respondent->fullname }}
                                </h3>
                                <div class="text-sm text-gray-500 mb-4">
                                    Usia: {{ \Carbon\Carbon::parse($screening->respondent->birthdate)->age }} tahun
                                </div>

                                <div class="space-y-3 mb-6">
                                    <div>
                                        <div class="flex justify-between text-xs mb-1">
                                            <span class="font-medium text-gray-700">Risiko Diabetes</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <x-dashboard.severity-badge :label="$screening->dm_status" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-xs mb-1">
                                            <span class="font-medium text-gray-700">Risiko Hipertensi</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <x-dashboard.severity-badge :label="$screening->ht_status" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                                <a href="{{ route('screening.result', $screening->id) }}?source=history"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-blue-200 shadow-sm text-sm font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Lihat Detail Hasil
                                    <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
@endsection