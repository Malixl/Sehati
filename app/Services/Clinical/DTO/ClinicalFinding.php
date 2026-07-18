<?php

namespace App\Services\Clinical\DTO;

/**
 * Immutable DTO: Satu temuan klinis.
 */
final readonly class ClinicalFinding
{
    public function __construct(
        public string $title,     // "Obesitas"
        public string $desc,      // "IMT Anda 28.5 kg/m² ..."
        public string $severity,  // critical | high | moderate | info | good
    ) {}

    /**
     * Convert ke array (backward-compatible dengan format Blade lama).
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'desc' => $this->desc,
            'severity' => $this->severity,
        ];
    }
}
