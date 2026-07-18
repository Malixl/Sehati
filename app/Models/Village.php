<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = ['district_id', 'code', 'name', 'target_usia_produktif', 'target_ht', 'target_dm'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function healthPosts()
    {
        return $this->hasMany(HealthPost::class);
    }

    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }
}
