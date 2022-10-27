<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuckyNumber extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'number', 'drawn', 'date', 'raffle_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
