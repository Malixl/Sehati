@extends('layouts.app')

@section('title', 'Sehati — Skrining Kesehatan Dini Diabetes & Hipertensi')
@section('meta-description', 'Sehati adalah sistem skrining kesehatan publik untuk mendeteksi risiko Diabetes Mellitus dan Hipertensi sejak dini.')

@section('content')

    {{-- Navbar --}}
    @include('partials.navbar')

    <main class="flex-1 overflow-x-hidden">

        {{-- Hero Section --}}
        <section class="bg-blue-900 text-white py-16 md:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">Kenali Risiko Anda Sebelum Gejala Muncul.</h1>
                <p class="mb-8 text-lg font-normal text-blue-200 lg:text-xl sm:px-16 lg:px-48">Sistem Edukasi dan Skrining Hipertensi & Diabetes Terintegrasi untuk mendeteksi risiko sejak dini secara cepat, aman, dan gratis.</p>
                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                    <a href="/consent" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300">
                        Mulai Skrining
                    </a>
                    <a href="#tentang" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg border border-white hover:bg-white/10 focus:ring-4 focus:ring-blue-300">
                        Pelajari Lebih Lanjut
                    </a>  
                </div>
            </div>
        </section>

        {{-- Mengapa Skrining Penting --}}
        <section id="tentang" class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900">Mengapa Skrining Dini Sangat Penting?</h2>
                <p class="mb-8 font-light text-gray-500 sm:text-xl sm:px-16 lg:px-48">Penyakit tidak menular seperti Diabetes dan Hipertensi seringkali dijuluki 'silent killer' karena tidak menunjukkan gejala pada tahap awal.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold tracking-tight text-gray-900">Deteksi Dini</h3>
                        <p class="font-normal text-gray-500 text-sm">Mengetahui risiko lebih awal memberi Anda peluang terbaik untuk pencegahan yang efektif.</p>
                    </div>

                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold tracking-tight text-gray-900">Gaya Hidup Sehat</h3>
                        <p class="font-normal text-gray-500 text-sm">Panduan personal untuk membantu Anda membuat keputusan gaya hidup yang lebih baik setiap harinya.</p>
                    </div>

                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold tracking-tight text-gray-900">Kesadaran Risiko</h3>
                        <p class="font-normal text-gray-500 text-sm">Meningkatkan kesadaran akan kondisi kesehatan Anda dan potensi risiko di masa depan.</p>
                    </div>

                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold tracking-tight text-gray-900">Konsultasi Profesional</h3>
                        <p class="font-normal text-gray-500 text-sm">Hasil skrining dapat digunakan sebagai data awal saat berkonsultasi dengan dokter.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Mengenal Penyakit --}}
        <section class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900">Mengenal Penyakit Terkait</h2>
                <p class="mb-8 font-light text-gray-500 sm:text-xl sm:px-16 lg:px-48">Informasi singkat mengenai Diabetes Mellitus dan Hipertensi.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                    <div class="p-6 bg-white border border-blue-200 hover:border-blue-300 rounded-lg shadow-sm">
                        <h3 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 border-b pb-4">Diabetes Mellitus</h3>
                        <p class="mb-4 font-normal text-gray-600">Penyakit kronis yang ditandai dengan tingginya kadar gula darah. Glukosa menumpuk di dalam darah karena tubuh tidak dapat memproduksi atau menggunakan insulin dengan baik.</p>
                        <div class="mb-6">
                            <strong class="block mb-2 font-medium text-gray-900">Gejala Umum:</strong>
                            <ul class="list-disc list-inside text-sm text-gray-500 space-y-1">
                                <li>Sering merasa haus</li>
                                <li>Sering buang air kecil, terutama malam hari</li>
                                <li>Rasa lapar yang ekstrem</li>
                                <li>Kelelahan yang tidak wajar</li>
                            </ul>
                        </div>
                    </div>

                    <div class="p-6 bg-white border border-red-200 hover:border-red-300 rounded-lg shadow-sm">
                        <h3 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 border-b pb-4">Hipertensi</h3>
                        <p class="mb-4 font-normal text-gray-600">Sering disebut tekanan darah tinggi. Kondisi di mana tekanan darah terhadap dinding arteri secara konsisten terlalu tinggi, membebani kerja jantung dan pembuluh darah.</p>
                        <div class="mb-6">
                            <strong class="block mb-2 font-medium text-gray-900">Gejala Umum:</strong>
                            <ul class="list-disc list-inside text-sm text-gray-500 space-y-1">
                                <li>Sakit kepala parah</li>
                                <li>Kelelahan atau kebingungan</li>
                                <li>Masalah penglihatan</li>
                                <li>Nyeri dada atau detak jantung tidak teratur</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Bagaimana Skrining Bekerja --}}
        <section class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900">Bagaimana Skrining Ini Bekerja?</h2>
                <p class="mb-12 font-light text-gray-500 sm:text-xl sm:px-16 lg:px-48">Hanya membutuhkan waktu kurang lebih 5-10 menit untuk mengetahui risiko kesehatan Anda.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
                    <div class="hidden md:block absolute top-6 left-[12.5%] right-[12.5%] h-0.5 bg-gray-200 z-0"></div>

                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-700 text-white flex items-center justify-center font-bold text-lg border-4 border-white mb-4 shadow-sm">1</div>
                        <h4 class="font-medium text-gray-900 mb-2">Buka Website</h4>
                        <p class="text-sm text-gray-500">Akses sistem ini dari perangkat apapun secara gratis.</p>
                    </div>

                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-700 text-white flex items-center justify-center font-bold text-lg border-4 border-white mb-4 shadow-sm">2</div>
                        <h4 class="font-medium text-gray-900 mb-2">Jawab Kuesioner</h4>
                        <p class="text-sm text-gray-500">Isi data gaya hidup dan riwayat kesehatan dengan jujur.</p>
                    </div>

                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-700 text-white flex items-center justify-center font-bold text-lg border-4 border-white mb-4 shadow-sm">3</div>
                        <h4 class="font-medium text-gray-900 mb-2">Lihat Hasil Risiko</h4>
                        <p class="text-sm text-gray-500">Sistem akan mengalkulasi dan menampilkan tingkat risiko Anda.</p>
                    </div>

                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-700 text-white flex items-center justify-center font-bold text-lg border-4 border-white mb-4 shadow-sm">4</div>
                        <h4 class="font-medium text-gray-900 mb-2">Terima Rekomendasi</h4>
                        <p class="text-sm text-gray-500">Dapatkan saran gaya hidup atau rujukan medis yang tepat.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Keunggulan --}}
        <section class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900">Keunggulan Skrining Sehati</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mt-10">
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h4 class="font-medium text-gray-900 mb-1">Tanpa Registrasi</h4>
                        <p class="text-sm text-gray-500">Langsung mulai tanpa perlu akun.</p>
                    </div>
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h4 class="font-medium text-gray-900 mb-1">Gratis</h4>
                        <p class="text-sm text-gray-500">Tanpa biaya apapun.</p>
                    </div>
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h4 class="font-medium text-gray-900 mb-1">Cepat</h4>
                        <p class="text-sm text-gray-500">Hanya 5 menit selesai.</p>
                    </div>
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h4 class="font-medium text-gray-900 mb-1">Rahasia</h4>
                        <p class="text-sm text-gray-500">Privasi Anda sepenuhnya terjaga.</p>
                    </div>
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h4 class="font-medium text-gray-900 mb-1">Berbasis Riset</h4>
                        <p class="text-sm text-gray-500">Algoritma divalidasi akademisi.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- FAQ Section --}}
        <section id="faq" class="bg-white py-16">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900">Pertanyaan yang Sering Diajukan</h2>
                </div>
                
                @include('partials.faq', ['items' => [
                    ['q' => 'Apakah skrining ini bisa dijadikan diagnosis medis pasti?', 'a' => 'Tidak. Skrining ini hanya bertujuan untuk mendeteksi tingkat risiko awal. Hasil skrining tidak menggantikan diagnosis resmi dari dokter. Anda disarankan mengunjungi fasilitas kesehatan terdekat jika mendapatkan hasil risiko tinggi.'],
                    ['q' => 'Berapa lama waktu yang dibutuhkan?', 'a' => 'Proses skrining membutuhkan waktu sekitar 5-10 menit untuk mengisi seluruh kuesioner.'],
                    ['q' => 'Apakah data saya aman?', 'a' => 'Ya. Data Anda dijamin kerahasiaannya dan tidak dipublikasikan. Data hanya dianalisis secara kolektif untuk keperluan penelitian akademik tanpa mengaitkannya dengan identitas pribadi.'],
                    ['q' => 'Siapa yang sebaiknya mengikuti skrining ini?', 'a' => 'Setiap individu dewasa (di atas 18 tahun), khususnya mereka yang memiliki riwayat keluarga dengan penyakit diabetes atau hipertensi, jarang berolahraga, atau perokok aktif.'],
                ]])
            </div>
        </section>

        {{-- CTA Section --}}
        <section class="bg-blue-900 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-white">Siap Mengetahui Status Kesehatan Anda?</h2>
                <p class="mb-8 font-light text-blue-200 sm:text-xl">Mari ambil langkah pertama untuk masa depan yang lebih sehat. Gratis, aman, dan rahasia.</p>
                <a href="/consent" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-blue-900 rounded-lg bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300">
                    Mulai Skrining Sekarang
                </a>
            </div>
        </section>

    </main>

    {{-- Footer --}}
    @include('partials.footer')

@endsection
