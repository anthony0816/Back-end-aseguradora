<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiskRuleSlug extends Model
{
    protected $table = 'risk_rule_slug';
    
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Un slug define el tipo de muchas reglas de riesgo.
     */
    public function riskRules(): HasMany
    {
        return $this->hasMany(RiskRule::class, 'rule_type_id');
    }
}