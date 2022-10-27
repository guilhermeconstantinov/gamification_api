<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AccessCode extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'status',
        'code',
        'revoked',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
