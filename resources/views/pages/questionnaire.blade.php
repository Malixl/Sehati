@extends('layouts.guest')

@section('title', 'Kuesioner Skrining — Sehati')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ step: 1, totalSteps: 3, isLoading: false }" data-aos="fade-up">
        
        {{-- Progress Header --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6 sticky top-4 z-30">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xl font-bold text-gray-900" x-text="step === 1 ? 'Bagian A: Gaya Hidup' : (step === 2 ? 'Bagian B: Riwayat Penyakit' : 'Bagian C: Gejala Fisik')"></span>
                <span class="text-sm font-medium text-gray-500"><span x-text="step"></span> dari <span x-text="totalSteps"></span></span>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
            </div>
        </div>

        {{-- Form Kuesioner --}}
        <form action="/result" method="GET" @submit.prevent="isLoading = true; setTimeout(() => $el.submit(), 2500)">
            
            {{-- Bagian 1: Gaya Hidup --}}
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
                    <p class="font-medium text-gray-900 mb-3">1. Seberapa sering Anda berolahraga atau melakukan aktivitas fisik sedang minimal 30 menit?</p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="q1_0" type="radio" value="0" name="q1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q1_0" class="ml-2 text-sm font-medium text-gray-900">Tidak Pernah / Jarang</label>
                        </div>
                        <div class="flex items-center">
                            <input id="q1_1" type="radio" value="1" name="q1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q1_1" class="ml-2 text-sm font-medium text-gray-900">1-2 kali seminggu</label>
                        </div>
                        <div class="flex items-center">
                            <input id="q1_2" type="radio" value="2" name="q1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q1_2" class="ml-2 text-sm font-medium text-gray-900">3 kali atau lebih seminggu</label>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
                    <p class="font-medium text-gray-900 mb-3">2. Apakah Anda mengonsumsi sayur dan buah setiap hari?</p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="q2_0" type="radio" value="0" name="q2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q2_0" class="ml-2 text-sm font-medium text-gray-900">Tidak setiap hari</label>
                        </div>
                        <div class="flex items-center">
                            <input id="q2_1" type="radio" value="1" name="q2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q2_1" class="ml-2 text-sm font-medium text-gray-900">Ya, setiap hari</label>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
                    <p class="font-medium text-gray-900 mb-3">3. Apakah Anda merokok atau pernah merokok secara rutin di masa lalu?</p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="q3_0" type="radio" value="0" name="q3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q3_0" class="ml-2 text-sm font-medium text-gray-900">Ya, perokok aktif / mantan perokok</label>
                        </div>
                        <div class="flex items-center">
                            <input id="q3_1" type="radio" value="1" name="q3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q3_1" class="ml-2 text-sm font-medium text-gray-900">Tidak, saya tidak pernah merokok</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Riwayat --}}
            <div x-show="step === 2" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
                    <p class="font-medium text-gray-900 mb-3">4. Apakah ada anggota keluarga inti (orang tua/saudara kandung) yang menderita Diabetes?</p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="q4_1" type="radio" value="1" name="q4" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q4_1" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="q4_0" type="radio" value="0" name="q4" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q4_0" class="ml-2 text-sm font-medium text-gray-900">Tidak / Tidak Tahu</label>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
                    <p class="font-medium text-gray-900 mb-3">5. Pernahkah Anda diberitahu oleh tenaga medis bahwa Anda memiliki gula darah tinggi (contoh: saat kehamilan atau MCU)?</p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="q5_1" type="radio" value="1" name="q5" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q5_1" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="q5_0" type="radio" value="0" name="q5" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q5_0" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
                    <p class="font-medium text-gray-900 mb-3">6. Pernahkah Anda didiagnosis memiliki tekanan darah tinggi atau mengonsumsi obat penurun darah tinggi secara rutin?</p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="q6_1" type="radio" value="1" name="q6" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q6_1" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="q6_0" type="radio" value="0" name="q6" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q6_0" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian 3: Gejala Klinis --}}
            <div x-show="step === 3" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
                    <p class="font-medium text-gray-900 mb-3">7. Apakah Anda sering merasa cepat lelah tanpa alasan yang jelas?</p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="q7_1" type="radio" value="1" name="q7" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q7_1" class="ml-2 text-sm font-medium text-gray-900">Sering</label>
                        </div>
                        <div class="flex items-center">
                            <input id="q7_0" type="radio" value="0" name="q7" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q7_0" class="ml-2 text-sm font-medium text-gray-900">Jarang / Tidak Pernah</label>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
                    <p class="font-medium text-gray-900 mb-3">8. Apakah Anda sering merasa haus dan buang air kecil lebih sering dari biasanya (terutama malam hari)?</p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="q8_1" type="radio" value="1" name="q8" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q8_1" class="ml-2 text-sm font-medium text-gray-900">Ya, sering mengalami hal ini</label>
                        </div>
                        <div class="flex items-center">
                            <input id="q8_0" type="radio" value="0" name="q8" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q8_0" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
                    <p class="font-medium text-gray-900 mb-3">9. Apakah Anda sering mengalami sakit kepala parah, penglihatan kabur tiba-tiba, atau tengkuk terasa berat?</p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="q9_1" type="radio" value="1" name="q9" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q9_1" class="ml-2 text-sm font-medium text-gray-900">Sering</label>
                        </div>
                        <div class="flex items-center">
                            <input id="q9_0" type="radio" value="0" name="q9" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="q9_0" class="ml-2 text-sm font-medium text-gray-900">Jarang / Tidak Pernah</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigasi Tombol --}}
            <div class="flex items-center justify-between mt-8 border-t border-gray-200 pt-6">
                {{-- Tombol Prev --}}
                <button type="button" @click="if(step > 1) step--" x-show="step > 1" style="display: none;" class="text-gray-900 hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center">
                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Sebelumnya
                </button>
                <div x-show="step === 1"></div> {{-- Spacer if Prev is hidden --}}

                {{-- Tombol Next --}}
                <button type="button" @click="if(step < totalSteps) step++" x-show="step < totalSteps" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center">
                    Selanjutnya
                    <svg class="w-4 h-4 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>

                {{-- Tombol Submit --}}
                <button type="submit" x-show="step === totalSteps" style="display: none;" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center">
                    Selesaikan Skrining
                    <svg class="w-4 h-4 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </div>

        </form>

        {{-- Loading Overlay --}}
        <div x-show="isLoading" style="display: none;" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <lottie-player 
                src="{{ asset('img/heartbeat.json') }}" 
                background="transparent" 
                speed="1" 
                style="width: 200px; height: 200px;" 
                loop 
                autoplay>
            </lottie-player>
            <h3 class="mt-4 text-xl font-bold text-gray-900 animate-pulse">Sedang menganalisis hasil skrining Anda...</h3>
            <p class="text-sm text-gray-500 mt-2">Harap tunggu sebentar</p>
        </div>

    </div>
@endsection
