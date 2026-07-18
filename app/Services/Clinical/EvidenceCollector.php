<?php

namespace App\Services\Clinical;

use App\Services\Clinical\DTO\PatientData;
use App\Services\Clinical\DTO\ClinicalEvidence;

/**
 * Evidence Collector — Mengubah raw input menjadi level/klasifikasi klinis.
 *
 * Setiap method classify*() merepresentasikan satu cut-off guideline.
 * Logika 100% identik dengan result.blade.php baris 52–151.
 */
class EvidenceCollector
{
    /**
     * Mengumpulkan seluruh evidence klinis dari data pasien.
     */
    public function collect(PatientData $patient): ClinicalEvidence
    {
        $imt = $this->calculateImt($patient->berat, $patient->tinggi);

        return new ClinicalEvidence(
            imt: $imt,
            imtLevel: $this->classifyImt($imt),
            bpLevel: $this->classifyBp($patient->sistolik, $patient->diastolik),
            gdsLevel: $this->classifyGds($patient->gula),
            wcLevel: $this->classifyWaistCircumference($patient->gender, $patient->lingkarPerut),
            cholLevel: $this->classifyChol($patient->kolesterolLab),
            uaLevel: $this->classifyUricAcid($patient->gender, $patient->asamUrat),
            ageRisk: $this->classifyAge($patient->age),
            familialCvd: $this->classifyFamilialCvd($patient->a_jantung, $patient->a_stroke, $patient->a_kolesterol),
            personalCvd: $patient->b_jantung || $patient->b_stroke,
            personalMetabolic: $patient->b_kolesterol,
            dmAlreadyDiagnosed: $patient->b_diabetes,
            htAlreadyDiagnosed: $patient->b_hipertensi,
        );
    }

    // ─── IMT — WHO Asia-Pacific Classification 2004 ──────────────

    public function calculateImt(float $berat, float $tinggi): float
    {
        if ($tinggi <= 0 || $berat <= 0) {
            return 0;
        }
        $tinggiM = $tinggi / 100;
        return $berat / ($tinggiM * $tinggiM);
    }

    public function classifyImt(float $imt): string
    {
        if ($imt >= 30) {
            return 'OBESE_II';
        } elseif ($imt >= 25) {
            return 'OBESE_I';
        } elseif ($imt >= 23) {
            return 'OVERWEIGHT';
        } elseif ($imt >= 18.5) {
            return 'NORMAL';
        }
        return 'UNDERWEIGHT';
    }

    // ─── Tekanan Darah — JNC 8 / ESH-ESC 2023 / ACC-AHA 2017 ────

    public function classifyBp(float $sistolik, float $diastolik): string
    {
        if ($sistolik >= 180 || $diastolik >= 120) {
            return 'KRISIS';
        } elseif ($sistolik >= 160 || $diastolik >= 100) {
            return 'DERAJAT_3';
        } elseif ($sistolik >= 140 || $diastolik >= 90) {
            return 'DERAJAT_2';
        } elseif ($sistolik >= 130 || $diastolik >= 80) {
            return 'DERAJAT_1';
        } elseif ($sistolik >= 120 && $diastolik < 80) {
            return 'ELEVATED';
        }
        return 'NORMAL';
    }

    // ─── Gula Darah Sewaktu — PERKENI 2021 ───────────────────────

    public function classifyGds(?float $gula): string
    {
        if ($gula === null) {
            return 'TIDAK_DIPERIKSA';
        } elseif ($gula >= 200) {
            return 'CURIGA_DM';
        } elseif ($gula >= 140) {
            return 'WASPADA';
        } elseif ($gula >= 100) {
            return 'PERLU_EVALUASI';
        }
        return 'NORMAL';
    }

    // ─── Lingkar Perut — IDF/Asia-Pasifik (gender-specific) ──────

    public function classifyWaistCircumference(string $gender, float $lingkarPerut): string
    {
        if ($gender === 'L' && $lingkarPerut >= 90) {
            return 'OBESITAS_SENTRAL';
        } elseif ($gender === 'P' && $lingkarPerut >= 80) {
            return 'OBESITAS_SENTRAL';
        } elseif ($gender === '' && $lingkarPerut > 90) {
            // Fallback jika gender tidak tersedia — gunakan cut-off konservatif
            return 'OBESITAS_SENTRAL';
        }
        return 'NORMAL';
    }

    // ─── Kolesterol — NCEP ATP III ───────────────────────────────

    public function classifyChol(?float $kolesterol): string
    {
        if ($kolesterol === null) {
            return 'TIDAK_DIPERIKSA';
        } elseif ($kolesterol >= 240) {
            return 'TINGGI';
        } elseif ($kolesterol >= 200) {
            return 'BATAS_TINGGI';
        }
        return 'NORMAL';
    }

    // ─── Asam Urat (gender-specific) ─────────────────────────────

    public function classifyUricAcid(string $gender, ?float $asamUrat): string
    {
        if ($asamUrat === null) {
            return 'TIDAK_DIPERIKSA';
        } elseif (($gender === 'L' && $asamUrat > 7.0) || ($gender === 'P' && $asamUrat > 6.0)) {
            return 'TINGGI';
        } elseif ($gender === '' && $asamUrat > 7.0) {
            return 'TINGGI';
        }
        return 'NORMAL';
    }

    // ─── Usia — PERKENI 2021, ESH-ESC 2023 ──────────────────────

    public function classifyAge(int $age): string
    {
        return ($age >= 45) ? 'USIA_RISIKO' : 'USIA_MUDA';
    }

    // ─── Riwayat Keluarga — Predisposisi Kardiovaskular ──────────

    public function classifyFamilialCvd(bool $aJantung, bool $aStroke, bool $aKolesterol): string
    {
        $count = (int) $aJantung + (int) $aStroke + (int) $aKolesterol;

        if ($count >= 2) {
            return 'STRONG';
        } elseif ($count >= 1) {
            return 'PRESENT';
        }
        return 'NONE';
    }
}
