<?php

namespace App\Services\Clinical\DTO;

/**
 * Immutable DTO: Hasil evidence collection.
 * Merepresentasikan klasifikasi klinis dari setiap variabel input.
 */
final readonly class ClinicalEvidence
{
    public function __construct(
        // Antropometri
        public float  $imt,
        public string $imtLevel,       // UNDERWEIGHT | NORMAL | OVERWEIGHT | OBESE_I | OBESE_II

        // Tekanan Darah
        public string $bpLevel,        // NORMAL | ELEVATED | DERAJAT_1 | DERAJAT_2 | DERAJAT_3 | KRISIS

        // Gula Darah Sewaktu
        public string $gdsLevel,       // TIDAK_DIPERIKSA | NORMAL | PERLU_EVALUASI | WASPADA | CURIGA_DM

        // Lingkar Perut
        public string $wcLevel,        // NORMAL | OBESITAS_SENTRAL

        // Kolesterol
        public string $cholLevel,      // TIDAK_DIPERIKSA | NORMAL | BATAS_TINGGI | TINGGI

        // Asam Urat
        public string $uaLevel,        // TIDAK_DIPERIKSA | NORMAL | TINGGI

        // Usia
        public string $ageRisk,        // USIA_MUDA | USIA_RISIKO

        // Riwayat Keluarga Kardiovaskular
        public string $familialCvd,    // NONE | PRESENT | STRONG

        // Komorbiditas
        public bool $personalCvd,       // b_jantung || b_stroke
        public bool $personalMetabolic, // b_kolesterol

        // Pre-screening flags
        public bool $dmAlreadyDiagnosed,
        public bool $htAlreadyDiagnosed,
    ) {}
}
