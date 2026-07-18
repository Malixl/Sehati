<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'village_id', 'code', 'name', 'address',
        'latitude', 'longitude', 'pic_name', 'phone', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }

    public function scopeFilterByRole($query, $user)
    {
        if ($user && $user->isAdminPosyandu()) {
            return $query->where('id', $user->health_post_id);
        }
        return $query;
    }
}
