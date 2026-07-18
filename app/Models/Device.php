<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'last_used_at'];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }
}
