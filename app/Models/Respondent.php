<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id', 'nik', 'fullname', 'birthdate',
        'gender', 'phone', 'address', 'village_id', 'health_post_id'
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function healthPost()
    {
        return $this->belongsTo(HealthPost::class);
    }

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function scopeFilterByRole($query, $user)
    {
        if ($user && $user->isAdminPosyandu()) {
            return $query->where('health_post_id', $user->health_post_id);
        }
        return $query;
    }
}
