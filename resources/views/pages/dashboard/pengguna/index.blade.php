@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')

@section('content')

    @php
        $breadcrumbTitle = $posyandu ? 'Kelola Operator ' . $posyandu->name : 'Kelola Operator';
    @endphp
    <x-dashboard.page-header 
        title="Manajemen Operator" 
        subtitle="Kelola akses Admin Fasilitas Kesehatan."
        :breadcrumb="['Data Fasilitas Pelayanan Kesehatan' => route('dashboard.posyandu.index'), $breadcrumbTitle => null]"
    >
        <x-slot name="action">
            <div class="flex gap-2">
                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdminPosyandu())
                <x-dashboard.button variant="primary" x-data="" @click.prevent="$dispatch('open-modal', 'add-pengguna')">
                    + Tambah Pengguna
                </x-dashboard.button>
                @endif
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

    {{-- Toolbar --}}
    <div class="flex items-center justify-end mb-4">
        <form action="{{ route('dashboard.pengguna.index') }}" method="GET" class="relative w-full md:w-64">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 shadow-sm" placeholder="Cari Nama atau Email..." onchange="this.form.submit()">
        </form>
    </div>

    {{-- Data Table --}}
    <x-dashboard.table :headers="['Nama Lengkap', 'Email', 'Role', 'Aksi']">
        @forelse($users as $usr)
            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-150">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $usr->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $usr->email }}</td>
                <td class="px-4 py-3">
                    @if($usr->isSuperAdmin())
                        <x-dashboard.badge color="blue">Super Admin</x-dashboard.badge>
                    @else
                        <x-dashboard.badge color="indigo">Admin Fasilitas Kesehatan</x-dashboard.badge>
                        <div class="text-xs text-gray-500 mt-1">{{ $usr->healthPost->name ?? '-' }}</div>
                    @endif
                </td>

                <td class="px-4 py-3 flex space-x-3 items-center">
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdminPosyandu())
                        <button type="button" @click="$dispatch('open-modal', 'edit-pengguna-{{ $usr->id }}')" class="text-blue-600 hover:text-blue-900 font-medium text-sm" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </button>
                        @if($usr->id !== Auth::id())
                            <form action="{{ route('dashboard.pengguna.destroy', $usr->id) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm transition-transform hover:scale-110" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        @endif

                        {{-- Edit Modal --}}
                        <div x-data="{ show: false }" @open-modal.window="if ($event.detail === 'edit-pengguna-{{ $usr->id }}') show = true" @close-modal.window="show = false" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
                            <div @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                                <div class="flex items-center justify-between p-4 border-b">
                                    <h3 class="text-lg font-semibold text-gray-900">Edit Pengguna</h3>
                                    <button @click="show = false" type="button" class="text-gray-400 hover:text-gray-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                                <form action="{{ route('dashboard.pengguna.update', $usr->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-4 space-y-4">
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                                            <input type="text" name="name" value="{{ $usr->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                            <input type="email" name="email" value="{{ $usr->email }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Password (Kosongkan jika tidak diubah)</label>
                                            <input type="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        </div>
                                        @if(isset($posyandu))
                                            <input type="hidden" name="role" value="{{ $usr->role }}">
                                            <input type="hidden" name="health_post_id" value="{{ $usr->health_post_id }}">
                                            
                                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                                <p class="text-sm text-gray-600 mb-1">Fasilitas Kesehatan</p>
                                                <p class="font-medium text-gray-900">{{ $usr->healthPost->name ?? '-' }}</p>
                                                <p class="text-sm text-gray-600 mt-3 mb-1">Role Operator</p>
                                                <p class="font-medium text-gray-900">{{ $usr->role === 'super_admin' ? 'Super Admin Puskesmas' : 'Admin Fasilitas Kesehatan' }}</p>
                                            </div>
                                        @elseif(Auth::user()->isOwner())
                                            <div x-data="{ role: '{{ $usr->role }}' }">
                                                <label class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                                <select name="role" x-model="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mb-4" required>
                                                    <option value="admin_posyandu">Admin Fasilitas Kesehatan</option>
                                                    <option value="super_admin">Super Admin Puskesmas</option>
                                                </select>
                                                
                                                <div x-show="role === 'admin_posyandu' || role === 'super_admin'">
                                                    <label class="block mb-2 text-sm font-medium text-gray-900">Fasilitas Kesehatan</label>
                                                    <select name="health_post_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                        <option value="">Pilih Fasilitas Kesehatan</option>
                                                        @foreach($posyandus as $p)
                                                            <option value="{{ $p->id }}" {{ $usr->health_post_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="role" value="admin_posyandu">
                                            <input type="hidden" name="health_post_id" value="{{ $usr->health_post_id }}">
                                        @endif
                                    </div>
                                    <div class="flex justify-end p-4 border-t space-x-2">
                                        <button type="button" @click="show = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <span class="text-gray-400 text-xs">-</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                    Tidak ada data pengguna ditemukan.
                </td>
            </tr>
        @endforelse
    </x-dashboard.table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    {{-- Add Modal --}}
    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdminPosyandu())
    <div x-data="{ show: false }" @open-modal.window="if ($event.detail === 'add-pengguna') show = true" @close-modal.window="show = false" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
        <div @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Pengguna Baru</h3>
                <button @click="show = false" type="button" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.pengguna.store') }}" method="POST">
                @csrf
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                        <input type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                        <input type="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    @if(isset($posyandu))
                        <input type="hidden" name="role" value="{{ $posyandu->village_id === null ? 'super_admin' : 'admin_posyandu' }}">
                        <input type="hidden" name="health_post_id" value="{{ $posyandu->id }}">
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-600 mb-1">Fasilitas Kesehatan</p>
                            <p class="font-medium text-gray-900">{{ $posyandu->name }}</p>
                            <p class="text-sm text-gray-600 mt-3 mb-1">Role Operator</p>
                            <p class="font-medium text-gray-900">{{ $posyandu->village_id === null ? 'Super Admin Puskesmas' : 'Admin Fasilitas Kesehatan' }}</p>
                        </div>
                    @elseif(Auth::user()->isOwner())
                        <div x-data="{ role: 'admin_posyandu' }">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                            <select name="role" x-model="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mb-4" required>
                                <option value="admin_posyandu">Admin Fasilitas Kesehatan</option>
                                <option value="super_admin">Super Admin Puskesmas</option>
                            </select>
                            
                            <div x-show="role === 'admin_posyandu' || role === 'super_admin'">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Fasilitas Kesehatan</label>
                                <select name="health_post_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="">Pilih Fasilitas Kesehatan</option>
                                    @foreach($posyandus as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="role" value="admin_posyandu">
                        @if(Auth::user()->isAdminPosyandu())
                            <input type="hidden" name="health_post_id" value="{{ Auth::user()->health_post_id }}">
                        @endif
                    @endif
                </div>
                <div class="flex justify-end p-4 border-t space-x-2">
                    <button type="button" @click="show = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
    @endif

@endsection
