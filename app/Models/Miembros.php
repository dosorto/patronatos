<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Miembros extends BaseModel
{
    use SoftDeletes;
    protected $table = 'miembros';

    protected $fillable = [
        'direccion',
        'estado',
        'persona_id',
        'organization_id', // cambiamos de 'organizacion_id'
    ];

    public function getEstadoAttribute($value): string
    {
        if (is_null($value)) return 'N/A';
        return in_array($value, ['1', 1, true], true) || strtolower($value) === 'activo' ? 'Activo' : 'Inactivo';
    }

    /**
     * Scope para filtrar miembros activos/habilitados
     */
    public function scopeActivos($query)
    {
        return $query->whereRaw("(estado = 1 OR estado = '1' OR LOWER(estado) = 'activo')");
    }

    // Relaciones
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function cobros()
    {
        return $this->hasMany(Cobro::class, 'miembro_id');
    }

    public function medidores()
    {
        return $this->hasMany(Medidores::class, 'miembro_id');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'miembro_id');
    }

    public function aportaciones()
    {
        return $this->hasMany(Aportacion::class, 'miembro_id');
    }

    public function moras()
    {
        return $this->hasMany(Mora::class, 'miembro_id');
    }
}