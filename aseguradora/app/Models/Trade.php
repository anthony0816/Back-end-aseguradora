<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'type',
        'volume',
        'open_time',
        'close_time',
        'open_price',
        'close_price',
        'status',
    ];

    protected $casts = [
        'open_time' => 'datetime',
        'close_time' => 'datetime',
        'volume' => 'decimal:4',
        'open_price' => 'decimal:5',
        'close_price' => 'decimal:5',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}
