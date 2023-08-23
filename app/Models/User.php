<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'mobile',
        'p_address',
        't_address',
        'qualification',
        'contact_person',
        'contact_person_details',
        'gender',
        'dob',
        'religion',
        'department',
        'office_start_time',
        'office_end_time',
        'designation',
        'status',
        'report_for',
        'date_joined',
        'date_released',
        'extra'

    ];


    public function attendences(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function daily_reports(): HasMany
    {
        return $this->hasMany(DailyReport::class);
    }


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
}
