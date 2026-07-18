<?php

namespace App\Services\Clinical;

use App\Services\Clinical\DTO\PatientData;
use App\Services\Clinical\DTO\ClinicalEvidence;
use App\Services\Clinical\DTO\EvaluationResult;
use App\Services\Clinical\DTO\DecisionExplanation;

/**
 * Hypertension Evaluator — Decision Matrix HT.
 *
 * Logika 100% identik dengan result.blade.php baris 248–356.
 * Referensi utama: JNC 8, ESH-ESC 2023, ACC-AHA 2017.
 */
class HypertensionEvaluator
{
    /**
     * Evaluasi risiko Hipertensi.
     */
    public function evaluate(PatientData $patient, ClinicalEvidence $evidence): EvaluationResult
    {
        $explanations = [];

        // ─── Pre-screening: Sudah Terdiagnosis ──────────────────
        if ($evidence->htAlreadyDiagnosed) {
            $explanations[] = new DecisionExplanation(
                factor: 'Sudah didiagnosis Hipertensi oleh tenaga medis',
                value: 'Ya',
                tier: 'Pre-screening',
                guideline: 'JNC 8',
                evidence: 'Definitive',
                triggered: true,
            );

            $htMessage = 'Anda sudah pernah didiagnosis Hipertensi oleh tenaga medis.';
            $htSeverity = 'moderate';

            // Tetap evaluasi BP aktual untuk cek kontrol
            if (in_array($evidence->bpLevel, ['KRISIS', 'DERAJAT_3'])) {
                $htMessage .= ' Tekanan darah Anda saat ini tercatat ' . $patient->sistolik . '/' . $patient->diastolik . ' mmHg — BELUM terkontrol dengan baik.';
                $htAction = 'SEGERA konsultasi ke dokter untuk evaluasi dan penyesuaian terapi.';
                $htSeverity = 'critical';
            } elseif (in_array($evidence->bpLevel, ['DERAJAT_2', 'DERAJAT_1'])) {
                $htMessage .= ' Tekanan darah Anda saat ini tercatat ' . $patient->sistolik . '/' . $patient->diastolik . ' mmHg — masih perlu pengawasan.';
                $htAction = 'Lanjutkan kontrol rutin dan konsultasi penyesuaian terapi jika diperlukan.';
            } else {
                $htMessage .= ' Tekanan darah Anda saat ini tercatat ' . $patient->sistolik . '/' . $patient->diastolik . ' mmHg — terkontrol baik.';
                $htAction = 'Pertahankan kepatuhan terapi dan kontrol rutin ke fasilitas kesehatan.';
            }

            return new EvaluationResult(
                status: 'Terdiagnosa',
                severity: $htSeverity,
                message: $htMessage,
                action: $htAction,
                explanations: $explanations,
            );
        }

        // ─── Synergy Rules ──────────────────────────────────────

        $synHt1 = $patient->a_hipertensi && in_array($evidence->bpLevel, ['DERAJAT_1', 'DERAJAT_2']);
        $synHt2 = in_array($evidence->bpLevel, ['DERAJAT_1', 'DERAJAT_2', 'DERAJAT_3']) && $patient->merokok && in_array($evidence->imtLevel, ['OBESE_I', 'OBESE_II']);
        $synHt3 = in_array($evidence->bpLevel, ['DERAJAT_2', 'DERAJAT_3', 'KRISIS']) && $evidence->personalCvd;
        $hasHtSynergy = $synHt1 || $synHt2 || $synHt3;

        // ─── Tier Counting ──────────────────────────────────────

        // Tier 1 — Direct Indicator (BP ≥ 140/90 atau krisis)
        $htTier1 = in_array($evidence->bpLevel, ['DERAJAT_2', 'DERAJAT_3', 'KRISIS']) ? 1 : 0;

        // Tier 2 — Major Risk Factors
        $htTier2 = 0;
        if ($patient->a_hipertensi) $htTier2++;
        if (in_array($evidence->imtLevel, ['OBESE_I', 'OBESE_II'])) $htTier2++;
        if ($patient->merokok) $htTier2++;
        if ($evidence->bpLevel === 'DERAJAT_1') $htTier2++;

        // Tier 3 — Moderate Risk Factors
        $htTier3 = 0;
        if ($evidence->wcLevel === 'OBESITAS_SENTRAL') $htTier3++;
        if ($evidence->ageRisk === 'USIA_RISIKO') $htTier3++;
        if ($evidence->personalCvd) $htTier3++;
        if ($evidence->bpLevel === 'ELEVATED') $htTier3++;
        if ($evidence->imtLevel === 'OVERWEIGHT') $htTier3++;

        // Tier 4 — Supporting Factors
        $htTier4 = 0;
        if ($evidence->familialCvd !== 'NONE') $htTier4++;
        if ($patient->b_kolesterol) $htTier4++;
        if (in_array($evidence->cholLevel, ['TINGGI', 'BATAS_TINGGI'])) $htTier4++;
        if ($evidence->uaLevel === 'TINGGI') $htTier4++;

        // ─── Build Explanations ─────────────────────────────────

        $explanations[] = new DecisionExplanation(
            factor: 'Tekanan darah (sistolik/diastolik)',
            value: $patient->sistolik . '/' . $patient->diastolik . ' mmHg',
            tier: 'Tier 1',
            guideline: 'JNC 8 / ESH-ESC 2023',
            evidence: 'Strong',
            triggered: $htTier1 >= 1,
        );
        $explanations[] = new DecisionExplanation(
            factor: 'Riwayat keluarga Hipertensi',
            value: $patient->a_hipertensi ? 'Ya' : 'Tidak',
            tier: 'Tier 2',
            guideline: 'ESH-ESC 2023',
            evidence: 'Strong',
            triggered: $patient->a_hipertensi,
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
            factor: 'Perokok aktif',
            value: $patient->merokok ? 'Ya' : 'Tidak',
            tier: 'Tier 2',
            guideline: 'WHO HEARTS 2018',
            evidence: 'Strong',
            triggered: $patient->merokok,
        );
        $explanations[] = new DecisionExplanation(
            factor: 'BP Derajat 1 (130–139/80–89)',
            value: $patient->sistolik . '/' . $patient->diastolik . ' mmHg',
            tier: 'Tier 2',
            guideline: 'ACC-AHA 2017',
            evidence: 'Strong',
            triggered: $evidence->bpLevel === 'DERAJAT_1',
        );
        $explanations[] = new DecisionExplanation(
            factor: 'Obesitas sentral',
            value: number_format($patient->lingkarPerut, 0) . ' cm',
            tier: 'Tier 3',
            guideline: 'IDF 2005',
            evidence: 'Moderate',
            triggered: $evidence->wcLevel === 'OBESITAS_SENTRAL',
        );
        $explanations[] = new DecisionExplanation(
            factor: 'Usia ≥45 tahun',
            value: (string) $patient->age,
            tier: 'Tier 3',
            guideline: 'ESH-ESC 2023',
            evidence: 'Moderate',
            triggered: $evidence->ageRisk === 'USIA_RISIKO',
        );
        $explanations[] = new DecisionExplanation(
            factor: 'Riwayat penyakit kardiovaskular pribadi',
            value: $evidence->personalCvd ? 'Ya' : 'Tidak',
            tier: 'Tier 3',
            guideline: 'ACC-AHA 2017',
            evidence: 'Moderate',
            triggered: $evidence->personalCvd,
        );

        // ─── Decision Tree ──────────────────────────────────────

        if ($evidence->bpLevel === 'KRISIS') {
            // PATH A: Krisis hipertensi — ≥180/120
            return new EvaluationResult(
                status: 'Perlu Pemeriksaan Klinis Segera',
                severity: 'critical',
                message: 'Tekanan darah Anda tercatat ' . $patient->sistolik . '/' . $patient->diastolik . ' mmHg. Ini termasuk kategori KRISIS HIPERTENSI menurut AHA/ACC.',
                action: 'SEGERA kunjungi fasilitas kesehatan terdekat untuk evaluasi dan penanganan darurat.',
                explanations: $explanations,
            );
        }

        if ($evidence->bpLevel === 'DERAJAT_3' && $evidence->personalCvd) {
            // SYN-HT-3: HT Derajat 3 + riwayat CVD
            return new EvaluationResult(
                status: 'Perlu Pemeriksaan Klinis Segera',
                severity: 'critical',
                message: 'Tekanan darah Anda tercatat ' . $patient->sistolik . '/' . $patient->diastolik . ' mmHg (Hipertensi Derajat 3), disertai riwayat penyakit kardiovaskular. Profil ini memerlukan evaluasi medis segera.',
                action: 'SEGERA kunjungi dokter. Kombinasi tekanan darah sangat tinggi dengan riwayat penyakit jantung/stroke merupakan kondisi berisiko tinggi.',
                explanations: $explanations,
            );
        }

        if ($evidence->bpLevel === 'DERAJAT_3') {
            return new EvaluationResult(
                status: 'Risiko Tinggi',
                severity: 'high',
                message: 'Tekanan darah Anda tercatat ' . $patient->sistolik . '/' . $patient->diastolik . ' mmHg. Ini termasuk Hipertensi Derajat 3 menurut ESH-ESC 2023.',
                action: 'Segera periksakan diri ke Puskesmas atau dokter untuk konfirmasi dan penanganan lebih lanjut.',
                explanations: $explanations,
            );
        }

        if ($evidence->bpLevel === 'DERAJAT_2') {
            // BP ≥140/90 = definisi klinis hipertensi (JNC 8)
            return new EvaluationResult(
                status: 'Risiko Tinggi',
                severity: 'high',
                message: 'Tekanan darah Anda tercatat ' . $patient->sistolik . '/' . $patient->diastolik . ' mmHg. Menurut JNC 8 dan PERHI, nilai ini memenuhi definisi klinis hipertensi.',
                action: 'Segera periksakan diri ke Puskesmas atau dokter untuk pemeriksaan lanjutan dan konfirmasi diagnosis.',
                explanations: $explanations,
            );
        }

        if ($evidence->bpLevel === 'DERAJAT_1' && $hasHtSynergy) {
            return new EvaluationResult(
                status: 'Risiko Tinggi',
                severity: 'high',
                message: 'Tekanan darah Anda tercatat ' . $patient->sistolik . '/' . $patient->diastolik . ' mmHg (Hipertensi Derajat 1), disertai kombinasi faktor risiko yang signifikan.',
                action: 'Segera konsultasi ke Puskesmas atau dokter untuk evaluasi risiko kardiovaskular secara menyeluruh.',
                explanations: $explanations,
            );
        }

        if (
            ($evidence->bpLevel === 'DERAJAT_1' && $htTier2 >= 1) ||
            ($evidence->bpLevel === 'ELEVATED' && $htTier2 >= 2) ||
            ($evidence->bpLevel === 'NORMAL' && $patient->a_hipertensi && ($evidence->ageRisk === 'USIA_RISIKO' || in_array($evidence->imtLevel, ['OBESE_I', 'OBESE_II']) || $patient->merokok)) ||
            ($htTier2 >= 2 && $htTier3 >= 1)
        ) {
            // PATH C: Evidence moderat
            return new EvaluationResult(
                status: 'Risiko Sedang',
                severity: 'moderate',
                message: 'Ditemukan beberapa faktor risiko moderat untuk Hipertensi. Tekanan darah Anda saat ini: ' . $patient->sistolik . '/' . $patient->diastolik . ' mmHg.',
                action: 'Modifikasi gaya hidup (kurangi garam, olahraga rutin). Pantau tekanan darah secara berkala. Konsultasi ke Puskesmas untuk evaluasi.',
                explanations: $explanations,
            );
        }

        // PATH D: Default
        return new EvaluationResult(
            status: 'Risiko Rendah',
            severity: 'low',
            message: 'Tidak ditemukan faktor risiko mayor untuk Hipertensi berdasarkan data yang tersedia. Tekanan darah Anda: ' . $patient->sistolik . '/' . $patient->diastolik . ' mmHg (Normal).',
            action: 'Pertahankan gaya hidup sehat dan hindari stres berlebih. Lakukan pemeriksaan kesehatan berkala.',
            explanations: $explanations,
        );
    }
}
