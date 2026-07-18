<?php

namespace Tests\Unit\Clinical;

use App\Services\Clinical\DiabetesEvaluator;
use App\Services\Clinical\DTO\PatientData;
use App\Services\Clinical\EvidenceCollector;
use PHPUnit\Framework\TestCase;

class DiabetesEvaluatorTest extends TestCase
{
    private DiabetesEvaluator $evaluator;
    private EvidenceCollector $evidenceCollector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->evaluator = new DiabetesEvaluator();
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
            'sistolik' => 120,
            'diastolik' => 80,
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
        $patient = $this->createPatient();
        $evidence = $this->evidenceCollector->collect($patient);
        $result = $this->evaluator->evaluate($patient, $evidence);

        $this->assertEquals('low', $result->severity);
        $this->assertEquals('Risiko Rendah', $result->status);
    }

    public function test_2_gds_over_200_is_critical_risk()
    {
        // Gula darah 210 -> CURIGA DM -> Critical
        $patient = $this->createPatient(['gula' => 210]);
        $evidence = $this->evidenceCollector->collect($patient);
        $result = $this->evaluator->evaluate($patient, $evidence);

        $this->assertEquals('critical', $result->severity);
        $this->assertEquals('Perlu Pemeriksaan Klinis Segera', $result->status);
    }

    public function test_3_obese_with_warning_gds_is_high_risk()
    {
        // Obesitas Sentral + GDS Waspada (150) -> High Risk
        $patient = $this->createPatient([
            'lingkarPerut' => 100, // Laki-laki >= 90 = Obesitas Sentral
            'gula' => 150          // Waspada
        ]);
        $evidence = $this->evidenceCollector->collect($patient);
        $result = $this->evaluator->evaluate($patient, $evidence);

        $this->assertEquals('high', $result->severity);
        $this->assertEquals('Risiko Tinggi', $result->status);
    }

    public function test_4_already_diagnosed_overrides_everything()
    {
        // Gula 90, tapi sudah didiagnosis DM -> Status 'diagnosed'
        $patient = $this->createPatient([
            'b_diabetes' => true,
            'gula' => 90
        ]);
        $evidence = $this->evidenceCollector->collect($patient);
        $result = $this->evaluator->evaluate($patient, $evidence);

        $this->assertEquals('moderate', $result->severity);
        $this->assertEquals('Sudah Terdiagnosis', $result->status);
    }

    public function test_5_moderate_risk_by_age_and_overweight()
    {
        // Usia >= 45, IMT Overweight, dan Perokok -> 3 Moderate factors -> Moderate Risk
        $patient = $this->createPatient([
            'age' => 50,
            'berat' => 70, // Overweight
            'merokok' => true
        ]);
        $evidence = $this->evidenceCollector->collect($patient);
        $result = $this->evaluator->evaluate($patient, $evidence);

        $this->assertEquals('moderate', $result->severity);
        $this->assertEquals('Risiko Sedang', $result->status);
    }
}
