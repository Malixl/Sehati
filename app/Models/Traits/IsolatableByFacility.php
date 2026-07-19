<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait IsolatableByFacility
{
    protected static function bootIsolatableByFacility()
    {
        static::addGlobalScope('facility_isolation', function (Builder $builder) {
            $user = Auth::user();

            if (!$user) {
                return; // Console or unauthenticated
            }

            if (in_array($user->role, ['owner', 'super_admin'])) {
                return; // Global Access
            }

            $modelClass = get_class($builder->getModel());

            if (in_array($user->role, ['operator_puskesmas', 'admin_puskesmas']) && $user->healthPost) {
                $districtId = $user->healthPost->district_id;
                if ($modelClass === \App\Models\Respondent::class) {
                    $builder->whereHas('village', function ($q) use ($districtId) {
                        $q->where('district_id', $districtId);
                    });
                } elseif ($modelClass === \App\Models\Screening::class) {
                    $builder->whereHas('respondent.village', function ($q) use ($districtId) {
                        $q->where('district_id', $districtId);
                    });
                }
            }

            if (in_array($user->role, ['operator_posyandu', 'admin_posyandu']) && $user->healthPost) {
                $villageId = $user->healthPost->village_id;
                if ($modelClass === \App\Models\Respondent::class) {
                    $builder->where('village_id', $villageId);
                } elseif ($modelClass === \App\Models\Screening::class) {
                    $builder->whereHas('respondent', function ($q) use ($villageId) {
                        $q->where('village_id', $villageId);
                    });
                }
            }
        });
    }
}
