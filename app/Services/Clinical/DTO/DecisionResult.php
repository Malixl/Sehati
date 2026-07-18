<?php

namespace App\Services\Clinical\DTO;

use App\Services\Clinical\RuleVersion;

/**
 * Immutable DTO: Hasil akhir keseluruhan Rule Engine.
 * Ini adalah satu-satunya objek yang dikembalikan ke Controller/Blade.
 */
final readonly class DecisionResult
{
    public function __construct(
        public EvaluationResult $diabetes,
        public EvaluationResult $hypertension,
        /** @var ClinicalFinding[] */
        public array            $findings,
        /** @var Recommendation[] */
        public array            $recommendations,
        public bool             $needsUrgentReferral,
        public bool             $needsReferral,
        public bool             $hasCardiometabolicDouble,
        public ClinicalEvidence $evidence,
        public PatientData      $patient,
        public RuleVersion      $ruleVersion,
    ) {}

    /**
     * Convert findings ke format array (backward-compatible dengan Blade lama).
     */
    public function findingsToArray(): array
    {
        return array_map(fn(ClinicalFinding $f) => $f->toArray(), $this->findings);
    }
}
