@extends('layouts.guest')

@section('title', 'Data Diri Responden — Sehati')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12" data-aos="fade-up">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold text-lg">
                    1
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Identitas Peserta</h1>
            <p class="text-gray-500 mt-2">Mohon isi data identitas berikut. Data Anda dijamin kerahasiaannya.</p>
        </div>

        {{-- Form --}}
        <div class="p-6 sm:p-8 bg-white border border-gray-200 rounded-lg shadow-sm">
            <form action="{{ route('screening.questionnaire') }}" method="GET">
                <div class="flex p-4 mb-6 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                    <svg class="shrink-0 inline w-4 h-4 mr-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <div>
                        <span class="font-medium">Informasi:</span> Seluruh data akan dienkripsi dan hanya dianalisis secara
                        kolektif untuk kepentingan penelitian.
                    </div>
                </div>

                <div class="space-y-4">
                    {{-- No KTP --}}
                    <div>
                        <label for="nik" class="block mb-2 text-sm font-medium text-gray-900">NIK
                            <input type="text" id="nik" name="nik"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Masukkan 16 digit Nomor Induk Kependudukan">
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label for="fullname" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="fullname" name="fullname"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Masukkan nama lengkap" required>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label for="birthdate" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir <span
                                class="text-red-500">*</span></label>
                        <input type="date" id="birthdate" name="birthdate"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label for="gender" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin (JK) <span
                                class="text-red-500">*</span></label>
                        <select id="gender" name="gender"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label for="age" class="block mb-2 text-sm font-medium text-gray-900">Umur <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="age" name="age"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Masukkan umur dalam tahun">
                    </div>

                    {{-- Golongan Darah --}}
                    <div>
                        <label for="blood_type" class="block mb-2 text-sm font-medium text-gray-900">Golongan Darah <span
                                class="text-red-500">*</span></label>
                        <select id="blood_type" name="blood_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                            <option value="tidak_tahu">Tidak Tahu</option>
                        </select>
                    </div>

                    {{-- Desa Domisili & Posyandu --}}
                    <div x-data="{
                            selectedVillage: '',
                            villages: {{ Js::from($villages) }},
                            get healthPosts() {
                                if (!this.selectedVillage) return [];
                                const village = this.villages.find(v => v.id == this.selectedVillage);
                                return village ? village.health_posts : [];
                            }
                        }" class="space-y-4">
                        <div>
                            <label for="village_id" class="block mb-2 text-sm font-medium text-gray-900">Desa Domisili <span
                                    class="text-red-500">*</span></label>
                            <select id="village_id" name="village_id" x-model="selectedVillage"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required>
                                <option value="">Pilih Desa Tempat Tinggal</option>
                                <template x-for="village in villages" :key="village.id">
                                    <option :value="village.id" x-text="village.name"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label for="health_post_id" class="block mb-2 text-sm font-medium text-gray-900">Puskesmas /
                                Posyandu <span class="text-red-500">*</span></label>
                            <select id="health_post_id" name="health_post_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required :disabled="!selectedVillage">
                                <option value="">Pilih Posyandu</option>
                                <template x-for="post in healthPosts" :key="post.id">
                                    <option :value="post.id" x-text="post.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 my-6">

                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                    <a href="/consent"
                        class="w-full sm:w-auto text-gray-900 hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center">
                        Kembali
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center">
                        Simpan & Mulai Kuesioner
                        <svg class="w-4 h-4 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection