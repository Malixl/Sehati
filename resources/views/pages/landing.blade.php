@extends('layouts.app')

@section('title', 'Sehati — Skrining Kesehatan Dini Diabetes & Hipertensi')
@section('meta-description', 'Sehati adalah sistem skrining kesehatan publik untuk mendeteksi risiko Diabetes Mellitus dan Hipertensi sejak dini.')

@section('content')

    {{-- Navbar --}}
    @include('partials.navbar')

    <main class="flex-1 overflow-x-hidden">

        {{-- Hero Section --}}
        <section class="bg-blue-900 text-white py-16 md:py-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    {{-- Text Content --}}
                    <div data-aos="fade-right" class="text-center lg:text-left">
                        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl xl:text-6xl">
                            Kenali Risiko Anda <br class="hidden lg:block">Sebelum Gejala Muncul.
                        </h1>
                        <p class="mb-8 text-lg font-normal text-blue-200 lg:text-xl">
                            Sistem Edukasi dan Skrining Hipertensi & Diabetes Terintegrasi untuk mendeteksi risiko sejak dini secara cepat, aman, dan gratis.
                        </p>
                        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center lg:justify-start sm:space-y-0 sm:space-x-4">
                            <a href="/consent" class="inline-flex justify-center items-center py-3 px-6 text-base font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700 hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-green-500/30 focus:ring-4 focus:ring-green-300">
                                Mulai Skrining
                            </a>
                            <a href="#tentang" class="inline-flex justify-center items-center py-3 px-6 text-base font-medium text-center text-white rounded-lg border border-white hover:bg-white/10 hover:scale-105 transition-all duration-300 focus:ring-4 focus:ring-blue-300">
                                Pelajari Lebih Lanjut
                            </a>  
                        </div>
                    </div>

                    {{-- Lottie Animation --}}
                    <div data-aos="fade-left" data-aos-delay="200" class="flex justify-center lg:justify-end">
                        <lottie-player 
                            src="{{ asset('img/Yoga Se Hi hoga.json') }}" 
                            background="transparent" 
                            speed="1" 
                            style="width: 100%; max-width: 500px; height: auto;" 
                            loop 
                            autoplay>
                        </lottie-player>
                    </div>
                </div>
            </div>
        </section>

        {{-- Mengapa Skrining Penting --}}
        <section id="tentang" class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div data-aos="fade-up">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900">Mengapa Skrining Dini Sangat Penting?</h2>
                    <p class="mb-12 font-light text-gray-500 sm:text-xl sm:px-16 lg:px-48">Penyakit tidak menular seperti Diabetes dan Hipertensi seringkali dijuluki 'silent killer' karena tidak menunjukkan gejala pada tahap awal.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div data-aos="fade-up" data-aos-delay="100" class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold tracking-tight text-gray-900">Deteksi Dini</h3>
                        <p class="font-normal text-gray-500 text-sm">Mengetahui risiko lebih awal memberi Anda peluang terbaik untuk pencegahan yang efektif.</p>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="200" class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold tracking-tight text-gray-900">Gaya Hidup Sehat</h3>
                        <p class="font-normal text-gray-500 text-sm">Panduan personal untuk membantu Anda membuat keputusan gaya hidup yang lebih baik setiap harinya.</p>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="300" class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-yellow-50 text-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold tracking-tight text-gray-900">Kesadaran Risiko</h3>
                        <p class="font-normal text-gray-500 text-sm">Meningkatkan kesadaran akan kondisi kesehatan Anda dan potensi risiko di masa depan.</p>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="400" class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold tracking-tight text-gray-900">Konsultasi Medis</h3>
                        <p class="font-normal text-gray-500 text-sm">Hasil skrining dapat digunakan sebagai data awal saat berkonsultasi dengan dokter Anda.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Mengenal Penyakit --}}
        <section class="bg-gray-50 py-20 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div data-aos="fade-up" class="text-center">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900">Mengenal Penyakit Terkait</h2>
                    <p class="mb-12 font-light text-gray-500 sm:text-xl sm:px-16 lg:px-48">Informasi singkat mengenai dua penyakit tidak menular yang paling umum.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                    <div data-aos="fade-right" data-aos-delay="100" class="p-8 bg-white border-t-4 border-t-blue-500 border-l border-r border-b border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 rounded-b-xl rounded-t-sm">
                        <div class="flex items-center gap-4 mb-4 border-b border-gray-100 pb-4">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold tracking-tight text-gray-900">Diabetes Mellitus</h3>
                        </div>
                        <p class="mb-6 font-normal text-gray-600 leading-relaxed">Penyakit kronis yang ditandai dengan tingginya kadar gula darah. Glukosa menumpuk di dalam darah karena tubuh tidak dapat memproduksi atau menggunakan insulin dengan baik.</p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <strong class="block mb-3 font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Gejala Umum:
                            </strong>
                            <ul class="list-none text-sm text-gray-600 space-y-2">
                                <li class="flex items-start gap-2"><svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Sering merasa haus berlebih</li>
                                <li class="flex items-start gap-2"><svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Sering buang air kecil (malam hari)</li>
                                <li class="flex items-start gap-2"><svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Rasa lapar yang ekstrem</li>
                                <li class="flex items-start gap-2"><svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Kelelahan yang tidak wajar</li>
                            </ul>
                        </div>
                    </div>

                    <div data-aos="fade-left" data-aos-delay="200" class="p-8 bg-white border-t-4 border-t-red-500 border-l border-r border-b border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 rounded-b-xl rounded-t-sm">
                        <div class="flex items-center gap-4 mb-4 border-b border-gray-100 pb-4">
                            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold tracking-tight text-gray-900">Hipertensi</h3>
                        </div>
                        <p class="mb-6 font-normal text-gray-600 leading-relaxed">Sering disebut tekanan darah tinggi. Kondisi di mana tekanan darah terhadap dinding arteri secara konsisten terlalu tinggi, membebani kerja jantung dan pembuluh darah.</p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <strong class="block mb-3 font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Gejala Umum:
                            </strong>
                            <ul class="list-none text-sm text-gray-600 space-y-2">
                                <li class="flex items-start gap-2"><svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Sakit kepala parah / berat di tengkuk</li>
                                <li class="flex items-start gap-2"><svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Kelelahan atau kebingungan</li>
                                <li class="flex items-start gap-2"><svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Masalah penglihatan (kabur mendadak)</li>
                                <li class="flex items-start gap-2"><svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Nyeri dada atau detak tidak teratur</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Bagaimana Skrining Bekerja --}}
        <section class="bg-white py-20 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div data-aos="zoom-in">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900">Bagaimana Skrining Ini Bekerja?</h2>
                    <p class="mb-16 font-light text-gray-500 sm:text-xl sm:px-16 lg:px-48">Hanya membutuhkan waktu 5 menit untuk mengetahui status awal Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
                    {{-- Connecting Line --}}
                    <div class="hidden md:block absolute top-8 left-[12.5%] right-[12.5%] h-1 bg-gradient-to-r from-blue-100 via-blue-300 to-blue-100 z-0 rounded-full"></div>

                    <div data-aos="fade-up" data-aos-delay="100" class="relative z-10 flex flex-col items-center group">
                        <div class="w-16 h-16 rounded-full bg-white text-blue-700 flex items-center justify-center font-bold text-2xl border-4 border-blue-100 mb-6 shadow-md group-hover:bg-blue-700 group-hover:text-white group-hover:border-blue-200 group-hover:scale-110 transition-all duration-300">1</div>
                        <h4 class="font-bold text-gray-900 mb-2 text-lg">Buka Website</h4>
                        <p class="text-sm text-gray-500">Akses sistem dari perangkat apapun tanpa perlu menginstal aplikasi.</p>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="200" class="relative z-10 flex flex-col items-center group">
                        <div class="w-16 h-16 rounded-full bg-white text-blue-700 flex items-center justify-center font-bold text-2xl border-4 border-blue-100 mb-6 shadow-md group-hover:bg-blue-700 group-hover:text-white group-hover:border-blue-200 group-hover:scale-110 transition-all duration-300">2</div>
                        <h4 class="font-bold text-gray-900 mb-2 text-lg">Jawab Kuesioner</h4>
                        <p class="text-sm text-gray-500">Isi data gaya hidup dan riwayat kesehatan dengan jujur dan rahasia.</p>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="300" class="relative z-10 flex flex-col items-center group">
                        <div class="w-16 h-16 rounded-full bg-white text-blue-700 flex items-center justify-center font-bold text-2xl border-4 border-blue-100 mb-6 shadow-md group-hover:bg-blue-700 group-hover:text-white group-hover:border-blue-200 group-hover:scale-110 transition-all duration-300">3</div>
                        <h4 class="font-bold text-gray-900 mb-2 text-lg">Lihat Hasil</h4>
                        <p class="text-sm text-gray-500">Sistem akan langsung mengalkulasi tingkat risiko Anda secara otomatis.</p>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="400" class="relative z-10 flex flex-col items-center group">
                        <div class="w-16 h-16 rounded-full bg-white text-blue-700 flex items-center justify-center font-bold text-2xl border-4 border-blue-100 mb-6 shadow-md group-hover:bg-blue-700 group-hover:text-white group-hover:border-blue-200 group-hover:scale-110 transition-all duration-300">4</div>
                        <h4 class="font-bold text-gray-900 mb-2 text-lg">Cetak & Konsultasi</h4>
                        <p class="text-sm text-gray-500">Simpan PDF hasil skrining Anda dan bawa saat periksa ke dokter.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Keunggulan --}}
        <section class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 data-aos="fade-up" class="mb-10 text-3xl font-extrabold tracking-tight text-gray-900">Keunggulan Skrining Sehati</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div data-aos="zoom-in" data-aos-delay="100" class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Tanpa Registrasi
                        </h4>
                        <p class="text-sm text-gray-500">Langsung mulai tanpa perlu repot membuat akun.</p>
                    </div>
                    <div data-aos="zoom-in" data-aos-delay="200" class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            100% Gratis
                        </h4>
                        <p class="text-sm text-gray-500">Tanpa biaya apapun, bebas diakses kapan saja.</p>
                    </div>
                    <div data-aos="zoom-in" data-aos-delay="300" class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Cepat
                        </h4>
                        <p class="text-sm text-gray-500">Kuesioner dirancang agar selesai hanya dalam 5 menit.</p>
                    </div>
                    <div data-aos="zoom-in" data-aos-delay="400" class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Rahasia
                        </h4>
                        <p class="text-sm text-gray-500">Privasi dan jawaban kuesioner Anda sepenuhnya terjaga.</p>
                    </div>
                    <div data-aos="zoom-in" data-aos-delay="500" class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Berbasis Riset
                        </h4>
                        <p class="text-sm text-gray-500">Algoritma perhitungan skor divalidasi oleh akademisi.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- FAQ Section --}}
        <section id="faq" class="bg-white py-20">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div data-aos="fade-up" class="text-center mb-12">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900">Pertanyaan yang Sering Diajukan</h2>
                </div>

                <div data-aos="fade-up" data-aos-delay="200" class="shadow-sm rounded-xl overflow-hidden border border-gray-100">
                    @include('partials.faq', [
                        'items' => [
                            ['q' => 'Apakah skrining ini bisa dijadikan diagnosis medis pasti?', 'a' => 'Tidak. Skrining ini hanya bertujuan untuk mendeteksi tingkat risiko awal. Hasil skrining tidak menggantikan diagnosis resmi dari dokter. Anda disarankan mengunjungi fasilitas kesehatan terdekat jika mendapatkan hasil risiko tinggi.'],
                            ['q' => 'Berapa lama waktu yang dibutuhkan?', 'a' => 'Proses skrining membutuhkan waktu sekitar 5-10 menit untuk mengisi seluruh kuesioner.'],
                            ['q' => 'Apakah data saya aman?', 'a' => 'Ya. Data Anda dijamin kerahasiaannya dan tidak dipublikasikan. Data hanya dianalisis secara kolektif untuk keperluan penelitian akademik tanpa mengaitkannya dengan identitas pribadi.'],
                            ['q' => 'Siapa yang sebaiknya mengikuti skrining ini?', 'a' => 'Setiap individu dewasa (di atas 18 tahun), khususnya mereka yang memiliki riwayat keluarga dengan penyakit diabetes atau hipertensi, jarang berolahraga, atau perokok aktif.'],
                        ]
                    ])
                </div>
            </div>
        </section>

        {{-- CTA Section --}}
        <section class="bg-blue-900 text-white py-20 relative overflow-hidden">
            {{-- Background decorative shapes --}}
            <div class="absolute top-0 left-0 w-64 h-64 bg-blue-800 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-64 h-64 bg-indigo-800 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000"></div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <div data-aos="zoom-in">
                    <h2 class="mb-6 text-4xl font-extrabold tracking-tight text-white">Siap Mengetahui Status Kesehatan Anda?</h2>
                    <p class="mb-10 font-light text-blue-100 text-lg sm:text-xl leading-relaxed">Mari ambil langkah pertama untuk masa depan yang lebih sehat. Deteksi lebih awal, cegah lebih cepat.</p>
                    <a href="/consent" class="inline-flex justify-center items-center py-4 px-8 text-lg font-bold text-center text-blue-900 rounded-full bg-white hover:bg-gray-50 hover:scale-110 transition-all duration-300 shadow-xl hover:shadow-white/20 focus:ring-4 focus:ring-blue-300">
                        Mulai Skrining Sekarang
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </section>

    </main>

    {{-- Footer --}}
    @include('partials.footer')

@endsection
