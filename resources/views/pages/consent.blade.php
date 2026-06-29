@extends('layouts.guest')

@section('title', 'Persetujuan (Informed Consent) — Sehati')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12" data-aos="fade-up">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 mb-6">
                <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900">Sehati</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Persetujuan Responden</h1>
            <p class="text-gray-500 mt-2">Harap membaca informasi di bawah ini sebelum melanjutkan skrining.</p>
        </div>

        {{-- Consent Content --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-8 overflow-y-auto max-h-[60vh]">
            <div class="text-sm text-gray-600 space-y-4">
                <p><strong>Judul Penelitian:</strong> Skrining Dini Risiko Penyakit Diabetes Mellitus dan Hipertensi Berbasis Web.</p>
                
                <p>Kami mengundang Anda untuk berpartisipasi dalam skrining kesehatan ini. Skrining ini bertujuan untuk mendeteksi tingkat risiko awal terkait penyakit Diabetes Mellitus dan Hipertensi di masyarakat.</p>
                
                <h4 class="text-gray-900 font-semibold mt-6">1. Prosedur Pelaksanaan</h4>
                <p>Jika Anda bersedia berpartisipasi, Anda akan diminta untuk mengisi kuesioner yang terdiri dari data demografi dasar (seperti usia, jenis kelamin) dan pertanyaan terkait gaya hidup serta riwayat kesehatan. Waktu yang dibutuhkan adalah sekitar 5-10 menit.</p>
                
                <h4 class="text-gray-900 font-semibold mt-6">2. Manfaat & Risiko</h4>
                <p>Manfaat dari partisipasi Anda adalah Anda dapat mengetahui indikasi awal risiko kesehatan yang mungkin Anda miliki, sehingga dapat melakukan langkah pencegahan dini. Tidak ada risiko fisik atau medis langsung yang ditimbulkan dengan mengikuti kuesioner ini.</p>
                
                <h4 class="text-gray-900 font-semibold mt-6">3. Kerahasiaan Data (Anonimitas)</h4>
                <p>Kami menjamin kerahasiaan data yang Anda berikan. Kami tidak mengumpulkan nama lengkap atau data identifikasi pribadi lainnya secara wajib (opsional/inisial diperbolehkan). Seluruh data akan dienkripsi dan hanya akan dianalisis secara kolektif/agregat untuk kepentingan penelitian ini saja.</p>
                
                <h4 class="text-gray-900 font-semibold mt-6">4. Sifat Sukarela</h4>
                <p>Partisipasi dalam skrining ini sepenuhnya bersifat sukarela. Anda berhak menolak atau mengundurkan diri kapan saja selama pengisian kuesioner tanpa sanksi apa pun.</p>
            </div>
        </div>

        {{-- Form Persetujuan --}}
        <form action="/respondent" method="GET" x-data="{ agreed: false }">
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="agree" name="agree" value="1" type="checkbox" required @change="agreed = $event.target.checked" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                    </div>
                    <label for="agree" class="ml-2 text-sm font-medium text-gray-900">Saya telah membaca, memahami, dan bersedia berpartisipasi dalam skrining kesehatan ini.</label>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                <a href="/" class="w-full sm:w-auto text-gray-900 hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center">
                    Batal & Kembali
                </a>
                <button type="submit" x-bind:disabled="!agreed" class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center disabled:opacity-50 disabled:cursor-not-allowed">
                    Saya Setuju, Lanjutkan
                    <svg class="w-4 h-4 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

    </div>
@endsection
