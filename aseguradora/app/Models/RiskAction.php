<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function riskRules()
    {
        return $this->belongsToMany(RiskRule::class, 'rule_actions', 'risk_action_id', 'risk_rule_id');
    }
}
