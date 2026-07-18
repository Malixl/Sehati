<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = ['screening_id', 'user_id', 'status', 'note'];

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterByRole($query, $user)
    {
        if ($user && $user->isAdminPosyandu()) {
            return $query->whereHas('screening.respondent', function ($q) use ($user) {
                $q->where('health_post_id', $user->health_post_id);
            });
        }
        return $query;
    }
}
