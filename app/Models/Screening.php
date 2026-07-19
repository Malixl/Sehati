<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\IsolatableByFacility;

class Screening extends Model
{
    use HasFactory, IsolatableByFacility;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'screened_at' => 'datetime',
        'a_diabetes' => 'boolean',
        'a_hipertensi' => 'boolean',
        'a_jantung' => 'boolean',
        'a_stroke' => 'boolean',
        'a_kolesterol' => 'boolean',
        'b_diabetes' => 'boolean',
        'b_hipertensi' => 'boolean',
        'b_jantung' => 'boolean',
        'b_stroke' => 'boolean',
        'b_asma' => 'boolean',
        'b_kolesterol' => 'boolean',
        'c_merokok' => 'boolean',
        'calculated_at' => 'datetime',
    ];

    public function respondent()
    {
        return $this->belongsTo(Respondent::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function followUp()
    {
        return $this->hasOne(FollowUp::class);
    }

    public function screeningPeriod()
    {
        return $this->belongsTo(ScreeningPeriod::class);
    }

    public function scopeFilterByRole($query, $user)
    {
        if ($user && $user->isAdminPosyandu()) {
            return $query->whereHas('respondent', function ($q) use ($user) {
                $q->where('health_post_id', $user->health_post_id);
            });
        }
        return $query;
    }
}
