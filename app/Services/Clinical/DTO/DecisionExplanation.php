<?php

namespace App\Services\Clinical\DTO;

/**
 * Immutable DTO: Satu item penjelasan keputusan (audit trail).
 */
final readonly class DecisionExplanation
{
    public function __construct(
        public string $factor,      // "GDS ≥200 mg/dL"
        public string $value,       // "230"
        public string $tier,        // "Tier 1" | "Tier 2" | "Tier 3" | "Tier 4" | "Synergy"
        public string $guideline,   // "PERKENI 2021"
        public string $evidence,    // "Strong" | "Moderate" | "Supporting"
        public bool   $triggered,   // true jika faktor ini aktif pada pasien
    ) {}
}
