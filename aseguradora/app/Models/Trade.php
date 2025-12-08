<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Account; // Asegurar que el modelo Account esté importado

class Trade extends Model
{
    protected $table = 'trade';

    protected $fillable = [
        'account_id',
        'type',
        'volume',
        'open_time',
        'close_time',
        'open_price',
        'close_price',
        'status',
    ];

    protected $casts = [
        'volume' => 'decimal:4',
        'open_time' => 'datetime',
        'close_time' => 'datetime',
        'open_price' => 'decimal:5',
        'close_price' => 'decimal:5',
    ];

    // --- Relación ---

    /**
     * Un trade pertenece a una Account.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}