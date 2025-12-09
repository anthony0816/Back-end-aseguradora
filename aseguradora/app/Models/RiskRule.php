<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by_user_id',
        'rule_type_id',
        'name',
        'description',
        'severity',
        'is_active',
        'parameter_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function ruleType()
    {
        return $this->belongsTo(RiskRuleSlug::class, 'rule_type_id');
    }

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }

    public function actions()
    {
        return $this->belongsToMany(RiskAction::class, 'rule_actions', 'risk_rule_id', 'risk_action_id');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}
