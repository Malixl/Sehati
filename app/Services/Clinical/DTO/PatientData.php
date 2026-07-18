<?php

namespace App\Services\Clinical\DTO;

use Illuminate\Http\Request;

/**
 * Immutable Data Transfer Object untuk data pasien/responden.
 * Merepresentasikan seluruh input dari form kuesioner.
 */
final readonly class PatientData
{
    public function __construct(
        // Demografi
        public string $gender,
        public int    $age,

        // Bagian A — Riwayat PTM Keluarga
        public bool $a_diabetes,
        public bool $a_hipertensi,
        public bool $a_jantung,
        public bool $a_stroke,
        public bool $a_kolesterol,

        // Bagian B — Riwayat PTM Diri Sendiri
        public bool $b_diabetes,
        public bool $b_hipertensi,
        public bool $b_jantung,
        public bool $b_stroke,
        public bool $b_kolesterol,

        // Bagian C — Pemeriksaan Kesehatan
        public float  $sistolik,
        public float  $diastolik,
        public float  $tinggi,
        public float  $berat,
        public float  $lingkarPerut,
        public ?float $gula,
        public ?float $kolesterolLab,
        public ?float $asamUrat,
        public bool   $merokok,
    ) {}

    /**
     * Factory method: buat PatientData dari HTTP Request.
     * Mapping identik dengan input collection di result.blade.php baris 20–50.
     */
    public static function fromRequest(Request $request): self
    {
        $gula = $request->input('c_gula');
        $kolesterol = $request->input('c_kolesterol_lab');
        $asamUrat = $request->input('c_asam_urat');

        return new self(
            gender: $request->input('gender', ''),
            age: (int) $request->input('age', 0),

            a_diabetes: (bool) (int) $request->input('a_diabetes', 0),
            a_hipertensi: (bool) (int) $request->input('a_hipertensi', 0),
            a_jantung: (bool) (int) $request->input('a_jantung', 0),
            a_stroke: (bool) (int) $request->input('a_stroke', 0),
            a_kolesterol: (bool) (int) $request->input('a_kolesterol', 0),

            b_diabetes: (bool) (int) $request->input('b_diabetes', 0),
            b_hipertensi: (bool) (int) $request->input('b_hipertensi', 0),
            b_jantung: (bool) (int) $request->input('b_jantung', 0),
            b_stroke: (bool) (int) $request->input('b_stroke', 0),
            b_kolesterol: (bool) (int) $request->input('b_kolesterol', 0),

            sistolik: (float) $request->input('c_tekanan_sistolik', 120),
            diastolik: (float) $request->input('c_tekanan_diastolik', 80),
            tinggi: (float) $request->input('c_tinggi', 160),
            berat: (float) $request->input('c_berat', 60),
            lingkarPerut: (float) $request->input('c_lingkar_perut', 80),
            gula: ($gula === null || $gula === '') ? null : (float) $gula,
            kolesterolLab: ($kolesterol === null || $kolesterol === '') ? null : (float) $kolesterol,
            asamUrat: ($asamUrat === null || $asamUrat === '') ? null : (float) $asamUrat,
            merokok: (bool) (int) $request->input('c_merokok', 0),
        );
    }
}
