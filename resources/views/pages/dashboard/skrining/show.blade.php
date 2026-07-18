@extends('layouts.dashboard')

@section('title', 'Detail Skrining')

@section('content')

    <x-dashboard.page-header 
        title="Detail Skrining" 
        subtitle="Melihat hasil kalkulasi dari Rule Engine GATEC."
        :breadcrumb="['Hasil Skrining' => route('dashboard.skrining.index'), 'Detail' => null]"
    >
        <x-slot name="action">
            <x-dashboard.button variant="secondary" href="{{ route('dashboard.skrining.index') }}">
                Kembali
            </x-dashboard.button>
        </x-slot>
    </x-dashboard.page-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Profil Responden --}}
        <div class="md:col-span-1">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Responden</h3>
                <div class="space-y-3">
                    <div>
                        <div class="text-xs text-gray-500">Nama Lengkap</div>
                        <div class="font-medium text-gray-900">{{ $screening->respondent->fullname ?? 'Anonim' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">NIK</div>
                        <div class="font-medium text-gray-900">{{ $screening->respondent->nik ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Tanggal Skrining</div>
                        <div class="font-medium text-gray-900">{{ $screening->created_at->format('d M Y, H:i') }} WIB</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Klinis</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 p-2 rounded border border-gray-100">
                        <div class="text-xs text-gray-500">Tekanan Darah</div>
                        <div class="font-medium">{{ $screening->c_sistolik }}/{{ $screening->c_diastolik }} mmHg</div>
                    </div>
                    <div class="bg-gray-50 p-2 rounded border border-gray-100">
                        <div class="text-xs text-gray-500">Gula Darah</div>
                        <div class="font-medium">{{ $screening->c_gula }} mg/dL</div>
                    </div>
                    <div class="bg-gray-50 p-2 rounded border border-gray-100">
                        <div class="text-xs text-gray-500">Kolesterol</div>
                        <div class="font-medium">{{ $screening->c_kolesterol }} mg/dL</div>
                    </div>
                    <div class="bg-gray-50 p-2 rounded border border-gray-100">
                        <div class="text-xs text-gray-500">Asam Urat</div>
                        <div class="font-medium">{{ $screening->c_asam_urat }} mg/dL</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hasil Skrining --}}
        <div class="md:col-span-2">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Hasil Analisis GATEC v{{ $screening->engine_version }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-gray-900">Diabetes Mellitus</span>
                            <x-dashboard.severity-badge :level="$screening->dm_severity" />
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $decision['dm_msg'] ?? '' }}</p>
                        <div class="text-sm bg-blue-50 text-blue-800 p-3 rounded">
                            <span class="font-semibold block mb-1">Tindakan:</span>
                            {{ $decision['dm_action'] ?? '-' }}
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-gray-900">Hipertensi</span>
                            <x-dashboard.severity-badge :level="$screening->ht_severity" />
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $decision['ht_msg'] ?? '' }}</p>
                        <div class="text-sm bg-blue-50 text-blue-800 p-3 rounded">
                            <span class="font-semibold block mb-1">Tindakan:</span>
                            {{ $decision['ht_action'] ?? '-' }}
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Temuan Klinis Tambahan</h4>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        @forelse($decision['findings'] ?? [] as $finding)
                            <li>
                                @if(is_array($finding))
                                    <strong>{{ $finding['title'] ?? '' }}</strong>: {{ $finding['desc'] ?? '' }}
                                @else
                                    {{ $finding }}
                                @endif
                            </li>
                        @empty
                            <li>Tidak ada temuan tambahan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
