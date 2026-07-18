<?php

namespace App\Services\Clinical\DTO;

/**
 * Immutable DTO: Satu item rekomendasi tindak lanjut.
 */
final readonly class Recommendation
{
    public function __construct(
        public int    $priority, // 0 = tertinggi (urgent), 1 = tinggi, 2 = normal
        public string $title,    // "Pemeriksaan Klinis SEGERA"
        public string $desc,     // Penjelasan
        public string $type,     // urgent | warning | general
    ) {}
}
