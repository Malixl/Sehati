<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Screening;
use Illuminate\Support\Facades\Auth;
use App\Services\Dashboard\DashboardAnalyticsService;

class DashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(DashboardAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $user = Auth::user();
        
        $kpi = $this->analyticsService->getKpiSummary($user);
        


        // Chart Data
        $familyDiseaseChart = $this->analyticsService->getFamilyDiseaseChart($user);
        $personalDiseaseChart = $this->analyticsService->getPersonalDiseaseChart($user);
        $screeningPerVillageChart = $this->analyticsService->getScreeningPerVillageChart($user);
        $screeningTrendChart = $this->analyticsService->getScreeningTrendChart($user);
        $severityDmChart = $this->analyticsService->getSeverityDistributionChart($user, 'dm');
        $severityHtChart = $this->analyticsService->getSeverityDistributionChart($user, 'ht');

        return view('pages.dashboard.index', compact(
            'kpi',
            'familyDiseaseChart',
            'personalDiseaseChart',
            'screeningPerVillageChart',
            'screeningTrendChart',
            'severityDmChart',
            'severityHtChart'
        ));
    }
}
