@extends('layouts.guest')

@section('title', 'Data Diri Responden — Sehati')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold text-lg">
                    1
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Data Diri (Demografi)</h1>
            <p class="text-gray-500 mt-2">Mohon isi data dasar berikut. Data Anda dijamin kerahasiaannya.</p>
        </div>

        {{-- Form --}}
        <div class="p-6 sm:p-8 bg-white border border-gray-200 rounded-lg shadow-sm">
            <form action="/questionnaire" method="GET">
                <div class="flex p-4 mb-6 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                    <svg class="shrink-0 inline w-4 h-4 mr-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <div>
                        <span class="font-medium">Informasi:</span> Hanya inisial atau nama panggilan yang diperlukan untuk menjaga kerahasiaan identitas Anda.
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama (Inisial) <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Budi atau B.A" required>
                    </div>
                    
                    <div>
                        <label for="age" class="block mb-2 text-sm font-medium text-gray-900">Usia <span class="text-red-500">*</span></label>
                        <input type="number" id="age" name="age" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan usia dalam angka" required>
                        <p class="mt-1 text-sm text-gray-500">Umur harus di atas 18 tahun</p>
                    </div>
                    
                    <div>
                        <label for="gender" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select id="gender" name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="education" class="block mb-2 text-sm font-medium text-gray-900">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                        <select id="education" name="education" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Pilih Pendidikan Terakhir</option>
                            <option value="sd">SD / Sederajat</option>
                            <option value="smp">SMP / Sederajat</option>
                            <option value="sma">SMA / Sederajat</option>
                            <option value="diploma">Diploma (D1/D2/D3)</option>
                            <option value="sarjana">Sarjana (S1/S2/S3)</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label for="job" class="block mb-2 text-sm font-medium text-gray-900">Pekerjaan <span class="text-red-500">*</span></label>
                        <select id="job" name="job" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Pilih Pekerjaan</option>
                            <option value="pns">PNS / TNI / Polri</option>
                            <option value="swasta">Pegawai Swasta</option>
                            <option value="wirausaha">Wirausaha / Pedagang</option>
                            <option value="irt">Ibu Rumah Tangga</option>
                            <option value="pelajar">Pelajar / Mahasiswa</option>
                            <option value="tidak_bekerja">Tidak / Belum Bekerja</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <hr class="border-gray-200 my-6">

                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                    <a href="/consent" class="w-full sm:w-auto text-gray-900 hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center">
                        Kembali
                    </a>
                    <button type="submit" class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex justify-center items-center">
                        Simpan & Mulai Kuesioner
                        <svg class="w-4 h-4 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
