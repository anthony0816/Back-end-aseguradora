<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterVolumeTrade extends Model
{
    use HasFactory;

    protected $primaryKey = 'parameter_id';
    public $incrementing = false;

    protected $fillable = [
        'parameter_id',
        'min_factor',
        'max_factor',
        'lookback_trades',
    ];

    protected $casts = [
        'min_factor' => 'decimal:4',
        'max_factor' => 'decimal:4',
    ];

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}
