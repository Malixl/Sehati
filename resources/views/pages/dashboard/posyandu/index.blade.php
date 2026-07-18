@extends('layouts.dashboard')

@section('title', 'Data Fasilitas Pelayanan Kesehatan')

@section('content')

    <x-dashboard.page-header title="Data Fasilitas Pelayanan Kesehatan"
        subtitle="Kelola master data fasilitas pelayanan kesehatan di wilayah kerja Puskesmas Tilango." :breadcrumb="['Data Fasilitas Pelayanan Kesehatan' => null]">
        <x-slot name="action">
            <div class="flex gap-2">
                <a href="{{ route('dashboard.posyandu.export', request()->all()) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200">
                    <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export CSV
                </a>

                @if(Auth::user()->isSuperAdmin())
                    <x-dashboard.button variant="primary" x-data="" @click.prevent="$dispatch('open-modal', 'add-posyandu')">
                        + Tambah Data
                    </x-dashboard.button>
                @endif
            </div>
        </x-slot>
    </x-dashboard.page-header>

    @if(session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex items-center justify-end mb-4">
        <form action="{{ route('dashboard.posyandu.index') }}" method="GET" class="relative w-full md:w-64">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 shadow-sm"
                placeholder="Cari Fasilitas Kesehatan atau Desa..." onchange="this.form.submit()">
        </form>
    </div>

    {{-- Data Table --}}
    <x-dashboard.table :headers="['Nama Fasilitas Kesehatan', 'Desa', 'Kecamatan', 'Total Operator', 'Aksi']">
        @forelse($posyanduList as $posyandu)
            <tr class="border-b border-gray-200 hover:bg-white hover:shadow-md transition-all duration-200">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $posyandu->name }}</td>
                <td class="px-4 py-3">{{ $posyandu->village_id ? ($posyandu->village->name ?? '-') : 'Kecamatan Tilango' }}</td>
                <td class="px-4 py-3">{{ $posyandu->village_id ? ($posyandu->village->district->name ?? '-') : 'Tilango' }}</td>
                <td class="px-4 py-3">{{ $posyandu->users_count ?? $posyandu->users()->count() }} Orang</td>

                <td class="px-4 py-3 flex space-x-3 items-center">
                    <a href="{{ route('dashboard.pengguna.index', ['posyandu_id' => $posyandu->id]) }}"
                        class="text-indigo-600 hover:text-indigo-900 font-medium text-sm" title="Kelola Operator">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </a>

                    @if(Auth::user()->isSuperAdmin() && $posyandu->village_id !== null)
                        <button type="button" @click="$dispatch('open-modal', 'edit-posyandu-{{ $posyandu->id }}')"
                            class="text-blue-600 hover:text-blue-900 font-medium text-sm" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>

                        <form action="{{ route('dashboard.posyandu.destroy', $posyandu->id) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm transition-transform hover:scale-110" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    @endif

                    @if(Auth::user()->isSuperAdmin() && $posyandu->village_id !== null)
                        {{-- Edit Modal --}}
                        <div x-data="{ show: false }"
                            @open-modal.window="if ($event.detail === 'edit-posyandu-{{ $posyandu->id }}') show = true"
                            @close-modal.window="show = false" x-show="show"
                            class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50"
                            style="display: none;">
                            <div @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                                <div class="flex items-center justify-between p-4 border-b">
                                    <h3 class="text-lg font-semibold text-gray-900">Edit Fasilitas Pelayanan Kesehatan</h3>
                                    <button @click="show = false" class="text-gray-400 hover:text-gray-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <form action="{{ route('dashboard.posyandu.update', $posyandu->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-4 space-y-4">
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Nama Fasilitas
                                                Kesehatan</label>
                                            <input type="text" name="name" value="{{ $posyandu->name }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Desa</label>
                                            <select name="village_id"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                required>
                                                @foreach($villages as $village)
                                                    <option value="{{ $village->id }}" {{ $posyandu->village_id == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex justify-end p-4 border-t space-x-2">
                                        <button type="button" @click="show = false"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                                        <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                    Tidak ada data fasilitas pelayanan kesehatan ditemukan.
                </td>
            </tr>
        @endforelse
    </x-dashboard.table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $posyanduList->links() }}
    </div>

    {{-- Add Modal --}}
    @if(Auth::user()->isSuperAdmin())
        <div x-data="{ show: false }" @open-modal.window="if ($event.detail === 'add-posyandu') show = true"
            @close-modal.window="show = false" x-show="show"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50"
            style="display: none;">
            <div @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Tambah Fasilitas Pelayanan Kesehatan Baru</h3>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('dashboard.posyandu.store') }}" method="POST">
                    @csrf
                    <div class="p-4 space-y-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Nama Fasilitas Kesehatan</label>
                            <input type="text" name="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required placeholder="Contoh: Puskesmas Pembantu Mawar">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Desa</label>
                            <select name="village_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required>
                                <option value="">-- Pilih Desa --</option>
                                @foreach($villages as $village)
                                    <option value="{{ $village->id }}">{{ $village->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end p-4 border-t space-x-2">
                        <button type="button" @click="show = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">Simpan
                            Posyandu</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection