<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'phone',
        'email',
        'password',
        'address',
        'gender',
        'birthday',
        'total_amount',
        'type_user',
        'cinema_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    const ROLE = [
        'System Admin',
        'Cinema Manager',
        'Staff'
    ];

    const TYPE_ADMIN = 1;
    const TYPE_MEMBER = 0;

    public function isAdmin()
    {
        return $this->type_user === self::TYPE_ADMIN;
    }

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function pointHistories()
    {
        return $this->hasMany(Point_history::class);
    }


    public function rank()
    {
        return $this->belongsTo(Rank::class, 'total_amount', 'total_spent');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
