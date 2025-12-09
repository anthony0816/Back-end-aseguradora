<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterTimeRangeOperation extends Model
{
    use HasFactory;

    protected $primaryKey = 'parameter_id';
    public $incrementing = false;

    protected $fillable = [
        'parameter_id',
        'time_window_minutes',
        'min_open_trades',
        'max_open_trades',
    ];

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}
