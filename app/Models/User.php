<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

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
        'birthdate',
        'phone',
        'status',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'email_verified_at',
        'remember_token',
    ];

    public function accessCodes()
    {
        return $this->hasMany(AccessCode::class);
    }

    public function luckyNumber()
    {
        return $this->hasOne(LuckyNumber::class);
    }

    public function validationCodes()
    {
        return $this->hasMany(ValidationCode::class);
    }
}
