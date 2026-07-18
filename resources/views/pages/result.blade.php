@extends('layouts.app')

@section('title', 'Hasil Skrining - Sehati')

@php
    // ══════════════════════════════════════════════════════════════
    // SEHATI RULE ENGINE v2 — GATEC (Service Architecture)
    // Guideline-Anchored Tiered Evidence Classification
    //
    // Seluruh logika klinis telah dipindahkan ke:
    // app/Services/Clinical/GatecRuleEngine.php
    //
    // Blade ini hanya bertanggung jawab untuk PRESENTASI UI.
    // ══════════════════════════════════════════════════════════════

    use App\Services\Clinical\GatecRuleEngine;
    use App\Services\Clinical\DTO\PatientData;

    // ─── 1. DATA PASIEN & SCREENING ─────────────────────────────────
    $patient = $screening->respondent;

    // Data Pasien (untuk display)
    $gender = $patient->gender;
    $age = \Carbon\Carbon::parse($patient->birthdate)->age;
    $c_tekanan_sistolik = $screening->c_sistolik;
    $c_tekanan_diastolik = $screening->c_diastolik;
    $c_gula = $screening->c_gula;
    $c_kolesterol_lab = $screening->c_kolesterol;
    $c_asam_urat = $screening->c_asam_urat;
    $c_lingkar_perut = $screening->c_perut;
    $c_merokok = $screening->c_merokok;
    $c_berat = $screening->c_berat;
    $c_tinggi = $screening->c_tinggi;

    // Decode Evidence & Decision
    $decision = json_decode($screening->decision_explanation, true);

    // Hasil Evaluasi DM
    $dmStatus = $screening->dm_status;
    $dmSeverity = $screening->dm_severity;
    $dmMessage = $decision['dm_msg'] ?? '';
    $dmAction = $decision['dm_action'] ?? '';

    // Hasil Evaluasi HT
    $htStatus = $screening->ht_status;
    $htSeverity = $screening->ht_severity;
    $htMessage = $decision['ht_msg'] ?? '';
    $htAction = $decision['ht_action'] ?? '';

    // Flags
    $needsUrgentReferral = $screening->recommendation_level === 'emergency';
    $needsReferral = in_array($screening->recommendation_level, ['emergency', 'visit_puskesmas', 'visit_posyandu']);
    $hasCardiometabolicDouble = ($dmSeverity === 'high' || $dmSeverity === 'critical') && ($htSeverity === 'high' || $htSeverity === 'critical');

    // Clinical Findings
    $findings = $decision['findings'] ?? [];

    // Evidence (untuk display)
    $imtLevel = $decision['evidence']['imtLevel'] ?? 'normal';
    $bpLevel = $decision['evidence']['bpLevel'] ?? 'normal';
    $gdsLevel = $decision['evidence']['gdsLevel'] ?? 'normal';
    $wcLevel = $decision['evidence']['wcLevel'] ?? 'normal';

    // Hitung IMT untuk badge
    $imt = $c_tinggi > 0 ? $c_berat / (($c_tinggi / 100) * ($c_tinggi / 100)) : 0;

    // ─── 2. UI STYLING (Presentasi — bukan business logic) ───────

    $getStatusStyle = function ($status) {
        $normalized = strtolower($status);
        if (str_contains($normalized, 'rendah') || str_contains($normalized, 'low')) {
            return [
                'color' => 'bg-green-50 border-green-200',
                'text' => 'text-green-700',
                'icon' => 'bg-green-100 text-green-600',
                'badge' => 'bg-green-100 text-green-700',
                'label' => '🟢',
            ];
        } elseif (str_contains($normalized, 'sedang') || str_contains($normalized, 'moderate')) {
            return [
                'color' => 'bg-yellow-50 border-yellow-200',
                'text' => 'text-yellow-800',
                'icon' => 'bg-yellow-100 text-yellow-600',
                'badge' => 'bg-yellow-100 text-yellow-800',
                'label' => '🟡',
            ];
        } elseif (str_contains($normalized, 'tinggi') || str_contains($normalized, 'high')) {
            return [
                'color' => 'bg-orange-50 border-orange-200',
                'text' => 'text-orange-700',
                'icon' => 'bg-orange-100 text-orange-600',
                'badge' => 'bg-orange-100 text-orange-700',
                'label' => '🟠',
            ];
        } elseif (str_contains($normalized, 'terdiagnosa') || str_contains($normalized, 'kritis') || str_contains($normalized, 'critical')) {
            return [
                'color' => 'bg-red-50 border-red-200',
                'text' => 'text-red-700',
                'icon' => 'bg-red-100 text-red-600',
                'badge' => 'bg-red-100 text-red-700',
                'label' => '🔴',
            ];
        }

        // Fallback
        return [
            'color' => 'bg-gray-50 border-gray-200',
            'text' => 'text-gray-700',
            'icon' => 'bg-gray-100 text-gray-600',
            'badge' => 'bg-gray-100 text-gray-700',
            'label' => '⚪',
        ];
    };

    $dmStyle = $getStatusStyle($dmStatus);
    $htStyle = $getStatusStyle($htStatus);

    // BP classification label untuk display
    $bpDisplayLabels = [
        'NORMAL' => 'Normal',
        'ELEVATED' => 'Elevated',
        'DERAJAT_1' => 'HT Derajat 1',
        'DERAJAT_2' => 'HT Derajat 2',
        'DERAJAT_3' => 'HT Derajat 3',
        'KRISIS' => 'Krisis HT',
    ];
    $bpDisplayLabel = $bpDisplayLabels[$bpLevel] ?? $bpLevel;
@endphp

@section('content')

    {{-- Navbar Khusus Halaman Hasil --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40 print:hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Sehati</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-32">

        {{-- Header --}}
        <div class="text-center mb-10" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Laporan Hasil Skrining</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">Hasil evaluasi risiko kesehatan Anda berdasarkan Guideline-Anchored
                Tiered Evidence Classification (GATEC)</p>
        </div>

        {{-- URGENT ALERT BANNER — untuk kasus Perlu Pemeriksaan Klinis Segera --}}
        @if($needsUrgentReferral)
            <div class="mb-8 p-5 bg-red-100 border-2 border-red-400 rounded-xl shadow-md animate-pulse print:animate-none"
                data-aos="zoom-in">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-red-800">Perhatian — Pemeriksaan Klinis Segera Direkomendasikan</h3>
                        <p class="text-sm text-red-700 mt-1">Hasil skrining menunjukkan temuan yang memerlukan evaluasi medis
                            sesegera mungkin. Silakan kunjungi Puskesmas atau fasilitas kesehatan terdekat.</p>
                    </div>
                </div>
            </div>
        @endif




        {{-- Main Result Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Kartu Hasil Diabetes --}}
            <div class="p-6 border rounded-xl shadow-sm {{ $dmStyle['color'] }} transition-colors" data-aos="fade-right">
                <div class="flex items-center justify-between mb-4 border-b border-gray-200/50 pb-4">
                    <h3 class="text-xl font-bold text-gray-900">Diabetes Mellitus</h3>
                    <div
                        class="w-auto px-3 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-sm {{ $dmStyle['icon'] }}">
                        {{ $dmStyle['label'] }}
                    </div>
                </div>

                <div class="mb-4">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $dmStyle['badge'] }}">
                        {{ $dmStatus }}
                    </span>
                </div>

                <p class="text-sm font-medium {{ $dmStyle['text'] }} leading-relaxed mb-3">
                    {{ $dmMessage }}
                </p>

                <div class="mt-3 pt-3 border-t border-gray-200/50">
                    <p class="text-xs font-semibold text-gray-600 mb-1">Rekomendasi:</p>
                    <p class="text-xs {{ $dmStyle['text'] }}">{{ $dmAction }}</p>
                </div>
            </div>

            {{-- Kartu Hasil Hipertensi --}}
            <div class="p-6 border rounded-xl shadow-sm {{ $htStyle['color'] }} transition-colors" data-aos="fade-left">
                <div class="flex items-center justify-between mb-4 border-b border-gray-200/50 pb-4">
                    <h3 class="text-xl font-bold text-gray-900">Hipertensi</h3>
                    <div
                        class="w-auto px-3 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-sm {{ $htStyle['icon'] }}">
                        {{ $htStyle['label'] }}
                    </div>
                </div>

                <div class="mb-2">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $htStyle['badge'] }}">
                        {{ $htStatus }}
                    </span>
                </div>

                {{-- BP Classification Badge --}}
                <div class="mb-4">
                    <span class="inline-block px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                        TD: {{ $c_tekanan_sistolik }}/{{ $c_tekanan_diastolik }} mmHg — {{ $bpDisplayLabel }}
                    </span>
                </div>

                <p class="text-sm font-medium {{ $htStyle['text'] }} leading-relaxed mb-3">
                    {{ $htMessage }}
                </p>

                <div class="mt-3 pt-3 border-t border-gray-200/50">
                    <p class="text-xs font-semibold text-gray-600 mb-1">Rekomendasi:</p>
                    <p class="text-xs {{ $htStyle['text'] }}">{{ $htAction }}</p>
                </div>
            </div>
        </div>

        {{-- Ringkasan Temuan Klinis & Rekomendasi --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" data-aos="fade-up">

            {{-- Temuan Klinis (Kiri) --}}
            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Temuan Klinis
                </h4>

                <ul class="space-y-3">
                    @foreach($findings as $finding)
                        @php
                            $findingColors = [
                                'critical' => 'bg-red-50 border-red-200 text-red-800',
                                'high' => 'bg-orange-50 border-orange-200 text-orange-800',
                                'moderate' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                                'info' => 'bg-blue-50 border-blue-200 text-blue-800',
                                'good' => 'bg-green-50 border-green-200 text-green-800',
                            ];
                            $findingColor = $findingColors[$finding['severity']] ?? 'bg-gray-50 border-gray-100 text-gray-800';
                        @endphp
                        <li class="rounded-lg p-3 border {{ $findingColor }}">
                            <span class="block font-semibold text-gray-900 text-sm mb-1">{{ $finding['title'] }}</span>
                            <span class="block text-xs text-gray-500">{{ $finding['desc'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Rekomendasi (Kanan) --}}
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Rekomendasi Tindak Lanjut
                </h4>

                <div class="space-y-4">
                    {{-- Rekomendasi Umum --}}
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 mt-1">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                1</div>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-900">Perbaikan Pola Makan</h5>
                            <p class="text-sm text-gray-500 mt-1">Perbanyak konsumsi sayur dan buah. Batasi asupan gula
                                (maks 4 sendok makan/hari), garam (maks 1 sendok teh/hari), dan lemak/minyak berlebih.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 mt-1">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                2</div>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-900">Aktivitas Fisik Rutin</h5>
                            <p class="text-sm text-gray-500 mt-1">Lakukan aktivitas fisik atau olahraga ringan minimal 30
                                menit setiap hari (contoh: jalan kaki, senam, bersepeda).</p>
                        </div>
                    </div>

                    @if($c_merokok)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 mt-1">
                                <div
                                    class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                                    3</div>
                            </div>
                            <div>
                                <h5 class="font-semibold text-orange-700">Berhenti Merokok</h5>
                                <p class="text-sm text-orange-600 mt-1">Merokok meningkatkan risiko DM (RR 1.44, Willi et al.
                                    2007) dan merupakan faktor risiko kardiovaskular utama. Hentikan kebiasaan merokok sekarang.
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($needsUrgentReferral)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 mt-1">
                                <div
                                    class="w-8 h-8 rounded-full bg-red-200 text-red-700 flex items-center justify-center font-bold animate-pulse">
                                    !</div>
                            </div>
                            <div>
                                <h5 class="font-semibold text-red-700">Pemeriksaan Klinis SEGERA</h5>
                                <p class="text-sm text-red-600 mt-1">Temuan skrining Anda memerlukan evaluasi medis segera.
                                    SEGERA kunjungi Puskesmas, klinik, atau rumah sakit terdekat untuk pemeriksaan lanjutan dan
                                    konfirmasi diagnostik.</p>
                            </div>
                        </div>
                    @elseif($needsReferral)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 mt-1">
                                <div
                                    class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold">
                                    !</div>
                            </div>
                            <div>
                                <h5 class="font-semibold text-red-700">Kunjungan Medis (Prioritas)</h5>
                                <p class="text-sm text-red-600 mt-1">Berdasarkan evaluasi faktor risiko, sangat disarankan untuk
                                    memeriksakan diri ke Puskesmas atau dokter terdekat untuk pemeriksaan lanjutan.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Disclaimer Box --}}
        <div class="p-4 mb-8 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-200 shadow-sm print:border-none print:shadow-none"
            role="alert" data-aos="fade-up">
            <div class="flex items-center mb-2">
                <svg class="w-4 h-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="font-bold">Perhatian Penting</span>
            </div>
            <p>Hasil skrining ini <strong>Bukanlah Diagnosis Medis</strong>, melainkan evaluasi indikasi risiko berbasis
                guideline klinis (PERKENI, ADA, JNC 8, ESH-ESC, WHO). Hanya dokter dan tenaga medis profesional yang berhak
                menegakkan diagnosis penyakit melalui pemeriksaan klinis dan laboratorium.</p>
        </div>

    </div>

    {{-- Action Buttons (Moved outside main container for reliable fixed positioning) --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-md border-t border-gray-200 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.1)] print:hidden"
        style="position: fixed !important;">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @php
                    $source = request()->query('source');
                    $backUrl = $source === 'history' ? route('screening.history') : '/';
                    $backText = $source === 'history' ? 'Kembali ke Riwayat' : 'Kembali ke Beranda';
                @endphp
                <a href="{{ $backUrl }}"
                    class="w-full sm:w-auto text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-6 py-3 text-center inline-flex justify-center items-center transition-colors">
                    {{ $backText }}
                </a>

                @if($needsUrgentReferral || $needsReferral)
                    <a href="/map"
                        class="w-full sm:w-auto text-white {{ $needsUrgentReferral ? 'bg-red-600 hover:bg-red-700 focus:ring-red-300 animate-bounce' : 'bg-red-600 hover:bg-red-700 focus:ring-red-300' }} focus:ring-4 focus:outline-none font-bold rounded-lg text-sm px-6 py-3 text-center inline-flex justify-center items-center transition-colors shadow-lg">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Cari Faskes Terdekat
                    </a>
                @endif

                <button onclick="window.print()"
                    class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-6 py-3 text-center inline-flex justify-center items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Laporan
                </button>
            </div>
        </div>
    </div>

@endsection