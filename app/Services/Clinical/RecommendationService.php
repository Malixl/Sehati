<?php

namespace App\Services\Clinical;

use App\Services\Clinical\DTO\PatientData;
use App\Services\Clinical\DTO\EvaluationResult;
use App\Services\Clinical\DTO\Recommendation;

/**
 * Recommendation Service — Generator rekomendasi tindak lanjut.
 *
 * Logika identik dengan result.blade.php bagian rekomendasi UI.
 */
class RecommendationService
{
    /**
     * Generate daftar rekomendasi berdasarkan severity dan data pasien.
     *
     * @return Recommendation[]
     */
    public function generate(
        EvaluationResult $dm,
        EvaluationResult $ht,
        PatientData $patient,
        bool $needsUrgentReferral,
        bool $needsReferral,
    ): array {
        $recommendations = [];

        // Universal recommendations (selalu ada)
        $recommendations[] = new Recommendation(
            priority: 10,
            title: 'Perbaikan Pola Makan',
            desc: 'Perbanyak konsumsi sayur dan buah. Batasi asupan gula (maks 4 sendok makan/hari), garam (maks 1 sendok teh/hari), dan lemak/minyak berlebih.',
            type: 'general',
        );

        $recommendations[] = new Recommendation(
            priority: 11,
            title: 'Aktivitas Fisik Rutin',
            desc: 'Lakukan aktivitas fisik atau olahraga ringan minimal 30 menit setiap hari (contoh: jalan kaki, senam, bersepeda).',
            type: 'general',
        );

        // Conditional: Merokok
        if ($patient->merokok) {
            $recommendations[] = new Recommendation(
                priority: 5,
                title: 'Berhenti Merokok',
                desc: 'Merokok meningkatkan risiko DM (RR 1.44, Willi et al. 2007) dan merupakan faktor risiko kardiovaskular utama. Hentikan kebiasaan merokok sekarang.',
                type: 'warning',
            );
        }

        // Conditional: Rujukan
        if ($needsUrgentReferral) {
            $recommendations[] = new Recommendation(
                priority: 0,
                title: 'Pemeriksaan Klinis SEGERA',
                desc: 'Temuan skrining Anda memerlukan evaluasi medis segera. SEGERA kunjungi Puskesmas, klinik, atau rumah sakit terdekat untuk pemeriksaan lanjutan dan konfirmasi diagnostik.',
                type: 'urgent',
            );
        } elseif ($needsReferral) {
            $recommendations[] = new Recommendation(
                priority: 1,
                title: 'Kunjungan Medis (Prioritas)',
                desc: 'Berdasarkan evaluasi faktor risiko, sangat disarankan untuk memeriksakan diri ke Puskesmas atau dokter terdekat untuk pemeriksaan lanjutan.',
                type: 'urgent',
            );
        }

        // Sort by priority (0 = paling urgent)
        usort($recommendations, fn(Recommendation $a, Recommendation $b) => $a->priority <=> $b->priority);

        return $recommendations;
    }
}
