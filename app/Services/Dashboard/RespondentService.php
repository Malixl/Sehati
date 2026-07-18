<?php

namespace App\Services\Dashboard;

use App\Models\Respondent;
use Illuminate\Database\Eloquent\Builder;

class RespondentService
{
    /**
     * Get paginated respondents based on user role and filters.
     */
    public function getPaginatedRespondents($user, array $filters = [], string $sort = 'created_at', string $direction = 'desc', int $perPage = 10)
    {
        $query = Respondent::filterByRole($user)
            ->with('healthPost', 'village')
            ->withCount('screenings');

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%");
            });
        }

        // Filter by Village (Desa)
        if (!empty($filters['village_id'])) {
            $query->where('village_id', $filters['village_id']);
        }

        // Filter by Gender
        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        // Sorting
        $allowedSorts = ['fullname', 'created_at', 'screenings_count'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get respondent details by ID.
     */
    public function getRespondentDetails($user, $id)
    {
        return Respondent::filterByRole($user)
            ->with(['healthPost', 'village', 'screenings' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->findOrFail($id);
    }
}
