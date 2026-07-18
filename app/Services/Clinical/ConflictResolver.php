<?php

namespace App\Services\Clinical;

use App\Services\Clinical\DTO\EvaluationResult;

/**
 * Conflict Resolver — Menangani kasus konflik antara evidence dan status terdahulu.
 *
 * Semua kasus konflik telah terdokumentasi di implementation plan.
 */
class ConflictResolver
{
    /**
     * Menentukan flag rujukan berdasarkan hasil evaluasi DM dan HT.
     * Logika identik dengan result.blade.php baris 493–497.
     *
     * @return array{needsUrgentReferral: bool, needsReferral: bool, hasCardiometabolicDouble: bool}
     */
    public function resolveReferralFlags(EvaluationResult $dm, EvaluationResult $ht): array
    {
        $needsUrgentReferral = in_array($dm->severity, ['critical'])
            || in_array($ht->severity, ['critical']);

        $needsReferral = in_array($dm->severity, ['high'])
            || in_array($ht->severity, ['high']);

        $hasCardiometabolicDouble = !in_array($dm->severity, ['low'])
            && !in_array($ht->severity, ['low']);

        return [
            'needsUrgentReferral' => $needsUrgentReferral,
            'needsReferral' => $needsReferral,
            'hasCardiometabolicDouble' => $hasCardiometabolicDouble,
        ];
    }
}
