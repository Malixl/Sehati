@extends('layouts.dashboard')

@section('title', 'Detail Responden')

@section('content')

    <x-dashboard.page-header 
        title="Detail Responden" 
        subtitle="Melihat riwayat skrining dan profil lengkap dari responden."
        :breadcrumb="['Data Responden' => route('dashboard.responden.index'), 'Detail' => null]"
    >
        <x-slot name="action">
            <x-dashboard.button variant="secondary" href="{{ route('dashboard.responden.index') }}">
                Kembali
            </x-dashboard.button>
        </x-slot>
    </x-dashboard.page-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Profil Card --}}
        <div class="md:col-span-1">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl font-bold uppercase">
                        {{ substr($respondent->fullname, 0, 2) }}
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">{{ $respondent->fullname }}</h4>
                        <p class="text-sm text-gray-500">NIK: {{ $respondent->nik ?? '-' }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1">Jenis Kelamin</div>
                        <div class="font-medium text-gray-900">{{ $respondent->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1">Umur</div>
                        <div class="font-medium text-gray-900">
                            {{ $respondent->birthdate ? \Carbon\Carbon::parse($respondent->birthdate)->age . ' Tahun' : '-' }}
                        </div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1">Alamat</div>
                        <div class="font-medium text-gray-900">{{ $respondent->address ?? '-' }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1">Fasilitas Kesehatan</div>
                        <div class="font-medium text-gray-900">{{ $respondent->healthPost->name ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Skrining --}}
        <div class="md:col-span-2">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Skrining</h3>
                
                @if($respondent->screenings->count() > 0)
                    <div class="space-y-4">
                        @foreach($respondent->screenings as $screening)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm text-gray-500">{{ $screening->created_at->format('d M Y, H:i') }} WIB</span>
                                    <x-dashboard.badge color="blue">GATEC v{{ $screening->engine_version }}</x-dashboard.badge>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="bg-white p-3 rounded border border-gray-100 shadow-sm">
                                        <div class="text-xs text-gray-500 mb-1">Risiko Diabetes</div>
                                        <x-dashboard.severity-badge :level="$screening->dm_severity" />
                                    </div>
                                    <div class="bg-white p-3 rounded border border-gray-100 shadow-sm">
                                        <div class="text-xs text-gray-500 mb-1">Risiko Hipertensi</div>
                                        <x-dashboard.severity-badge :level="$screening->ht_severity" />
                                    </div>
                                </div>
                                <div class="text-right">
                                    <x-dashboard.button variant="primary" size="sm" href="{{ route('dashboard.skrining.show', $screening->id) }}">
                                        Detail Skrining
                                    </x-dashboard.button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-dashboard.empty-state 
                        title="Belum Ada Riwayat" 
                        message="Responden ini belum pernah melakukan skrining." 
                    />
                @endif
            </div>
        </div>
    </div>

@endsection
