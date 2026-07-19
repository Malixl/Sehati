<?php

namespace App\Services\Dashboard;

use App\Models\Respondent;
use App\Models\Screening;
use App\Models\HealthPost;
use App\Models\FollowUp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardAnalyticsService
{
    /**
     * Cache duration in seconds (1 minute for near real-time updates)
     */
    private const CACHE_TTL = 60;

    /**
     * Generate unique cache key based on role and facility
     */
    private function getCacheKey(string $prefix, $user): string
    {
        $facilityId = $user->health_post_id ?? 'global';
        return "{$prefix}_{$user->role}_{$facilityId}";
    }

    /**
     * Clear cache for a specific facility to ensure real-time updates
     */
    public static function clearFacilityCache($healthPostId = null)
    {
        $prefixes = [
            'kpi_summary', 'family_disease', 'personal_disease', 
            'screening_trend', 'screening_village', 'severity_dm', 'severity_ht'
        ];
        
        foreach ($prefixes as $prefix) {
            Cache::forget("{$prefix}_owner_global");
            Cache::forget("{$prefix}_super_admin_global");
            
            if ($healthPostId) {
                Cache::forget("{$prefix}_admin_posyandu_{$healthPostId}");
                Cache::forget("{$prefix}_operator_posyandu_{$healthPostId}");
                
                // Assuming we can clear all puskesmas caches or we just clear the whole cache
                // But for simplicity, we can just call php artisan cache:clear via Artisan facade if we want
                // or just clear the specific posyandu and global. 
                // To be completely safe and avoid stale data across all dashboards:
            }
        }
    }

    /**
     * Get KPI Summary
     */
    public function getKpiSummary($user)
    {
        $cacheKey = $this->getCacheKey('kpi_summary', $user);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            $today = Carbon::today();

            $totalResponden = Respondent::filterByRole($user)->count();
            $totalSkrining = Screening::filterByRole($user)->count();
            $skriningHariIni = Screening::filterByRole($user)->whereDate('screened_at', $today)->count();
            
            // High risk counts
            $risikoTinggiDM = Screening::filterByRole($user)->whereIn('dm_severity', ['high', 'critical'])->count();
            $risikoTinggiHT = Screening::filterByRole($user)->whereIn('ht_severity', ['high', 'critical'])->count();
            
            // Action status
            $belumPenanganan = Screening::filterByRole($user)->where('action_status', 'unhandled')->count();
            
            $jumlahPosyandu = HealthPost::filterByRole($user)->count();

            // Status Follow Up (if table exists)
            $followUpPending = 0;
            $followUpCompleted = 0;
            if (Schema::hasTable('follow_ups')) {
                $followUpPending = FollowUp::filterByRole($user)->where('status', 'pending')->count();
                $followUpCompleted = FollowUp::filterByRole($user)->where('status', 'completed')->count();
            }

            return [
                'total_responden' => $totalResponden,
                'total_skrining' => $totalSkrining,
                'skrining_hari_ini' => $skriningHariIni,
                'risiko_tinggi_dm' => $risikoTinggiDM,
                'risiko_tinggi_ht' => $risikoTinggiHT,
                'belum_penanganan' => $belumPenanganan,
                'jumlah_posyandu' => $jumlahPosyandu,
                'follow_up_pending' => $followUpPending,
                'follow_up_completed' => $followUpCompleted,
            ];
        });
    }

    /**
     * Get Family Disease History for Pie Chart
     */
    public function getFamilyDiseaseChart($user)
    {
        $cacheKey = $this->getCacheKey('family_disease', $user);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            // Aggregate directly in DB using SUM
            $query = Screening::filterByRole($user)
                ->selectRaw('
                    SUM(CASE WHEN a_diabetes = 1 THEN 1 ELSE 0 END) as diabetes,
                    SUM(CASE WHEN a_hipertensi = 1 THEN 1 ELSE 0 END) as hipertensi,
                    SUM(CASE WHEN a_jantung = 1 THEN 1 ELSE 0 END) as jantung,
                    SUM(CASE WHEN a_stroke = 1 THEN 1 ELSE 0 END) as stroke,
                    SUM(CASE WHEN a_kolesterol = 1 THEN 1 ELSE 0 END) as kolesterol
                ')
                ->first();

            $result = [
                'Diabetes' => (int) $query->diabetes,
                'Hipertensi' => (int) $query->hipertensi,
                'Jantung' => (int) $query->jantung,
                'Stroke' => (int) $query->stroke,
                'Kolesterol' => (int) $query->kolesterol,
            ];
            
            $filtered = array_filter($result, function($value) {
                return $value > 0;
            });

            return empty($filtered) ? ['Belum ada data' => 0] : $filtered;
        });
    }

    /**
     * Get Personal Disease History for Pie Chart
     */
    public function getPersonalDiseaseChart($user)
    {
        $cacheKey = $this->getCacheKey('personal_disease', $user);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            $query = Screening::filterByRole($user)
                ->selectRaw('
                    SUM(CASE WHEN b_diabetes = 1 THEN 1 ELSE 0 END) as diabetes,
                    SUM(CASE WHEN b_hipertensi = 1 THEN 1 ELSE 0 END) as hipertensi,
                    SUM(CASE WHEN b_jantung = 1 THEN 1 ELSE 0 END) as jantung,
                    SUM(CASE WHEN b_stroke = 1 THEN 1 ELSE 0 END) as stroke,
                    SUM(CASE WHEN b_asma = 1 THEN 1 ELSE 0 END) as asma,
                    SUM(CASE WHEN b_kolesterol = 1 THEN 1 ELSE 0 END) as kolesterol
                ')
                ->first();

            $result = [
                'Diabetes' => (int) $query->diabetes,
                'Hipertensi' => (int) $query->hipertensi,
                'Jantung' => (int) $query->jantung,
                'Stroke' => (int) $query->stroke,
                'Asma' => (int) $query->asma,
                'Kolesterol' => (int) $query->kolesterol,
            ];

            $filtered = array_filter($result, function($value) {
                return $value > 0;
            });

            return empty($filtered) ? ['Belum ada data' => 0] : $filtered;
        });
    }

    /**
     * Get Screening Count per Village for Bar Chart
     */
    public function getScreeningPerVillageChart($user)
    {
        $cacheKey = $this->getCacheKey('screening_village', $user);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            // Using join to get village name for aggregation
            return DB::table('screenings')
                ->join('respondents', 'screenings.respondent_id', '=', 'respondents.id')
                ->join('villages', 'respondents.village_id', '=', 'villages.id')
                ->when($user->isAdminPosyandu(), function ($query) use ($user) {
                    $query->where('respondents.health_post_id', $user->health_post_id);
                })
                ->select('villages.name as village_name', DB::raw('count(screenings.id) as total'))
                ->groupBy('villages.id', 'villages.name')
                ->orderByDesc('total')
                ->limit(10)
                ->get()
                ->pluck('total', 'village_name')
                ->toArray();
        });
    }

    /**
     * Get Screening Trend (Last 6 Months) for Line Chart
     */
    public function getScreeningTrendChart($user)
    {
        $cacheKey = $this->getCacheKey('screening_trend', $user);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

            $data = Screening::filterByRole($user)
                ->where('screened_at', '>=', $sixMonthsAgo)
                ->selectRaw('DATE_FORMAT(screened_at, "%Y-%m") as month, COUNT(id) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $trend = [];
            // Fill missing months with 0
            for ($i = 5; $i >= 0; $i--) {
                $monthStr = Carbon::now()->subMonths($i)->format('Y-m');
                $monthDisplay = Carbon::now()->subMonths($i)->format('M Y');
                $record = $data->firstWhere('month', $monthStr);
                
                $trend[$monthDisplay] = $record ? (int) $record->total : 0;
            }

            return $trend;
        });
    }

    /**
     * Get Severity Distribution for Donut Chart
     */
    public function getSeverityDistributionChart($user, $type = 'dm') // 'dm' or 'ht'
    {
        $cacheKey = $this->getCacheKey("severity_{$type}", $user);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user, $type) {
            $column = $type === 'dm' ? 'dm_status' : 'ht_status';
            
            $data = Screening::filterByRole($user)
                ->select($column, DB::raw('COUNT(id) as total'))
                ->whereNotNull($column)
                ->groupBy($column)
                ->get();

            $result = [];

            foreach ($data as $item) {
                $label = $item->$column; // Already 'Risiko Tinggi', 'Sudah Terdiagnosis', etc
                
                if ((int) $item->total > 0) {
                    $result[$label] = (int) $item->total;
                }
            }

            // Jika kosong sama sekali, kembalikan satu label default agar chart tidak error
            if (empty($result)) {
                $result['Belum ada data'] = 0;
            }

            return $result;
        });
    }

    /**
     * Get Demographics (Gender & Age Group)
     */
    public function getDemographicsChart($user)
    {
        $cacheKey = $this->getCacheKey('demographics', $user);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            $genderData = Respondent::filterByRole($user)
                ->select('gender', DB::raw('COUNT(id) as total'))
                ->groupBy('gender')
                ->pluck('total', 'gender')
                ->toArray();
                
            $result = [
                'gender' => [
                    'Laki-laki' => $genderData['L'] ?? 0,
                    'Perempuan' => $genderData['P'] ?? 0,
                ],
                // Age groups could be aggregated here using DB::raw mapping birthdate diff to years, 
                // but for simplicity, we focus on gender as requested.
            ];

            return $result;
        });
    }
}
