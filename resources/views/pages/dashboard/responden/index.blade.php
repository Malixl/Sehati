@extends('layouts.dashboard')

@section('title', 'Data Skrining')

@section('content')

    <x-dashboard.page-header title="Data Skrining"
        subtitle="Kelola dan lihat data masyarakat yang telah mengikuti skrining kesehatan." :breadcrumb="['Data Skrining' => null]">
        <x-slot name="action">
        <x-slot name="action">
            <div class="flex gap-2">
                <a href="{{ route('dashboard.responden.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200">
                    <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export CSV
                </a>
                
                <x-dashboard.button variant="primary" x-data="" @click.prevent="$dispatch('open-modal', 'add-responden')">
                    + Tambah Responden
                </x-dashboard.button>
            </div>
        </x-slot>
    </x-dashboard.page-header>


    
    @if($errors->any())
        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-dashboard.table>
        <x-slot name="head">
            <th scope="col" class="px-4 py-3">NIK</th>
            <th scope="col" class="px-4 py-3">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'fullname', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center group">
                    Nama Lengkap
                    <span class="ml-1 flex flex-col">
                        <svg class="w-2 h-2 {{ request('sort') === 'fullname' && request('direction') === 'asc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a.75.75 0 01.53.22l4 4a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l4-4A.75.75 0 0110 3z"/></svg>
                        <svg class="w-2 h-2 {{ request('sort') === 'fullname' && request('direction', 'desc') === 'desc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }} -mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 17a.75.75 0 01-.53-.22l-4-4a.75.75 0 011.06-1.06L10 15.19l3.47-3.47a.75.75 0 011.06 1.06l-4 4a.75.75 0 01-.53.22z"/></svg>
                    </span>
                </a>
            </th>
            <th scope="col" class="px-4 py-3">Jenis Kelamin</th>
            <th scope="col" class="px-4 py-3">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center group">
                    Tgl Daftar
                    <span class="ml-1 flex flex-col">
                        <svg class="w-2 h-2 {{ request('sort', 'created_at') === 'created_at' && request('direction') === 'asc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a.75.75 0 01.53.22l4 4a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l4-4A.75.75 0 0110 3z"/></svg>
                        <svg class="w-2 h-2 {{ request('sort', 'created_at') === 'created_at' && request('direction', 'desc') === 'desc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }} -mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 17a.75.75 0 01-.53-.22l-4-4a.75.75 0 011.06-1.06L10 15.19l3.47-3.47a.75.75 0 011.06 1.06l-4 4a.75.75 0 01-.53.22z"/></svg>
                    </span>
                </a>
            </th>
            <th scope="col" class="px-4 py-3">Umur</th>
            <th scope="col" class="px-4 py-3">Fasilitas Kesehatan</th>
            <th scope="col" class="px-4 py-3">Aksi</th>
        </x-slot>
        <x-slot name="toolbar">
            <form action="{{ route('dashboard.responden.index') }}" method="GET" x-data="{ showFilters: false }"
                class="flex flex-col space-y-3">
                <div class="flex items-center justify-between">
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 shadow-sm"
                            placeholder="Cari NIK atau Nama..." onchange="this.form.submit()">
                    </div>
                    <button type="button" @click="showFilters = !showFilters"
                        class="ml-3 md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                    </button>
                </div>

                <div :class="{'hidden': !showFilters, 'block': showFilters}" class="md:block">
                    <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-3">
                        {{-- Filter Gender --}}
                        <select name="gender"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 shadow-sm w-full sm:w-auto"
                            onchange="this.form.submit()">
                            <option value="">Semua Gender</option>
                            <option value="L" {{ request('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ request('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>

                    </div>
                </div>
            </form>
        </x-slot>
        @forelse($respondents as $respondent)
            <tr class="border-b border-gray-200 hover:bg-white hover:shadow-md transition-all duration-200">
                <td class="px-4 py-3 text-gray-900">{{ $respondent->nik ?? '-' }}</td>
                <td class="px-4 py-3 font-medium text-gray-900">{{ $respondent->fullname }}</td>
                <td class="px-4 py-3">{{ $respondent->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                <td class="px-4 py-3">
                    {{ $respondent->birthdate ? \Carbon\Carbon::parse($respondent->birthdate)->age . ' thn' : '-' }}
                </td>
                <td class="px-4 py-3">{{ $respondent->healthPost->name ?? '-' }}</td>
                <td class="px-4 py-3 flex space-x-2 items-center">
                    <a href="{{ route('responden.show', $respondent->id) }}" class="text-blue-600 hover:text-blue-900 font-medium text-sm" title="Detail">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </a>
                    
                    <button type="button" @click="$dispatch('open-modal', 'edit-responden-{{ $respondent->id }}')" class="text-blue-600 hover:text-blue-900 font-medium text-sm" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    </button>
                    
                    <form action="{{ route('dashboard.responden.destroy', $respondent->id) }}" method="POST" class="inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm transition-transform hover:scale-110" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>

                    {{-- Edit Modal --}}
                    <div x-data="{ show: false }" @open-modal.window="if ($event.detail === 'edit-responden-{{ $respondent->id }}') show = true" @close-modal.window="show = false" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
                        <div @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 my-8 max-h-[90vh] overflow-y-auto">
                            <div class="flex items-center justify-between p-4 border-b sticky top-0 bg-white z-10">
                                <h3 class="text-lg font-semibold text-gray-900">Edit Responden</h3>
                                <button @click="show = false" class="text-gray-400 hover:text-gray-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            <form action="{{ route('dashboard.responden.update', $respondent->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="p-4 space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">NIK</label>
                                            <input type="text" name="nik" value="{{ $respondent->nik }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                                            <input type="text" name="fullname" value="{{ $respondent->fullname }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin</label>
                                            <select name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                                <option value="L" {{ $respondent->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ $respondent->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir</label>
                                            <input type="date" name="birth_date" value="{{ \Carbon\Carbon::parse($respondent->birthdate ?? $respondent->birth_date)->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        </div>
                                        <div class="col-span-1 md:col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Alamat Lengkap</label>
                                            <textarea name="address" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>{{ $respondent->address }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Desa</label>
                                            <select name="village_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                                @foreach($villages as $v)
                                                    <option value="{{ $v->id }}" {{ $respondent->village_id == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Fasilitas Kesehatan</label>
                                            <select name="health_post_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                                @foreach($posyandus as $p)
                                                    <option value="{{ $p->id }}" {{ $respondent->health_post_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end p-4 border-t space-x-2 sticky bottom-0 bg-white">
                                    <button type="button" @click="show = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                    Tidak ada data responden ditemukan.
                </td>
            </tr>
        @endforelse
    </x-dashboard.table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $respondents->links() }}
    </div>

    {{-- Add Modal --}}
    <div x-data="{ show: false }" @open-modal.window="if ($event.detail === 'add-responden') show = true" @close-modal.window="show = false" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
        <div @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 my-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-4 border-b sticky top-0 bg-white z-10">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Responden Baru</h3>
                <button @click="show = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.responden.store') }}" method="POST">
                @csrf
                <div class="p-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">NIK</label>
                            <input type="text" name="nik" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                            <input type="text" name="fullname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin</label>
                            <select name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required></textarea>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Desa</label>
                            <select name="village_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="">-- Pilih Desa --</option>
                                @foreach($villages as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Fasilitas Kesehatan</label>
                            <select name="health_post_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="">-- Pilih Fasilitas Kesehatan --</option>
                                @foreach($posyandus as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t space-x-2 sticky bottom-0 bg-white">
                    <button type="button" @click="show = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">Simpan Responden</button>
                </div>
            </form>
        </div>
    </div>

@endsection