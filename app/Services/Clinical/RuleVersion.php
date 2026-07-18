<?php

namespace App\Services\Clinical;

/**
 * Metadata versi Rule Engine GATEC.
 * Menyimpan informasi versi, referensi klinis, dan changelog.
 */
final readonly class RuleVersion
{
    public string $version;
    public string $codename;
    public string $fullName;
    public string $releaseDate;
    public string $author;

    /** @var array<string, string> */
    public array $references;

    /** @var array<string, string> */
    public array $changelog;

    public function __construct()
    {
        $this->version = '2.0.0';
        $this->codename = 'GATEC';
        $this->fullName = 'Guideline-Anchored Tiered Evidence Classification';
        $this->releaseDate = '2026-07-12';
        $this->author = 'SEHATI Research Team';

        // Menggunakan workaround karena readonly property dengan array literal
        // tidak bisa di-assign di class body di PHP 8.2
        $this->references = [
            'PERKENI 2021'        => 'Konsensus Pengelolaan dan Pencegahan DM Tipe 2 di Indonesia',
            'ADA 2024'            => 'Standards of Care in Diabetes',
            'JNC 8'               => 'Evidence-Based Guideline for Management of High Blood Pressure',
            'ESH-ESC 2023'        => 'European Society of Hypertension Guidelines',
            'ACC-AHA 2017'        => 'Guideline for Prevention, Detection, Evaluation, and Management of High Blood Pressure',
            'WHO Asia-Pacific 2004' => 'BMI Classification for Asian Population',
            'IDF 2005'            => 'Metabolic Syndrome Criteria (Waist Circumference)',
            'NCEP ATP III'        => 'National Cholesterol Education Program — Cholesterol Classification',
            'WHO HEARTS 2018'     => 'Technical Package for Cardiovascular Disease Management in Primary Health Care',
        ];

        $this->changelog = [
            '2.0.0' => 'Refactored to Service Architecture (GATEC). Clinical output identical to v2 Blade implementation.',
            '1.0.0' => 'MVP — weighted scoring (+1/+2/+3) in result.blade.php.',
        ];
    }
}
