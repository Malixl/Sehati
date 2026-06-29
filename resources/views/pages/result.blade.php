@extends('layouts.app')

@section('title', 'Hasil Skrining - Sehati')

@php
    // Get answers from URL
    $q1 = (int) request('q1', 2);
    $q2 = (int) request('q2', 1);
    $q3 = (int) request('q3', 1);
    $q4 = (int) request('q4', 0);
    $q5 = (int) request('q5', 0);
    $q6 = (int) request('q6', 0);
    $q7 = (int) request('q7', 0);
    $q8 = (int) request('q8', 0);
    $q9 = (int) request('q9', 0);
    
    // Calculate Points
    $pt_q1 = ($q1 === 0) ? 1 : 0;
    $pt_q2 = ($q2 === 0) ? 1 : 0;
    $pt_q3 = ($q3 === 0) ? 2 : 0;
    $pt_q4 = ($q4 === 1) ? 2 : 0;
    $pt_q5 = ($q5 === 1) ? 3 : 0;
    $pt_q6 = ($q6 === 1) ? 3 : 0;
    $pt_q7 = ($q7 === 1) ? 1 : 0;
    $pt_q8 = ($q8 === 1) ? 2 : 0;
    $pt_q9 = ($q9 === 1) ? 2 : 0;

    // Totals
    $diabetesScore = $pt_q1 + $pt_q2 + $pt_q4 + $pt_q5 + $pt_q7 + $pt_q8;
    $hypertensionScore = $pt_q1 + $pt_q2 + $pt_q3 + $pt_q6 + $pt_q7 + $pt_q9;

    // Categorize Diabetes
    if ($diabetesScore <= 2) {
        $dmStatus = 'Risiko Rendah';
        $dmColor = 'bg-green-50 border-green-200';
        $dmTextColor = 'text-green-700';
        $dmIconColor = 'bg-green-100 text-green-600';
        $dmMessage = 'Risiko Anda terhadap Diabetes Mellitus sangat rendah. Pertahankan gaya hidup sehat Anda, rutin berolahraga, dan tetap konsumsi makanan bergizi yang seimbang.';
    } elseif ($diabetesScore <= 5) {
        $dmStatus = 'Risiko Sedang';
        $dmColor = 'bg-yellow-50 border-yellow-200';
        $dmTextColor = 'text-yellow-700';
        $dmIconColor = 'bg-yellow-100 text-yellow-600';
        $dmMessage = 'Anda memiliki indikasi risiko sedang. Mulailah perbaiki pola makan Anda, kurangi konsumsi gula/manis, dan lebih rutin beraktivitas fisik minimal 30 menit sehari.';
    } else {
        $dmStatus = 'Risiko Tinggi';
        $dmColor = 'bg-red-50 border-red-200';
        $dmTextColor = 'text-red-700';
        $dmIconColor = 'bg-red-100 text-red-600';
        $dmMessage = 'Risiko Anda tergolong TINGGI. Kami sangat menyarankan Anda untuk SEGERA memeriksakan kadar gula darah Anda secara resmi ke fasilitas kesehatan terdekat (Puskesmas/Klinik/Dokter).';
    }

    // Categorize Hypertension
    if ($hypertensionScore <= 2) {
        $htStatus = 'Risiko Rendah';
        $htColor = 'bg-green-50 border-green-200';
        $htTextColor = 'text-green-700';
        $htIconColor = 'bg-green-100 text-green-600';
        $htMessage = 'Risiko Anda terhadap Hipertensi sangat rendah. Lanjutkan gaya hidup aktif, hindari merokok, dan kelola stres dengan baik setiap harinya.';
    } elseif ($hypertensionScore <= 5) {
        $htStatus = 'Risiko Sedang';
        $htColor = 'bg-yellow-50 border-yellow-200';
        $htTextColor = 'text-yellow-700';
        $htIconColor = 'bg-yellow-100 text-yellow-600';
        $htMessage = 'Ada indikasi risiko menengah. Kurangi konsumsi garam (sodium) harian Anda, hindari makanan cepat saji, dan pertimbangkan untuk mulai rutin mengecek tekanan darah.';
    } else {
        $htStatus = 'Risiko Tinggi';
        $htColor = 'bg-red-50 border-red-200';
        $htTextColor = 'text-red-700';
        $htIconColor = 'bg-red-100 text-red-600';
        $htMessage = 'Risiko hipertensi Anda TINGGI. Segera lakukan pengecekan tekanan darah ke fasilitas medis profesional. Hindari makanan asin, batasi kafein, dan segera konsultasikan keluhan Anda ke dokter.';
    }
@endphp

@section('content')
    
    {{-- Navbar Khusus Halaman Hasil --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Sehati</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- Header --}}
        <div class="text-center mb-10" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Laporan Hasil Skrining</h1>
            <p class="text-gray-500">Berikut adalah kalkulasi risiko kesehatan Anda berdasarkan jawaban kuesioner.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Kartu Hasil Diabetes --}}
            <div class="p-6 border rounded-xl shadow-sm {{ $dmColor }} transition-colors" data-aos="fade-right" data-aos-delay="100">
                <div class="flex items-center justify-between mb-4 border-b border-gray-200/50 pb-4">
                    <h3 class="text-xl font-bold text-gray-900">Diabetes Mellitus</h3>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $dmIconColor }}">
                        {{ $diabetesScore }}/10
                    </div>
                </div>
                
                <div class="mb-4">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $dmIconColor }}">
                        {{ $dmStatus }}
                    </span>
                </div>
                
                <p class="text-sm font-medium {{ $dmTextColor }} leading-relaxed">
                    {{ $dmMessage }}
                </p>
            </div>

            {{-- Kartu Hasil Hipertensi --}}
            <div class="p-6 border rounded-xl shadow-sm {{ $htColor }} transition-colors" data-aos="fade-left" data-aos-delay="200">
                <div class="flex items-center justify-between mb-4 border-b border-gray-200/50 pb-4">
                    <h3 class="text-xl font-bold text-gray-900">Hipertensi</h3>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $htIconColor }}">
                        {{ $hypertensionScore }}/10
                    </div>
                </div>
                
                <div class="mb-4">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $htIconColor }}">
                        {{ $htStatus }}
                    </span>
                </div>
                
                <p class="text-sm font-medium {{ $htTextColor }} leading-relaxed">
                    {{ $htMessage }}
                </p>
            </div>
        </div>

        {{-- Disclaimer Box --}}
        <div class="p-4 mb-8 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-200 shadow-sm" role="alert" data-aos="fade-up" data-aos-delay="300">
            <div class="flex items-center mb-2">
                <svg class="w-4 h-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="font-bold">Perhatian Penting</span>
            </div>
            <p>Hasil skrining ini <strong>Bukanlah Diagnosis Medis</strong>, melainkan perhitungan indikasi risiko berbasis data statistik kuesioner. Hanya dokter dan tenaga kesehatan profesional yang berhak menegakkan diagnosis penyakit melalui pemeriksaan klinis dan uji laboratorium secara langsung.</p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row justify-center gap-4" data-aos="zoom-in" data-aos-delay="400">
            <a href="/" class="w-full sm:w-auto text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-6 py-3 text-center inline-flex justify-center items-center transition-colors">
                Kembali ke Beranda
            </a>
            
            @if($dmStatus == 'Risiko Tinggi' || $htStatus == 'Risiko Tinggi')
            <a href="/map" class="w-full sm:w-auto text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-bold rounded-lg text-sm px-6 py-3 text-center inline-flex justify-center items-center transition-colors shadow-lg animate-bounce">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Cari Faskes Terdekat
            </a>
            @endif

            <button onclick="window.print()" class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-6 py-3 text-center inline-flex justify-center items-center transition-colors">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak / Simpan PDF
            </button>
        </div>

    </div>
@endsection
