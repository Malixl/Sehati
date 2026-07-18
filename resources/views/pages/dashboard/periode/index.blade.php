@extends('layouts.dashboard')

@section('title', 'Manajemen Periode')

@section('content')

    <x-dashboard.page-header title="Manajemen Periode Skrining"
        subtitle="Kelola jadwal dan gelombang skrining untuk perhitungan hasil capaian." :breadcrumb="['Manajemen Periode' => null]">
        <x-slot name="action">
            <x-dashboard.button variant="primary" x-data="" @click.prevent="$dispatch('open-modal', 'add-period')">
                + Tambah Periode
            </x-dashboard.button>
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

    {{-- Data Table --}}
    <x-dashboard.table :headers="['Nama Periode', 'Tanggal Mulai', 'Tanggal Selesai', 'Status', 'Dibuat Oleh', 'Aksi']">
        @forelse($periods as $period)
            <tr class="border-b border-gray-200 hover:bg-white hover:shadow-md transition-all duration-200">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $period->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }}</td>
                <td class="px-4 py-3 text-gray-500">{{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }}</td>
                <td class="px-4 py-3">
                    @if($period->is_active)
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Aktif/Buka</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-400">Tutup</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($period->creator)
                        <div class="font-medium text-gray-900">{{ $period->creator->name }}</div>
                        <div class="text-xs text-gray-500">{{ $period->creator->email }}</div>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>

                <td class="px-4 py-3 flex space-x-3 items-center">
                    <form action="{{ route('dashboard.periode.toggle', $period->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="{{ $period->is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }} font-medium text-sm" title="{{ $period->is_active ? 'Tutup Periode' : 'Aktifkan Periode' }}">
                            @if($period->is_active)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                        </button>
                    </form>
                    
                    <button type="button" @click="$dispatch('open-modal', 'edit-period-{{ $period->id }}')" class="text-blue-600 hover:text-blue-900 font-medium text-sm" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    </button>
                    
                    <form action="{{ route('dashboard.periode.destroy', $period->id) }}" method="POST" class="inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm transition-transform hover:scale-110" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>

                    {{-- Edit Modal --}}
                    <div x-data="{ show: false }" @open-modal.window="if ($event.detail === 'edit-period-{{ $period->id }}') show = true" @close-modal.window="show = false" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
                        <div @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                            <div class="flex items-center justify-between p-4 border-b">
                                <h3 class="text-lg font-semibold text-gray-900">Edit Periode Skrining</h3>
                                <button @click="show = false" class="text-gray-400 hover:text-gray-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            <form action="{{ route('dashboard.periode.update', $period->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="p-4 space-y-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-900">Nama Periode</label>
                                        <input type="text" name="name" value="{{ $period->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal Mulai</label>
                                        <input type="date" name="start_date" value="{{ \Carbon\Carbon::parse($period->start_date)->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal Selesai</label>
                                        <input type="date" name="end_date" value="{{ \Carbon\Carbon::parse($period->end_date)->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                </div>
                                <div class="flex justify-end p-4 border-t space-x-2">
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
                    Tidak ada periode skrining ditemukan. Silakan tambahkan periode baru.
                </td>
            </tr>
        @endforelse
    </x-dashboard.table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $periods->links() }}
    </div>

    {{-- Add Modal --}}
    <div x-data="{ show: false }" @open-modal.window="if ($event.detail === 'add-period') show = true" @close-modal.window="show = false" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
        <div @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Periode Skrining Baru</h3>
                <button @click="show = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.periode.store') }}" method="POST">
                @csrf
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Nama Periode</label>
                        <input type="text" name="name" placeholder="Misal: Gelombang 1 2026" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </div>
                <div class="flex justify-end p-4 border-t space-x-2">
                    <button type="button" @click="show = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">Simpan Periode</button>
                </div>
            </form>
        </div>
    </div>

@endsection
