<?php

namespace Tests\Unit\Clinical;

use App\Services\Clinical\HypertensionEvaluator;
use App\Services\Clinical\DTO\PatientData;
use App\Services\Clinical\EvidenceCollector;
use PHPUnit\Framework\TestCase;

class HypertensionEvaluatorTest extends TestCase
{
    private HypertensionEvaluator $evaluator;
    private EvidenceCollector $evidenceCollector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->evaluator = new HypertensionEvaluator();
        $this->evidenceCollector = new EvidenceCollector();
    }

    private function createPatient(array $overrides = []): PatientData
    {
        // Default: Pasien Sehat (Normal, Low Risk)
        $data = array_merge([
            'gender' => 'L',
            'age' => 30,
            'a_diabetes' => false,
            'a_hipertensi' => false,
            'a_jantung' => false,
            'a_stroke' => false,
            'a_kolesterol' => false,
            'b_diabetes' => false,
            'b_hipertensi' => false,
            'b_jantung' => false,
            'b_stroke' => false,
            'b_kolesterol' => false,
            'sistolik' => 110,
            'diastolik' => 70,
            'tinggi' => 170,
            'berat' => 65,  // IMT = 22.49 (Normal)
            'lingkarPerut' => 80,
            'gula' => 90,
            'kolesterolLab' => 180,
            'asamUrat' => 5.0,
            'merokok' => false,
        ], $overrides);

        return new PatientData(
            $data['gender'], $data['age'],
            $data['a_diabetes'], $data['a_hipertensi'], $data['a_jantung'], $data['a_stroke'], $data['a_kolesterol'],
            $data['b_diabetes'], $data['b_hipertensi'], $data['b_jantung'], $data['b_stroke'], $data['b_kolesterol'],
            $data['sistolik'], $data['diastolik'], $data['tinggi'], $data['berat'], $data['lingkarPerut'],
            $data['gula'], $data['kolesterolLab'], $data['asamUrat'], $data['merokok']
        );
    }

    public function test_1_normal_patient_is_low_risk()
    {
        $patient = $this->createPatient(); // BP 110/70
        $evidence = $this->evidenceCollector->collect($patient);
        $result = $this->evaluator->evaluate($patient, $evidence);

        $this->assertEquals('low', $result->severity);
        $this->assertEquals('Risiko Rendah', $result->status);
    }

    public function test_2_hypertensive_crisis_is_critical_risk()
    {
        // BP 185/110 -> KRISIS
        $patient = $this->createPatient(['sistolik' => 185, 'diastolik' => 110]);
        $evidence = $this->evidenceCollector->collect($patient);
        $result = $this->evaluator->evaluate($patient, $evidence);

        $this->assertEquals('critical', $result->severity);
        $this->assertEquals('Perlu Pemeriksaan Klinis Segera', $result->status);
    }

    public function test_3_grade_2_hypertension_is_high_risk()
    {
        // BP 145/95 -> DERAJAT_2 -> High
        $patient = $this->createPatient(['sistolik' => 145, 'diastolik' => 95]);
        $evidence = $this->evidenceCollector->collect($patient);
        $result = $this->evaluator->evaluate($patient, $evidence);

        $this->assertEquals('high', $result->severity);
        $this->assertEquals('Risiko Tinggi', $result->status);
    }

    public function test_4_diagnosed_but_uncontrolled_bp()
    {
        // Terdiagnosis (b_hipertensi = true), tapi BP = 165/100 (Derajat 3)
        $patient = $this->createPatient([
            'b_hipertensi' => true,
            'sistolik' => 165,
            'diastolik' => 100
        ]);
        $evidence = $this->evidenceCollector->collect($patient);
        $result = $this->evaluator->evaluate($patient, $evidence);

        $this->assertEquals('critical', $result->severity);
        $this->assertEquals('Sudah Terdiagnosis', $result->status);
        $this->assertStringContainsString('BELUM terkontrol', $result->message);
    }

    public function test_5_moderate_risk_by_age_and_weight_even_with_normal_bp()
    {
        // BP Normal, tapi Usia >= 45, ada genetik HT, Obesitas I -> Moderate
        $patient = $this->createPatient([
            'sistolik' => 125,
            'diastolik' => 75,
            'age' => 50,
            'berat' => 75, // Obese I (IMT 25.9)
            'a_hipertensi' => true 
        ]);
        $evidence = $this->evidenceCollector->collect($patient);
        $result = $this->evaluator->evaluate($patient, $evidence);

        $this->assertEquals('moderate', $result->severity);
        $this->assertEquals('Risiko Sedang', $result->status);
    }
}
