<?php

namespace App\Services\Clinical;

use App\Services\Clinical\DTO\PatientData;
use App\Services\Clinical\DTO\ClinicalEvidence;
use App\Services\Clinical\DTO\ClinicalFinding;

/**
 * Clinical Finding Service — Generator temuan klinis otomatis.
 *
 * Logika 100% identik dengan result.blade.php baris 408–491.
 */
class ClinicalFindingService
{
    /**
     * Generate daftar temuan klinis dari evidence dan data pasien.
     *
     * @return ClinicalFinding[]
     */
    public function generate(PatientData $patient, ClinicalEvidence $evidence): array
    {
        $findings = [];

        $this->addBloodPressureFindings($evidence, $patient, $findings);
        $this->addBloodSugarFindings($evidence, $patient, $findings);
        $this->addBmiFindings($evidence, $findings);
        $this->addWaistFindings($evidence, $patient, $findings);
        $this->addCholesterolFindings($evidence, $patient, $findings);
        $this->addUricAcidFindings($evidence, $patient, $findings);
        $this->addSmokingFindings($patient, $findings);
        $this->addFamilyHistoryFindings($patient, $evidence, $findings);
        $this->addComorbidityFindings($patient, $findings);
        $this->addAgeFindings($patient, $evidence, $findings);

        if (empty($findings)) {
            $findings[] = new ClinicalFinding(
                title: 'Tidak Ada Temuan Abnormal',
                desc: 'Semua parameter dalam batas normal. Pertahankan gaya hidup sehat Anda.',
                severity: 'good',
            );
        }

        return $findings;
    }

    private function addBloodPressureFindings(ClinicalEvidence $evidence, PatientData $patient, array &$findings): void
    {
        $bpLabels = [
            'KRISIS'    => ['Krisis Hipertensi', "Tekanan darah {$patient->sistolik}/{$patient->diastolik} mmHg — memerlukan evaluasi segera.", 'critical'],
            'DERAJAT_3' => ['Hipertensi Derajat 3', "Tekanan darah {$patient->sistolik}/{$patient->diastolik} mmHg (≥160/100).", 'high'],
            'DERAJAT_2' => ['Hipertensi Derajat 2', "Tekanan darah {$patient->sistolik}/{$patient->diastolik} mmHg (≥140/90).", 'high'],
            'DERAJAT_1' => ['Hipertensi Derajat 1', "Tekanan darah {$patient->sistolik}/{$patient->diastolik} mmHg (≥130/80).", 'moderate'],
            'ELEVATED'  => ['Tekanan Darah Elevated', "Tekanan darah {$patient->sistolik}/{$patient->diastolik} mmHg — di atas normal.", 'info'],
        ];

        if (isset($bpLabels[$evidence->bpLevel])) {
            $label = $bpLabels[$evidence->bpLevel];
            $findings[] = new ClinicalFinding(title: $label[0], desc: $label[1], severity: $label[2]);
        }
    }

    private function addBloodSugarFindings(ClinicalEvidence $evidence, PatientData $patient, array &$findings): void
    {
        $gdsLabels = [
            'CURIGA_DM'     => ['Gula Darah Sangat Tinggi', 'GDS tercatat ' . number_format((float) $patient->gula, 0) . ' mg/dL (≥200) — curiga DM.', 'critical'],
            'WASPADA'       => ['Gula Darah Waspada', 'GDS tercatat ' . number_format((float) $patient->gula, 0) . ' mg/dL (140–199) — memerlukan evaluasi.', 'high'],
            'PERLU_EVALUASI' => ['Gula Darah Perlu Evaluasi', 'GDS tercatat ' . number_format((float) $patient->gula, 0) . ' mg/dL (100–139).', 'moderate'],
        ];

        if (isset($gdsLabels[$evidence->gdsLevel])) {
            $label = $gdsLabels[$evidence->gdsLevel];
            $findings[] = new ClinicalFinding(title: $label[0], desc: $label[1], severity: $label[2]);
        }
    }

    private function addBmiFindings(ClinicalEvidence $evidence, array &$findings): void
    {
        if (in_array($evidence->imtLevel, ['OBESE_I', 'OBESE_II'])) {
            $findings[] = new ClinicalFinding(
                title: 'Obesitas',
                desc: 'IMT Anda ' . number_format($evidence->imt, 1) . ' kg/m² (≥25 — cut-off Asia-Pasifik).',
                severity: 'high',
            );
        } elseif ($evidence->imtLevel === 'OVERWEIGHT') {
            $findings[] = new ClinicalFinding(
                title: 'Berat Badan Lebih',
                desc: 'IMT Anda ' . number_format($evidence->imt, 1) . ' kg/m² (23–24.9 — overweight Asia-Pasifik).',
                severity: 'moderate',
            );
        }
    }

    private function addWaistFindings(ClinicalEvidence $evidence, PatientData $patient, array &$findings): void
    {
        if ($evidence->wcLevel === 'OBESITAS_SENTRAL') {
            $cutoff = $patient->gender === 'P' ? '≥80' : '≥90';
            $genderLabel = $patient->gender === 'P' ? 'perempuan' : 'laki-laki';
            $findings[] = new ClinicalFinding(
                title: 'Obesitas Sentral',
                desc: "Lingkar perut {$patient->lingkarPerut} cm ({$cutoff} cm untuk {$genderLabel}).",
                severity: 'high',
            );
        }
    }

    private function addCholesterolFindings(ClinicalEvidence $evidence, PatientData $patient, array &$findings): void
    {
        if ($evidence->cholLevel === 'TINGGI') {
            $findings[] = new ClinicalFinding(
                title: 'Kolesterol Tinggi',
                desc: 'Kolesterol total ' . number_format((float) $patient->kolesterolLab, 0) . ' mg/dL (≥240).',
                severity: 'moderate',
            );
        } elseif ($evidence->cholLevel === 'BATAS_TINGGI') {
            $findings[] = new ClinicalFinding(
                title: 'Kolesterol Batas Tinggi',
                desc: 'Kolesterol total ' . number_format((float) $patient->kolesterolLab, 0) . ' mg/dL (200–239).',
                severity: 'info',
            );
        }
    }

    private function addUricAcidFindings(ClinicalEvidence $evidence, PatientData $patient, array &$findings): void
    {
        if ($evidence->uaLevel === 'TINGGI') {
            $findings[] = new ClinicalFinding(
                title: 'Asam Urat Tinggi',
                desc: 'Asam urat ' . number_format((float) $patient->asamUrat, 1) . ' mg/dL — di atas batas normal.',
                severity: 'moderate',
            );
        }
    }

    private function addSmokingFindings(PatientData $patient, array &$findings): void
    {
        if ($patient->merokok) {
            $findings[] = new ClinicalFinding(
                title: 'Perokok Aktif',
                desc: 'Merokok meningkatkan risiko DM (RR 1.44) dan merupakan faktor risiko kardiovaskular utama.',
                severity: 'high',
            );
        }
    }

    private function addFamilyHistoryFindings(PatientData $patient, ClinicalEvidence $evidence, array &$findings): void
    {
        if ($patient->a_diabetes) {
            $findings[] = new ClinicalFinding(
                title: 'Riwayat Keluarga DM',
                desc: 'Riwayat diabetes pada keluarga meningkatkan risiko DM 2–3.5× lipat.',
                severity: 'moderate',
            );
        }
        if ($patient->a_hipertensi) {
            $findings[] = new ClinicalFinding(
                title: 'Riwayat Keluarga HT',
                desc: 'Riwayat hipertensi pada keluarga (heritabilitas 30–50%).',
                severity: 'moderate',
            );
        }
        if ($evidence->familialCvd === 'STRONG') {
            $findings[] = new ClinicalFinding(
                title: 'Predisposisi Kardiovaskular Familial',
                desc: 'Multiple riwayat penyakit kardiovaskular pada keluarga.',
                severity: 'moderate',
            );
        }
    }

    private function addComorbidityFindings(PatientData $patient, array &$findings): void
    {
        if ($patient->b_jantung) {
            $findings[] = new ClinicalFinding(
                title: 'Riwayat Penyakit Jantung',
                desc: 'Anda pernah didiagnosis penyakit jantung — memerlukan monitoring tekanan darah ketat.',
                severity: 'high',
            );
        }
        if ($patient->b_stroke) {
            $findings[] = new ClinicalFinding(
                title: 'Riwayat Stroke',
                desc: 'Anda pernah didiagnosis stroke — HT adalah penyebab utama stroke.',
                severity: 'high',
            );
        }
    }

    private function addAgeFindings(PatientData $patient, ClinicalEvidence $evidence, array &$findings): void
    {
        if ($evidence->ageRisk === 'USIA_RISIKO') {
            $findings[] = new ClinicalFinding(
                title: 'Usia ≥ 45 Tahun',
                desc: "Usia Anda {$patient->age} tahun — skrining DM dan HT direkomendasikan (PERKENI 2021).",
                severity: 'info',
            );
        }
    }
}
