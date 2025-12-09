<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'login',
        'trading_status',
        'status',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}