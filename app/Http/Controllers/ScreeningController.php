<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Respondent;
use App\Models\Screening;
use App\Models\Village;
use App\Models\HealthPost;
use App\Services\Clinical\GatecRuleEngine;
use App\Services\Clinical\DTO\PatientData;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;

class ScreeningController extends Controller
{
    public function showConsentForm()
    {
        $activePeriod = \App\Models\ScreeningPeriod::where('is_active', true)->first();
        if (!$activePeriod) {
            return view('pages.closed');
        }
        return view('pages.consent');
    }

    public function showRespondentForm()
    {
        $activePeriod = \App\Models\ScreeningPeriod::where('is_active', true)->first();
        if (!$activePeriod) {
            return view('pages.closed');
        }

        $villages = Village::with('healthPosts')->get();
        return view('pages.respondent', compact('villages'));
    }

    public function showQuestionnaireForm(Request $request)
    {
        $respondentData = $request->only(['nik', 'fullname', 'birthdate', 'gender', 'phone', 'address', 'village_id', 'health_post_id']);
        return view('pages.questionnaire', compact('respondentData'));
    }

    public function store(Request $request, GatecRuleEngine $ruleEngine)
    {
        $token = $request->cookie('device_token');
        $device = Device::where('token', $token)->first();

        if (!$device) {
            return redirect('/')->with('error', 'Device token not found.');
        }

        // IP-based rate limiting (anti-spam bot)
        $throttleKey = 'screening|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 30)) {
            return redirect('/')->with('error', 'Terlalu banyak permintaan pengisian. Silakan coba beberapa saat lagi.');
        }
        RateLimiter::hit($throttleKey, 60);

        // Limit check: max 2 screenings per day per device
        $todayScreeningsCount = Screening::where('device_id', $device->id)
            ->whereDate('screened_at', Carbon::today())
            ->count();

        if ($todayScreeningsCount >= 2) {
            return redirect('/')->with('error', 'Anda telah mencapai batas maksimal 2x skrining hari ini.');
        }

        // 1. Save or Update Respondent
        $respondent = Respondent::updateOrCreate(
            ['nik' => $request->input('nik')],
            [
                'device_id' => $device->id,
                'fullname' => $request->input('fullname'),
                'birthdate' => $request->input('birthdate'),
                'gender' => $request->input('gender'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'village_id' => $request->input('village_id'),
                'health_post_id' => $request->input('health_post_id'),
            ]
        );

        // 2. Map Request to PatientData DTO
        $age = Carbon::parse($request->input('birthdate'))->age;
        
        $patient = new PatientData(
            gender: $request->input('gender', ''),
            age: $age,
            a_diabetes: $request->input('a_diabetes') == '1',
            a_hipertensi: $request->input('a_hipertensi') == '1',
            a_jantung: $request->input('a_jantung') == '1',
            a_stroke: $request->input('a_stroke') == '1',
            a_kolesterol: $request->input('a_kolesterol') == '1',
            b_diabetes: $request->input('b_diabetes') == '1',
            b_hipertensi: $request->input('b_hipertensi') == '1',
            b_jantung: $request->input('b_jantung') == '1',
            b_stroke: $request->input('b_stroke') == '1',
            b_kolesterol: $request->input('b_kolesterol') == '1',
            sistolik: (float) $request->input('c_tekanan_sistolik', 120),
            diastolik: (float) $request->input('c_tekanan_diastolik', 80),
            tinggi: (float) $request->input('c_tinggi', 160),
            berat: (float) $request->input('c_berat', 60),
            lingkarPerut: (float) $request->input('c_lingkar_perut', 80),
            gula: $request->filled('c_gula') ? (float) $request->input('c_gula') : null,
            kolesterolLab: $request->filled('c_kolesterol_lab') ? (float) $request->input('c_kolesterol_lab') : null,
            asamUrat: $request->filled('c_asam_urat') ? (float) $request->input('c_asam_urat') : null,
            merokok: $request->input('c_merokok') == '1'
        );

        // 3. Process via Rule Engine
        $result = $ruleEngine->evaluate($patient);
        
        $activePeriod = \App\Models\ScreeningPeriod::where('is_active', true)->first();
        if (!$activePeriod) {
            return view('pages.closed');
        }

        // 4. Save Screening to Database
        $screening = new Screening();
        $screening->respondent_id = $respondent->id;
        $screening->device_id = $device->id;
        $screening->screening_period_id = $activePeriod->id;
        $screening->screened_at = now();

        // Raw Inputs
        $screening->a_diabetes = $patient->a_diabetes;
        $screening->a_hipertensi = $patient->a_hipertensi;
        $screening->a_jantung = $patient->a_jantung;
        $screening->a_stroke = $patient->a_stroke;
        $screening->a_kolesterol = $patient->a_kolesterol;
        
        $screening->b_diabetes = $patient->b_diabetes;
        $screening->b_hipertensi = $patient->b_hipertensi;
        $screening->b_jantung = $patient->b_jantung;
        $screening->b_stroke = $patient->b_stroke;
        $screening->b_asma = $request->input('b_asma') == '1';
        $screening->b_kolesterol = $patient->b_kolesterol;

        $screening->c_sistolik = $patient->sistolik;
        $screening->c_diastolik = $patient->diastolik;
        $screening->c_berat = $patient->berat;
        $screening->c_tinggi = $patient->tinggi;
        $screening->c_perut = $patient->lingkarPerut;
        $screening->c_gula = $patient->gula;
        $screening->c_kolesterol = $patient->kolesterolLab;
        $screening->c_asam_urat = $patient->asamUrat;
        $screening->c_merokok = $patient->merokok;

        // Engine Outputs
        $screening->engine_version = $result->ruleVersion->codename . ' v' . $result->ruleVersion->version;
        $screening->clinical_guideline = substr(implode(', ', array_keys($result->ruleVersion->references)), 0, 100);
        $screening->dm_status = $result->diabetes->status;
        $screening->dm_severity = strtolower($result->diabetes->severity);
        $screening->ht_status = $result->hypertension->status;
        $screening->ht_severity = strtolower($result->hypertension->severity);
        
        // Recommendation level based on referral needs
        $recLevel = $result->needsUrgentReferral ? 'emergency' : ($result->needsReferral ? 'visit_puskesmas' : ($screening->dm_severity === 'low' && $screening->ht_severity === 'low' ? 'lifestyle' : 'monitor'));
        $screening->recommendation_level = $recLevel;
        
        $screening->decision_explanation = json_encode([
            'dm_msg' => $result->diabetes->message,
            'dm_action' => $result->diabetes->action,
            'ht_msg' => $result->hypertension->message,
            'ht_action' => $result->hypertension->action,
            'findings' => $result->findingsToArray(),
            'evidence' => [
                'imtLevel' => $result->evidence->imtLevel,
                'bpLevel' => $result->evidence->bpLevel,
                'gdsLevel' => $result->evidence->gdsLevel,
                'wcLevel' => $result->evidence->wcLevel,
            ]
        ]);
        $screening->calculated_at = now();
        $screening->ip_address = $request->ip();
        $screening->user_agent = $request->userAgent();

        $screening->save();

        // Notify Super Admins & Owners about the new screening
        $admins = User::whereIn('role', ['super_admin', 'owner'])->get();
        if ($admins->count() > 0) {
            Notification::send($admins, new SystemNotification(
                'Data Skrining Baru',
                'Terdapat data skrining baru dari ' . $respondent->fullname,
                route('dashboard.skrining.index')
            ));
        }

        return redirect()->route('screening.result', ['id' => $screening->id]);
    }

    public function show($id)
    {
        $screening = Screening::with('respondent')->findOrFail($id);
        
        // Since result.blade.php expects a PatientData object or similar logic structure currently,
        // we might need to adapt result.blade.php to read from the Screening Eloquent object directly.
        return view('pages.result', compact('screening'));
    }

    public function history(Request $request)
    {
        $token = $request->cookie('device_token');
        $device = Device::where('token', $token)->first();

        if (!$device) {
            $screenings = collect();
        } else {
            $screenings = Screening::with('respondent')
                ->where('device_id', $device->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('pages.history', compact('screenings'));
    }
}
