<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User; // 🚨 AÑADE ESTO (o la ruta correcta a tu modelo User)

class Notification extends Model
{
    protected $table = 'notification';

    protected $fillable = [
        'user_id',
        'metadata',
        'mensaje',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        // 🚀 Ahora se resuelve correctamente gracias al 'use'
        return $this->belongsTo(User::class); 
    }
}