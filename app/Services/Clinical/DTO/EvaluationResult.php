<?php

namespace App\Services\Clinical\DTO;

/**
 * Immutable DTO: Hasil evaluasi untuk satu domain penyakit (DM atau HT).
 */
final readonly class EvaluationResult
{
    public function __construct(
        public string $status,       // "Risiko Tinggi", "Sudah Terdiagnosis", dll
        public string $severity,     // critical | high | moderate | low | diagnosed | diagnosed_uncontrolled
        public string $message,      // Penjelasan untuk pasien
        public string $action,       // Rekomendasi tindak lanjut
        /** @var DecisionExplanation[] */
        public array  $explanations, // Audit trail per faktor
    ) {}
}
