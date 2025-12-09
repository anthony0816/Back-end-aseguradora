<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DurationParameter extends Model
{
    use HasFactory;

    protected $primaryKey = 'parameter_id';
    public $incrementing = false;

    protected $fillable = [
        'parameter_id',
        'duration',
    ];

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}
