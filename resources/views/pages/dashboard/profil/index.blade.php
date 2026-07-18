@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')

    <x-dashboard.page-header 
        title="Profil Pengguna" 
        subtitle="Kelola informasi pribadi dan pengaturan akun Anda."
        :breadcrumb="['Profil' => null]"
    />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 text-center">
                <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-3xl mx-auto mb-4">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>
                <x-dashboard.badge color="blue">{{ $user->role === 'super_admin' ? 'Super Admin' : ($user->role === 'owner' ? 'Owner' : 'Admin Fasilitas Kesehatan') }}</x-dashboard.badge>
                @if($user->healthPost)
                    <p class="text-xs text-gray-500 mt-2">Fasilitas Kesehatan: {{ $user->healthPost->name }}</p>
                @endif
            </div>
        </div>
        
        <div class="md:col-span-2">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Informasi Akun</h4>
                <p class="mt-1 text-sm text-gray-600">Perbarui informasi profil dan kata sandi Anda di sini.</p>
                <form action="{{ route('dashboard.profil.update') }}" method="POST" x-data="{ showOld: false, showNew: false }">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Ubah Password <span class="text-sm text-gray-500 font-normal">(Opsional)</span></h4>
                    <div class="grid grid-cols-1 gap-4 mb-6">

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Password Baru</label>
                            <div class="relative">
                                <input :type="showNew ? 'text' : 'password'" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10">
                                <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none">
                                    <svg x-show="!showNew" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showNew" style="display: none;" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
