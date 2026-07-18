<?php

namespace App\Services\Clinical;

use App\Services\Clinical\DTO\PatientData;
use App\Services\Clinical\DTO\DecisionResult;

/**
 * GATEC Rule Engine — Orchestrator Utama.
 *
 * Ini adalah satu-satunya class yang perlu dipanggil oleh Controller/Blade.
 * Mengoordinasikan seluruh pipeline: Evidence → Evaluation → Findings → Recommendations.
 *
 * @example
 *   $engine = app(GatecRuleEngine::class);
 *   $result = $engine->evaluate(PatientData::fromRequest($request));
 */
class GatecRuleEngine
{
    public function __construct(
        private readonly EvidenceCollector $evidenceCollector,
        private readonly DiabetesEvaluator $diabetesEvaluator,
        private readonly HypertensionEvaluator $hypertensionEvaluator,
        private readonly ClinicalFindingService $findingService,
        private readonly RecommendationService $recommendationService,
        private readonly ConflictResolver $conflictResolver,
    ) {}

    /**
     * Jalankan seluruh pipeline Rule Engine GATEC.
     *
     * Pipeline:
     * 1. Evidence Collection (raw input → level klinis)
     * 2. DM Evaluation (tier counting → synergy → decision tree)
     * 3. HT Evaluation (tier counting → synergy → decision tree)
     * 4. Conflict Resolution (referral flags)
     * 5. Clinical Findings Generation
     * 6. Recommendation Generation
     */
    public function evaluate(PatientData $patient): DecisionResult
    {
        // Step 1: Collect Evidence
        $evidence = $this->evidenceCollector->collect($patient);

        // Step 2: Evaluate Diabetes
        $dmResult = $this->diabetesEvaluator->evaluate($patient, $evidence);

        // Step 3: Evaluate Hypertension
        $htResult = $this->hypertensionEvaluator->evaluate($patient, $evidence);

        // Step 4: Resolve Conflicts & Referral Flags
        $flags = $this->conflictResolver->resolveReferralFlags($dmResult, $htResult);

        // Step 5: Generate Clinical Findings
        $findings = $this->findingService->generate($patient, $evidence);

        // Step 6: Generate Recommendations
        $recommendations = $this->recommendationService->generate(
            dm: $dmResult,
            ht: $htResult,
            patient: $patient,
            needsUrgentReferral: $flags['needsUrgentReferral'],
            needsReferral: $flags['needsReferral'],
        );

        // Step 7: Compose Final Result
        return new DecisionResult(
            diabetes: $dmResult,
            hypertension: $htResult,
            findings: $findings,
            recommendations: $recommendations,
            needsUrgentReferral: $flags['needsUrgentReferral'],
            needsReferral: $flags['needsReferral'],
            hasCardiometabolicDouble: $flags['hasCardiometabolicDouble'],
            evidence: $evidence,
            patient: $patient,
            ruleVersion: new RuleVersion(),
        );
    }
}
