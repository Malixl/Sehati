<?php

namespace App\Services\Clinical;

use App\Services\Clinical\DTO\PatientData;
use App\Services\Clinical\DTO\ClinicalEvidence;
use App\Services\Clinical\DTO\EvaluationResult;
use App\Services\Clinical\DTO\DecisionExplanation;

/**
 * Diabetes Mellitus Evaluator — Decision Matrix DM.
 *
 * Logika 100% identik dengan result.blade.php baris 160–246.
 * Referensi utama: PERKENI 2021, ADA 2024.
 */
class DiabetesEvaluator
{
    /**
     * Evaluasi risiko Diabetes Mellitus.
     */
    public function evaluate(PatientData $patient, ClinicalEvidence $evidence): EvaluationResult
    {
        $explanations = [];

        // ─── Pre-screening: Sudah Terdiagnosis ──────────────────
        if ($evidence->dmAlreadyDiagnosed) {
            $explanations[] = new DecisionExplanation(
                factor: 'Sudah didiagnosis DM oleh tenaga medis',
                value: 'Ya',
                tier: 'Pre-screening',
                guideline: 'PERKENI 2021',
                evidence: 'Definitive',
                triggered: true,
            );

            return new EvaluationResult(
                status: 'Sudah Terdiagnosis',
                severity: 'diagnosed',
                message: 'Anda sudah pernah didiagnosis Diabetes Mellitus oleh tenaga medis. Skrining risiko tidak berlaku — yang diperlukan adalah pemantauan dan pengelolaan berkelanjutan.',
                action: 'Lanjutkan kontrol rutin ke fasilitas kesehatan. Pantau gula darah secara berkala.',
                explanations: $explanations,
            );
        }

        // ─── Tier Counting ──────────────────────────────────────

        // Tier 1 — Direct Indicator
        $dmTier1 = ($evidence->gdsLevel === 'CURIGA_DM') ? 1 : 0;

        $explanations[] = new DecisionExplanation(
            factor: 'GDS ≥200 mg/dL (Curiga DM)',
            value: $patient->gula !== null ? number_format($patient->gula, 0) : 'N/A',
            tier: 'Tier 1',
            guideline: 'PERKENI 2021',
            evidence: 'Strong',
            triggered: $dmTier1 >= 1,
        );

        // Tier 2 — Major Risk Factors
        $dmTier2 = 0;
        if ($patient->a_diabetes)
            $dmTier2++;
        if (in_array($evidence->imtLevel, ['OBESE_I', 'OBESE_II']))
            $dmTier2++;
        if ($evidence->wcLevel === 'OBESITAS_SENTRAL')
            $dmTier2++;
        if ($evidence->gdsLevel === 'WASPADA')
            $dmTier2++;

        $explanations[] = new DecisionExplanation(
            factor: 'Riwayat keluarga DM',
            value: $patient->a_diabetes ? 'Ya' : 'Tidak',
            tier: 'Tier 2',
            guideline: 'PERKENI 2021',
            evidence: 'Strong',
            triggered: $patient->a_diabetes,
        );
        $explanations[] = new DecisionExplanation(
            factor: 'IMT Obesitas (≥25 Asia-Pacific)',
            value: number_format($evidence->imt, 1),
            tier: 'Tier 2',
            guideline: 'WHO Asia-Pacific 2004',
            evidence: 'Strong',
            triggered: in_array($evidence->imtLevel, ['OBESE_I', 'OBESE_II']),
        );
        $explanations[] = new DecisionExplanation(
            factor: 'Obesitas sentral (lingkar perut)',
            value: number_format($patient->lingkarPerut, 0) . ' cm',
            tier: 'Tier 2',
            guideline: 'IDF 2005',
            evidence: 'Strong',
            triggered: $evidence->wcLevel === 'OBESITAS_SENTRAL',
        );
        $explanations[] = new DecisionExplanation(
            factor: 'GDS Waspada (140–199 mg/dL)',
            value: $patient->gula !== null ? number_format($patient->gula, 0) : 'N/A',
            tier: 'Tier 2',
            guideline: 'PERKENI 2021',
            evidence: 'Strong',
            triggered: $evidence->gdsLevel === 'WASPADA',
        );

        // Tier 3 — Moderate Risk Factors
        $dmTier3 = 0;
        if ($evidence->imtLevel === 'OVERWEIGHT')
            $dmTier3++;
        if ($evidence->ageRisk === 'USIA_RISIKO')
            $dmTier3++;
        if ($patient->merokok)
            $dmTier3++;
        if ($evidence->gdsLevel === 'PERLU_EVALUASI')
            $dmTier3++;
        if ($evidence->familialCvd === 'STRONG')
            $dmTier3++; // Escalated dari Tier 4

        $explanations[] = new DecisionExplanation(
            factor: 'IMT Overweight (23–24.9)',
            value: number_format($evidence->imt, 1),
            tier: 'Tier 3',
            guideline: 'WHO Asia-Pacific 2004',
            evidence: 'Moderate',
            triggered: $evidence->imtLevel === 'OVERWEIGHT',
        );
        $explanations[] = new DecisionExplanation(
            factor: 'Usia ≥45 tahun',
            value: (string) $patient->age,
            tier: 'Tier 3',
            guideline: 'PERKENI 2021',
            evidence: 'Moderate',
            triggered: $evidence->ageRisk === 'USIA_RISIKO',
        );
        $explanations[] = new DecisionExplanation(
            factor: 'Perokok aktif',
            value: $patient->merokok ? 'Ya' : 'Tidak',
            tier: 'Tier 3',
            guideline: 'WHO HEARTS 2018',
            evidence: 'Moderate',
            triggered: $patient->merokok,
        );
        $explanations[] = new DecisionExplanation(
            factor: 'GDS Perlu Evaluasi (100–139 mg/dL)',
            value: $patient->gula !== null ? number_format($patient->gula, 0) : 'N/A',
            tier: 'Tier 3',
            guideline: 'ADA 2024',
            evidence: 'Moderate',
            triggered: $evidence->gdsLevel === 'PERLU_EVALUASI',
        );
        $explanations[] = new DecisionExplanation(
            factor: 'Predisposisi kardiovaskular familial kuat (≥2 CVD)',
            value: $evidence->familialCvd,
            tier: 'Tier 3 (Escalated)',
            guideline: 'ACC-AHA 2017',
            evidence: 'Moderate',
            triggered: $evidence->familialCvd === 'STRONG',
        );

        // Tier 4 — Supporting Factors
        $dmTier4 = 0;
        if ($evidence->personalMetabolic)
            $dmTier4++;
        if (in_array($evidence->cholLevel, ['TINGGI', 'BATAS_TINGGI']))
            $dmTier4++;
        if ($evidence->uaLevel === 'TINGGI')
            $dmTier4++;
        if ($evidence->familialCvd === 'PRESENT')
            $dmTier4++;

        // ─── Synergy Rules ──────────────────────────────────────

        $synDm1 = $patient->a_diabetes && in_array($evidence->gdsLevel, ['WASPADA', 'PERLU_EVALUASI']);
        $synDm2 = $patient->a_diabetes && in_array($evidence->imtLevel, ['OBESE_I', 'OBESE_II']) && $evidence->wcLevel === 'OBESITAS_SENTRAL';
        $synDm3 = in_array($evidence->imtLevel, ['OBESE_I', 'OBESE_II']) && $evidence->wcLevel === 'OBESITAS_SENTRAL' && in_array($evidence->gdsLevel, ['PERLU_EVALUASI', 'WASPADA']);
        $synDm4 = $evidence->ageRisk === 'USIA_RISIKO' && $patient->a_diabetes && (in_array($evidence->imtLevel, ['OBESE_I', 'OBESE_II']) || $evidence->wcLevel === 'OBESITAS_SENTRAL');
        $hasDmSynergy = $synDm1 || $synDm2 || $synDm3 || $synDm4;

        // ─── Decision Tree ──────────────────────────────────────

        if ($dmTier1 >= 1) {
            // PATH A: GDS ≥ 200 — Indikator diagnostik langsung
            return new EvaluationResult(
                status: 'Perlu Pemeriksaan Klinis Segera',
                severity: 'critical',
                message: 'Gula darah sewaktu Anda tercatat ' . number_format((float) $patient->gula, 0) . ' mg/dL (≥200 mg/dL). Menurut PERKENI 2021, nilai ini memerlukan konfirmasi diagnostik segera.',
                action: 'SEGERA kunjungi Puskesmas atau dokter untuk pemeriksaan GDP (Gula Darah Puasa) dan/atau HbA1c.',
                explanations: $explanations,
            );
        }

        if (
            ($evidence->gdsLevel === 'WASPADA' && ($patient->a_diabetes || in_array($evidence->imtLevel, ['OBESE_I', 'OBESE_II']) || $evidence->wcLevel === 'OBESITAS_SENTRAL')) ||
            ($dmTier2 >= 2 && $hasDmSynergy) ||
            ($dmTier2 >= 3)
        ) {
            // PATH B: Evidence mayor kuat
            return new EvaluationResult(
                status: 'Risiko Tinggi',
                severity: 'high',
                message: 'Ditemukan kombinasi faktor risiko mayor yang signifikan untuk Diabetes Mellitus. Risiko Anda tergolong tinggi berdasarkan evaluasi guideline klinis.',
                action: 'Segera periksakan diri ke Puskesmas atau dokter untuk pemeriksaan gula darah lanjutan.',
                explanations: $explanations,
            );
        }

        if (
            ($dmTier2 >= 1 && $dmTier3 >= 1) ||
            ($dmTier2 >= 2) ||
            ($evidence->gdsLevel === 'PERLU_EVALUASI' && ($patient->a_diabetes || $evidence->ageRisk === 'USIA_RISIKO')) ||
            ($dmTier3 >= 3)
        ) {
            // PATH C: Evidence moderat
            return new EvaluationResult(
                status: 'Risiko Sedang',
                severity: 'moderate',
                message: 'Ditemukan beberapa faktor risiko moderat untuk Diabetes Mellitus. Perlu perhatian terhadap gaya hidup dan pemeriksaan berkala.',
                action: 'Modifikasi gaya hidup (pola makan sehat, aktivitas fisik). Konsultasi ke Puskesmas untuk pemeriksaan berkala.',
                explanations: $explanations,
            );
        }

        // PATH D: Default
        return new EvaluationResult(
            status: 'Risiko Rendah',
            severity: 'low',
            message: 'Tidak ditemukan faktor risiko mayor untuk Diabetes Mellitus berdasarkan data yang tersedia. Pertahankan gaya hidup sehat Anda.',
            action: 'Tetap jaga pola hidup sehat dan lakukan pemeriksaan kesehatan berkala.',
            explanations: $explanations,
        );
    }
}
