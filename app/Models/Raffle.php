<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Raffle extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'date'];

    public function luckyNumbers(): HasMany
    {
        return $this->hasMany(LuckyNumber::class, 'raffle_id');
    }
}
