<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organization extends Model
{
    protected $connection = 'mysql';
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'db_connection',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
        'id_tipo_organizacion',
        'id_municipio',
        'id_departamento',
        'direccion',
        'rtn',
        'telefono',
        'fecha_creacion',
        'estado',
        'logo',
        'meses_mora',
        'dias_pago',
        'plan_name',
        'subscription_status',
        'subscription_expires_at',
        'max_households',
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
        'subscription_expires_at' => 'date',
    ];

    /**
     * Verifica si la suscripción está activa y vigente.
     */
    public function isSubscriptionActive(): bool
    {
        return $this->subscription_status === 'active' && 
               $this->subscription_expires_at && 
               $this->subscription_expires_at->isFuture();
    }

    /**
     * Verifica si la cuenta ha sido suspendida manualmente.
     */
    public function isSuspended(): bool
    {
        return $this->subscription_status === 'suspended';
    }

    /**
     * Retorna los días restantes de servicio (periodos de 24h).
     */
    public function daysRemaining(): int
    {
        if (!$this->subscription_expires_at) return 0;
        return (int) now()->diffInDays($this->subscription_expires_at, false);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function activos(): HasMany
    {
        return $this->hasMany(Activo::class, 'organization_id');
    }

    public function miembros(): HasMany
    {
        return $this->hasMany(Miembros::class, 'organization_id');
    }

    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class, 'organization_id');
    }
    
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'id_municipio');
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function tipoOrganizacion(): BelongsTo
    {
        return $this->belongsTo(TipoOrganizacion::class, 'id_tipo_organizacion', 'id_tipo_organizacion');
    }

    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class, 'organization_id');
    }

    public function cobros(): HasMany
    {
        return $this->hasMany(Cobro::class, 'organization_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'organization_id');
    }

    
}