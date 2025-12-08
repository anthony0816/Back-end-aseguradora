<?php

namespace App\Models;

// Importamos BelongsToMany, HasMany, etc.
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 🚨 Importante: Indica a Eloquent que la tabla se llama 'user' (singular)
    protected $table = 'user';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser casteados.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean', // Casteamos is_admin a booleano
    ];

    // --- Relaciones Eloquent ---

    /**
     * Un usuario puede tener muchas cuentas de trading.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'owner_id');
    }

    /**
     * Un usuario puede crear muchas reglas de riesgo.
     */
    public function createdRiskRules(): HasMany
    {
        return $this->hasMany(RiskRule::class, 'created_by_user_id');
    }

    /**
     * Un usuario puede tener muchas notificaciones.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}