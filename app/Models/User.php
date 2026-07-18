<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'district_id',
        'health_post_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function healthPost()
    {
        return $this->belongsTo(HealthPost::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin' || $this->role === 'owner';
    }

    public function isAdminPosyandu(): bool
    {
        return $this->role === 'admin_posyandu';
    }
}
