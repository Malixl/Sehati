<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreeningPeriod extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function scopeCurrentlyActive($query)
    {
        return $query->where('is_active', true)
                     ->whereDate('start_date', '<=', \Carbon\Carbon::today())
                     ->whereDate('end_date', '>=', \Carbon\Carbon::today());
    }

    public function getIsCurrentlyActiveAttribute()
    {
        if (!$this->is_active) return false;
        
        $today = \Carbon\Carbon::today();
        $start = \Carbon\Carbon::parse($this->start_date)->startOfDay();
        $end = \Carbon\Carbon::parse($this->end_date)->endOfDay();
        
        return $today->betweenIncluded($start, $end);
    }

    public function getIsExpiredAttribute()
    {
        return \Carbon\Carbon::parse($this->end_date)->endOfDay()->isPast();
    }
}
