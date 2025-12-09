<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    public function durationParameter()
    {
        return $this->hasOne(DurationParameter::class);
    }

    public function volumeTradeParameter()
    {
        return $this->hasOne(ParameterVolumeTrade::class);
    }

    public function timeRangeOperationParameter()
    {
        return $this->hasOne(ParameterTimeRangeOperation::class);
    }

    public function riskRule()
    {
        return $this->hasOne(RiskRule::class);
    }
}
