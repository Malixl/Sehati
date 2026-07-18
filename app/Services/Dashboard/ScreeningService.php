<?php

namespace App\Services\Dashboard;

use App\Models\Screening;
use Illuminate\Database\Eloquent\Builder;

class ScreeningService
{
    /**
     * Get paginated screenings based on user role and filters.
     */
    public function getPaginatedScreenings($user, array $filters = [], string $sort = 'created_at', string $direction = 'desc', int $perPage = 15)
    {
        $query = Screening::filterByRole($user)
            ->with(['respondent.healthPost', 'respondent.village', 'screeningPeriod']);

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('respondent', function (Builder $q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%");
            });
        }

        // Filter by Severity
        if (!empty($filters['severity'])) {
            $severity = $filters['severity'];
            $query->where(function (Builder $q) use ($severity) {
                $q->where('dm_severity', $severity)
                  ->orWhere('ht_severity', $severity);
            });
        }

        // Sorting
        $allowedSorts = ['created_at', 'fullname', 'dm_severity', 'ht_severity', 'recommendation_level', 'action_status'];
        if (in_array($sort, $allowedSorts)) {
            if ($sort === 'fullname') {
                $query->join('respondents', 'screenings.respondent_id', '=', 'respondents.id')
                      ->orderBy('respondents.fullname', $direction === 'asc' ? 'asc' : 'desc')
                      ->select('screenings.*'); // Ensure we don't select respondent columns over screening columns
            } else {
                $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get screening details by ID.
     */
    public function getScreeningDetails($user, $id)
    {
        return Screening::filterByRole($user)
            ->with('respondent.healthPost', 'respondent.village')
            ->findOrFail($id);
    }

    /**
     * Delete screening.
     */
    public function deleteScreening($user, $id)
    {
        $screening = $this->getScreeningDetails($user, $id);
        return $screening->delete();
    }
}
