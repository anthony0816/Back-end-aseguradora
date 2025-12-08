<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliza extends Model
{
    use HasFactory;


    // Nombre de la tabla (por defecto sería 'polizas', pero lo definimos para claridad)
    protected $table = 'polizas'; 

    // Campos que se pueden asignar masivamente (llenar con POST/PUT)
    protected $fillable = [
        'numero_poliza',
        'cliente',
        'monto',
        'fecha_inicio',
        'fecha_fin',
    ];

}
