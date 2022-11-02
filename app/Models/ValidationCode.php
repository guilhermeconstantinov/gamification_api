<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'revoked',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
