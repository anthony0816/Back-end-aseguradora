<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'risk_rule_id',
        'trade_id',
        'count',
        'triggered_value',
        'is_executed',
    ];

    protected $casts = [
        'is_executed' => 'boolean',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function riskRule()
    {
        return $this->belongsTo(RiskRule::class);
    }

    public function trade()
    {
        return $this->belongsTo(Trade::class);
    }
}
