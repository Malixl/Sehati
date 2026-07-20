@extends('layouts.guest')

@section('title', 'Kuesioner Skrining — Sehati')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
        x-data="{
            step: 1,
            totalSteps: 3,
            isLoading: false,
            validationError: '',

            // Bagian A — Riwayat Penyakit Tidak Menular Pada Keluarga
            a_diabetes: null,
            a_hipertensi: null,
            a_jantung: null,
            a_stroke: null,
            a_kolesterol: null,

            // Bagian B — Riwayat Penyakit Tidak Menular Pada Diri Sendiri
            b_diabetes: null,
            b_hipertensi: null,
            b_jantung: null,
            b_stroke: null,
            b_asma: null,
            b_kolesterol: null,

            // Bagian C — Faktor Risiko dan Pemeriksaan Kesehatan
            c_tekanan_sistolik: '',
            c_tekanan_diastolik: '',
            c_tinggi: '',
            c_berat: '',
            c_lingkar_perut: '',
            c_gula: '',
            c_kolesterol: '',
            c_asam_urat: '',
            c_merokok: null,

            // Computed IMT
            get imt() {
                if (this.c_tinggi && this.c_berat) {
                    const tinggiM = parseFloat(this.c_tinggi) / 100;
                    const berat = parseFloat(this.c_berat);
                    if (tinggiM > 0 && berat > 0) {
                        return (berat / (tinggiM * tinggiM)).toFixed(1);
                    }
                }
                return '-';
            },

            get imtCategory() {
                const val = parseFloat(this.imt);
                if (isNaN(val)) return '';
                if (val < 18.5) return 'Berat Badan Kurang';
                if (val < 25) return 'Normal';
                if (val < 30) return 'Berat Badan Lebih';
                return 'Obesitas';
            },

            get imtColor() {
                const val = parseFloat(this.imt);
                if (isNaN(val)) return 'text-gray-500';
                if (val < 18.5) return 'text-blue-600';
                if (val < 25) return 'text-green-600';
                if (val < 30) return 'text-yellow-600';
                return 'text-red-600';
            },

            // Step titles
            stepTitle() {
                if (this.step === 1) return 'Bagian A: Riwayat Penyakit Tidak Menular Pada Keluarga';
                if (this.step === 2) return 'Bagian B: Riwayat Penyakit Tidak Menular Pada Diri Sendiri';
                return 'Bagian C: Faktor Risiko dan Pemeriksaan Kesehatan';
            },

            // Validation per step
            canProceed() {
                if (this.step === 1) {
                    return this.a_diabetes !== null &&
                           this.a_hipertensi !== null &&
                           this.a_jantung !== null &&
                           this.a_stroke !== null &&
                           this.a_kolesterol !== null;
                }
                if (this.step === 2) {
                    return this.b_diabetes !== null &&
                           this.b_hipertensi !== null &&
                           this.b_jantung !== null &&
                           this.b_stroke !== null &&
                           this.b_asma !== null &&
                           this.b_kolesterol !== null;
                }
                if (this.step === 3) {
                    return this.c_tekanan_sistolik !== '' &&
                           this.c_tekanan_diastolik !== '' &&
                           this.c_tinggi !== '' &&
                           this.c_berat !== '' &&
                           this.c_lingkar_perut !== '' &&
                           this.c_merokok !== null;
                }
                return false;
            },

            nextStep() {
                if (!this.canProceed()) {
                    this.validationError = 'Mohon lengkapi semua pertanyaan sebelum melanjutkan.';
                    setTimeout(() => this.validationError = '', 3000);
                    return;
                }
                this.validationError = '';
                if (this.step < this.totalSteps) {
                    this.step++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },

            prevStep() {
                this.validationError = '';
                if (this.step > 1) {
                    this.step--;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },

            submitForm() {
                if (!this.canProceed()) {
                    this.validationError = 'Mohon lengkapi semua pertanyaan sebelum mengirim.';
                    setTimeout(() => this.validationError = '', 3000);
                    return;
                }
                
                // Scroll to top INSTANTLY before showing loading
                window.scrollTo(0, 0);
                
                this.isLoading = true;
                document.body.style.overflow = 'hidden';
                
                setTimeout(() => {
                    this.$refs.form.submit();
                }, 2500);
            }
        }"
        data-aos="fade-up">

        {{-- Progress Header --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6 sticky top-4 z-30">
            <div class="flex items-center justify-between mb-2">
                <span class="text-lg sm:text-xl font-bold text-gray-900" x-text="stepTitle()"></span>
                <span class="text-sm font-medium text-gray-500 shrink-0 ml-4">
                    <span x-text="step"></span> dari <span x-text="totalSteps"></span>
                </span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-out"
                    :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
            </div>
        </div>

        {{-- Validation Error Alert --}}
        <div x-show="validationError" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
            <svg class="shrink-0 inline w-4 h-4 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <span x-text="validationError"></span>
        </div>

        {{-- Form Kuesioner --}}
        <form action="{{ route('screening.store') }}" method="POST" x-ref="form">
            @csrf

            {{-- Hidden fields: Data demografi dari form respondent --}}
            @foreach($respondentData as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            {{-- ============================================ --}}
            {{-- BAGIAN A: Riwayat Penyakit Tidak Menular Pada Keluarga --}}
            {{-- ============================================ --}}
            <div x-show="step === 1"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0">

                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold">Petunjuk:</span> Apakah ada anggota keluarga Anda (orang tua, saudara kandung) yang memiliki riwayat penyakit berikut?
                    </p>
                </div>

                {{-- A1: Penyakit Diabetes --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">1. Penyakit Diabetes</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="a_diabetes_ya" type="radio" value="1" name="a_diabetes" x-model="a_diabetes"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="a_diabetes_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="a_diabetes_tidak" type="radio" value="0" name="a_diabetes" x-model="a_diabetes"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="a_diabetes_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>

                {{-- A2: Penyakit Hipertensi --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">2. Penyakit Hipertensi</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="a_hipertensi_ya" type="radio" value="1" name="a_hipertensi" x-model="a_hipertensi"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="a_hipertensi_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="a_hipertensi_tidak" type="radio" value="0" name="a_hipertensi" x-model="a_hipertensi"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="a_hipertensi_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>

                {{-- A3: Penyakit Jantung --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">3. Penyakit Jantung</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="a_jantung_ya" type="radio" value="1" name="a_jantung" x-model="a_jantung"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="a_jantung_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="a_jantung_tidak" type="radio" value="0" name="a_jantung" x-model="a_jantung"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="a_jantung_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>

                {{-- A4: Penyakit Stroke --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">4. Penyakit Stroke</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="a_stroke_ya" type="radio" value="1" name="a_stroke" x-model="a_stroke"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="a_stroke_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="a_stroke_tidak" type="radio" value="0" name="a_stroke" x-model="a_stroke"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="a_stroke_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>

                {{-- A5: Kolesterol Tinggi --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">5. Kolesterol Tinggi</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="a_kolesterol_ya" type="radio" value="1" name="a_kolesterol" x-model="a_kolesterol"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="a_kolesterol_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="a_kolesterol_tidak" type="radio" value="0" name="a_kolesterol" x-model="a_kolesterol"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="a_kolesterol_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- BAGIAN B: Riwayat Penyakit Tidak Menular Pada Diri Sendiri --}}
            {{-- ============================================ --}}
            <div x-show="step === 2" x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0">

                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold">Petunjuk:</span> Apakah Anda sendiri pernah didiagnosis atau memiliki riwayat penyakit berikut?
                    </p>
                </div>

                {{-- B1: Penyakit Diabetes --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">1. Penyakit Diabetes</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="b_diabetes_ya" type="radio" value="1" name="b_diabetes" x-model="b_diabetes"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_diabetes_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="b_diabetes_tidak" type="radio" value="0" name="b_diabetes" x-model="b_diabetes"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_diabetes_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>

                {{-- B2: Penyakit Hipertensi --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">2. Penyakit Hipertensi</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="b_hipertensi_ya" type="radio" value="1" name="b_hipertensi" x-model="b_hipertensi"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_hipertensi_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="b_hipertensi_tidak" type="radio" value="0" name="b_hipertensi" x-model="b_hipertensi"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_hipertensi_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>

                {{-- B3: Penyakit Jantung --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">3. Penyakit Jantung</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="b_jantung_ya" type="radio" value="1" name="b_jantung" x-model="b_jantung"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_jantung_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="b_jantung_tidak" type="radio" value="0" name="b_jantung" x-model="b_jantung"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_jantung_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>

                {{-- B4: Penyakit Stroke --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">4. Penyakit Stroke</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="b_stroke_ya" type="radio" value="1" name="b_stroke" x-model="b_stroke"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_stroke_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="b_stroke_tidak" type="radio" value="0" name="b_stroke" x-model="b_stroke"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_stroke_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>

                {{-- B5: Penyakit Asma --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">5. Penyakit Asma</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="b_asma_ya" type="radio" value="1" name="b_asma" x-model="b_asma"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_asma_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="b_asma_tidak" type="radio" value="0" name="b_asma" x-model="b_asma"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_asma_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>

                {{-- B6: Kolesterol Tinggi --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">6. Kolesterol Tinggi</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="b_kolesterol_ya" type="radio" value="1" name="b_kolesterol" x-model="b_kolesterol"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_kolesterol_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="b_kolesterol_tidak" type="radio" value="0" name="b_kolesterol" x-model="b_kolesterol"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="b_kolesterol_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- BAGIAN C: Faktor Risiko dan Pemeriksaan Kesehatan --}}
            {{-- ============================================ --}}
            <div x-show="step === 3" x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0">

                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold">Petunjuk:</span> Masukkan hasil pengukuran dan pemeriksaan kesehatan Anda. Field pemeriksaan lab bersifat opsional jika belum pernah diperiksa.
                    </p>
                </div>

                {{-- C1: Tekanan Darah --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">1. Tekanan Darah</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="c_tekanan_sistolik" class="block mb-1.5 text-sm text-gray-600">Sistolik (mmHg)</label>
                            <input type="number" id="c_tekanan_sistolik" name="c_tekanan_sistolik" x-model="c_tekanan_sistolik"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Cth: 120" min="50" max="300" required>
                        </div>
                        <div>
                            <label for="c_tekanan_diastolik" class="block mb-1.5 text-sm text-gray-600">Diastolik (mmHg)</label>
                            <input type="number" id="c_tekanan_diastolik" name="c_tekanan_diastolik" x-model="c_tekanan_diastolik"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Cth: 80" min="30" max="200" required>
                        </div>
                    </div>
                </div>

                {{-- C2: Tinggi Badan --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">2. Tinggi Badan</p>
                    <div>
                        <label for="c_tinggi" class="block mb-1.5 text-sm text-gray-600">Dalam satuan cm</label>
                        <input type="number" id="c_tinggi" name="c_tinggi" x-model="c_tinggi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Cth: 165" min="50" max="250" step="0.1" required>
                    </div>
                </div>

                {{-- C3: Berat Badan --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">3. Berat Badan</p>
                    <div>
                        <label for="c_berat" class="block mb-1.5 text-sm text-gray-600">Dalam satuan kg</label>
                        <input type="number" id="c_berat" name="c_berat" x-model="c_berat"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Cth: 65" min="10" max="300" step="0.1" required>
                    </div>
                </div>

                {{-- C4: IMT (Auto-calculated) --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">4. IMT (Indeks Masa Tubuh)</p>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <input type="text" name="c_imt" :value="imt" readonly
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed font-semibold"
                                :class="imtColor">
                        </div>
                        <div class="shrink-0">
                            <span class="text-sm font-medium px-3 py-1.5 rounded-full"
                                :class="{
                                    'bg-blue-50 text-blue-700': imt !== '-' && parseFloat(imt) < 18.5,
                                    'bg-green-50 text-green-700': imt !== '-' && parseFloat(imt) >= 18.5 && parseFloat(imt) < 25,
                                    'bg-yellow-50 text-yellow-700': imt !== '-' && parseFloat(imt) >= 25 && parseFloat(imt) < 30,
                                    'bg-red-50 text-red-700': imt !== '-' && parseFloat(imt) >= 30,
                                    'bg-gray-50 text-gray-500': imt === '-'
                                }"
                                x-text="imt === '-' ? 'Isi tinggi & berat' : imtCategory"></span>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-400">Dihitung otomatis dari Tinggi Badan dan Berat Badan.</p>
                </div>

                {{-- C5: Lingkar Perut --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">5. Lingkar Perut</p>
                    <div>
                        <label for="c_lingkar_perut" class="block mb-1.5 text-sm text-gray-600">Dalam satuan cm</label>
                        <input type="number" id="c_lingkar_perut" name="c_lingkar_perut" x-model="c_lingkar_perut"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Cth: 80" min="30" max="200" step="0.1" required>
                    </div>
                </div>

                {{-- C6: Pemeriksaan Lab --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">6. Pemeriksaan Lab</p>
                    <p class="text-xs text-gray-500 mb-4">Kosongkan jika belum pernah diperiksa.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="c_gula" class="block mb-1.5 text-sm text-gray-600">Gula Darah (mg/dL)</label>
                            <input type="number" id="c_gula" name="c_gula" x-model="c_gula"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Cth: 100" min="0" max="1000" step="0.1">
                        </div>
                        <div>
                            <label for="c_kolesterol_lab" class="block mb-1.5 text-sm text-gray-600">Kolesterol (mg/dL)</label>
                            <input type="number" id="c_kolesterol_lab" name="c_kolesterol_lab" x-model="c_kolesterol"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Cth: 200" min="0" max="1000" step="0.1">
                        </div>
                        <div>
                            <label for="c_asam_urat" class="block mb-1.5 text-sm text-gray-600">Asam Urat (mg/dL)</label>
                            <input type="number" id="c_asam_urat" name="c_asam_urat" x-model="c_asam_urat"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Cth: 5.0" min="0" max="50" step="0.1">
                        </div>
                    </div>
                </div>

                {{-- C7: Merokok --}}
                <div class="p-5 sm:p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-3">
                    <p class="font-medium text-gray-900 mb-3">7. Merokok</p>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center">
                            <input id="c_merokok_ya" type="radio" value="1" name="c_merokok" x-model="c_merokok"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="c_merokok_ya" class="ml-2 text-sm font-medium text-gray-900">Ya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="c_merokok_tidak" type="radio" value="0" name="c_merokok" x-model="c_merokok"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <label for="c_merokok_tidak" class="ml-2 text-sm font-medium text-gray-900">Tidak</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- NAVIGASI TOMBOL --}}
            {{-- ============================================ --}}
            <div class="flex items-center justify-between mt-8 border-t border-gray-200 pt-6">
                {{-- Tombol Sebelumnya --}}
                <button type="button" @click="prevStep()" x-show="step > 1" x-cloak
                    class="text-gray-900 hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center transition-colors">
                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Sebelumnya
                </button>
                <div x-show="step === 1"></div> {{-- Spacer --}}

                {{-- Tombol Selanjutnya --}}
                <button type="button" @click="nextStep()" x-show="step < totalSteps"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center transition-colors">
                    Selanjutnya
                    <svg class="w-4 h-4 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>

                {{-- Tombol Selesaikan --}}
                <button type="button" @click="submitForm()" x-show="step === totalSteps" x-cloak
                    class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center transition-colors">
                    Selesaikan Skrining
                    <svg class="w-4 h-4 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>

        </form>

        {{-- Loading Overlay --}}
        <template x-teleport="body">
            <div x-show="isLoading" x-cloak
                class="fixed top-0 left-0 w-screen h-screen z-9999 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                <lottie-player src="{{ asset('img/heartbeat.json') }}" background="transparent" speed="1"
                    style="width: 200px; height: 200px;" loop autoplay>
                </lottie-player>
                <h3 class="mt-4 text-xl font-bold text-gray-900 animate-pulse">Sedang menganalisis hasil skrining Anda...</h3>
                <p class="text-sm text-gray-500 mt-2">Harap tunggu sebentar</p>
            </div>
        </template>

    </div>
@endsection