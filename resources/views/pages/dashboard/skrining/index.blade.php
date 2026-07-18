@extends('layouts.dashboard')

@section('title', 'Data Skrining')

@section('content')

    <x-dashboard.page-header 
        title="Data Skrining" 
        subtitle="Kelola dan lihat data riwayat skrining seluruh masyarakat."
        :breadcrumb="['Data Skrining' => null]"
    >
        <x-slot name="action">
            <a href="{{ route('dashboard.skrining.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200">
                <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </a>
        </x-slot>
    </x-dashboard.page-header>

    {{-- Data Table --}}
    <x-dashboard.table>
        <x-slot name="head">
            <th scope="col" class="px-4 py-3">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center group">
                    Tanggal
                    <span class="ml-1 flex flex-col">
                        <svg class="w-2 h-2 {{ request('sort') === 'created_at' && request('direction') === 'asc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a.75.75 0 01.53.22l4 4a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l4-4A.75.75 0 0110 3z"/></svg>
                        <svg class="w-2 h-2 {{ request('sort', 'created_at') === 'created_at' && request('direction', 'desc') === 'desc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }} -mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 17a.75.75 0 01-.53-.22l-4-4a.75.75 0 011.06-1.06L10 15.19l3.47-3.47a.75.75 0 011.06 1.06l-4 4a.75.75 0 01-.53.22z"/></svg>
                    </span>
                </a>
            </th>
            <th scope="col" class="px-4 py-3">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'fullname', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center group whitespace-nowrap">
                    Responden
                    <span class="ml-1 flex flex-col">
                        <svg class="w-2 h-2 {{ request('sort') === 'fullname' && request('direction') === 'asc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a.75.75 0 01.53.22l4 4a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l4-4A.75.75 0 0110 3z"/></svg>
                        <svg class="w-2 h-2 {{ request('sort') === 'fullname' && request('direction', 'desc') === 'desc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }} -mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 17a.75.75 0 01-.53-.22l-4-4a.75.75 0 011.06-1.06L10 15.19l3.47-3.47a.75.75 0 011.06 1.06l-4 4a.75.75 0 01-.53.22z"/></svg>
                    </span>
                </a>
            </th>
            @if(Auth::user()->isSuperAdmin())
            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                Desa / Fasilitas Kesehatan
            </th>
            @endif
            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                Periode Skrining
            </th>
            <th scope="col" class="px-4 py-3">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'dm_severity', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center group whitespace-nowrap">
                    Status DM
                    <span class="ml-1 flex flex-col">
                        <svg class="w-2 h-2 {{ request('sort') === 'dm_severity' && request('direction') === 'asc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a.75.75 0 01.53.22l4 4a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l4-4A.75.75 0 0110 3z"/></svg>
                        <svg class="w-2 h-2 {{ request('sort') === 'dm_severity' && request('direction', 'desc') === 'desc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }} -mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 17a.75.75 0 01-.53-.22l-4-4a.75.75 0 011.06-1.06L10 15.19l3.47-3.47a.75.75 0 011.06 1.06l-4 4a.75.75 0 01-.53.22z"/></svg>
                    </span>
                </a>
            </th>
            <th scope="col" class="px-4 py-3">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'ht_severity', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center group whitespace-nowrap">
                    Status HT
                    <span class="ml-1 flex flex-col">
                        <svg class="w-2 h-2 {{ request('sort') === 'ht_severity' && request('direction') === 'asc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a.75.75 0 01.53.22l4 4a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l4-4A.75.75 0 0110 3z"/></svg>
                        <svg class="w-2 h-2 {{ request('sort') === 'ht_severity' && request('direction', 'desc') === 'desc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }} -mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 17a.75.75 0 01-.53-.22l-4-4a.75.75 0 011.06-1.06L10 15.19l3.47-3.47a.75.75 0 011.06 1.06l-4 4a.75.75 0 01-.53.22z"/></svg>
                    </span>
                </a>
            </th>
            <th scope="col" class="px-4 py-3">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'recommendation_level', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center group whitespace-nowrap">
                    Rekomendasi
                    <span class="ml-1 flex flex-col">
                        <svg class="w-2 h-2 {{ request('sort') === 'recommendation_level' && request('direction') === 'asc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a.75.75 0 01.53.22l4 4a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l4-4A.75.75 0 0110 3z"/></svg>
                        <svg class="w-2 h-2 {{ request('sort') === 'recommendation_level' && request('direction', 'desc') === 'desc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }} -mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 17a.75.75 0 01-.53-.22l-4-4a.75.75 0 011.06-1.06L10 15.19l3.47-3.47a.75.75 0 011.06 1.06l-4 4a.75.75 0 01-.53.22z"/></svg>
                    </span>
                </a>
            </th>
            <th scope="col" class="px-4 py-3">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'action_status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center group whitespace-nowrap">
                    Tindakan
                    <span class="ml-1 flex flex-col">
                        <svg class="w-2 h-2 {{ request('sort') === 'action_status' && request('direction') === 'asc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a.75.75 0 01.53.22l4 4a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l4-4A.75.75 0 0110 3z"/></svg>
                        <svg class="w-2 h-2 {{ request('sort') === 'action_status' && request('direction', 'desc') === 'desc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }} -mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 17a.75.75 0 01-.53-.22l-4-4a.75.75 0 011.06-1.06L10 15.19l3.47-3.47a.75.75 0 011.06 1.06l-4 4a.75.75 0 01-.53.22z"/></svg>
                    </span>
                </a>
            </th>
            <th scope="col" class="px-4 py-3">Aksi</th>
        </x-slot>
        <x-slot name="toolbar">
            <div x-data="{ showFilters: false }" class="w-full flex flex-col space-y-3">
                <div class="flex flex-col md:flex-row md:items-center justify-between space-y-3 md:space-y-0">
                    <form action="{{ route('dashboard.skrining.index') }}" method="GET" class="relative w-full md:w-64">
                        <input type="hidden" name="severity" value="{{ request('severity') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                        
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <div class="flex items-center">
                            <input type="text" name="search" value="{{ request('search') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 shadow-sm" placeholder="Cari NIK/Nama..." onchange="this.form.submit()">
                            <button type="button" @click="showFilters = !showFilters" class="ml-3 md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </x-slot>
        @forelse($screenings as $screening)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 whitespace-nowrap text-gray-500">
                    <div class="font-medium text-gray-900">{{ $screening->created_at->format('d M Y') }}</div>
                    <div class="text-xs">{{ $screening->created_at->format('H:i') }} WITA</div>
                </td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900">{{ $screening->respondent->fullname ?? 'Anonim' }}</div>
                    <div class="text-xs text-gray-500">{{ $screening->respondent->nik ?? '-' }}</div>
                </td>
                @if(Auth::user()->isSuperAdmin())
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900">{{ $screening->respondent->village->name ?? '-' }}</div>
                    <div class="text-xs text-gray-500">{{ $screening->respondent->healthPost->name ?? '-' }}</div>
                </td>
                @endif
                <td class="px-4 py-3 whitespace-nowrap">
                    @if($screening->screeningPeriod)
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $screening->screeningPeriod->name }}
                        </span>
                    @else
                        <span class="text-xs text-gray-400 italic">Tanpa Periode</span>
                    @endif
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <x-dashboard.severity-badge :level="$screening->dm_severity" />
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <x-dashboard.severity-badge :level="$screening->ht_severity" />
                </td>
                <td class="px-4 py-3">
                    @php
                        $recText = match($screening->recommendation_level) {
                            'lifestyle' => '🟢 Jaga Pola Hidup',
                            'monitor' => '🟡 Pantau Berkala',
                            'visit_puskesmas' => '🟠 Rujuk Puskesmas',
                            'emergency' => '🔴 Segera ke UGD/Faskes',
                            default => $screening->recommendation_level
                        };
                    @endphp
                    <span class="text-xs font-medium bg-gray-50 text-gray-800 px-2.5 py-1 rounded-md border border-gray-200">
                        {{ $recText }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <form action="{{ route('dashboard.skrining.updateStatus', $screening->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="action_status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 {{ $screening->action_status == 'unhandled' ? 'text-red-600 font-semibold' : ($screening->action_status == 'in_progress' ? 'text-yellow-600 font-semibold' : 'text-green-600 font-semibold') }}">
                            <option value="unhandled" {{ $screening->action_status == 'unhandled' ? 'selected' : '' }}>Belum Penanganan</option>
                            <option value="in_progress" {{ $screening->action_status == 'in_progress' ? 'selected' : '' }}>Dalam Penanganan</option>
                            <option value="completed" {{ $screening->action_status == 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </form>
                </td>
                <td class="px-4 py-3 flex space-x-3 items-center">
                    <a href="{{ route('dashboard.skrining.show', $screening->id) }}" class="text-blue-600 hover:text-blue-900 font-medium text-sm" title="Detail">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </a>
                    <form action="{{ route('dashboard.skrining.destroy', $screening->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data skrining ini? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                    Tidak ada data skrining ditemukan.
                </td>
            </tr>
        @endforelse
    </x-dashboard.table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $screenings->links() }}
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-dashboard.modal id="confirm-modal" title="Hapus Data Skrining">
        <div class="text-center">
            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <h3 class="mb-5 text-lg font-normal text-gray-500">Apakah Anda yakin ingin menghapus data skrining ini? Tindakan ini tidak dapat dibatalkan.</h3>
            <div class="flex justify-center gap-4">
                <x-dashboard.button variant="danger" data-modal-hide="confirm-modal">
                    Ya, Hapus
                </x-dashboard.button>
                <x-dashboard.button variant="secondary" data-modal-hide="confirm-modal">
                    Batal
                </x-dashboard.button>
            </div>
        </div>
    </x-dashboard.modal>

@endsection
