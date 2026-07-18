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


}
