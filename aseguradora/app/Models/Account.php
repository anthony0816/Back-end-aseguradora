<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User; // Asegurar que el modelo User esté importado

class Account extends Model
{
    protected $table = 'account';

    protected $fillable = [
        'owner_id',
        'login',
        'trading_status',
        'status',
    ];

    protected $casts = [
        'login' => 'integer',
    ];

    // --- Relaciones ---

    /**
     * Una cuenta pertenece a un usuario (el dueño).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Una cuenta tiene muchos trades.
     */
    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class, 'account_id');
    }
}