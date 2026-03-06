<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Directiva extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'directivas';

    protected $fillable = [
        'miembro_id',
        'organization_id',
        'cargo',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Relación con el miembro.
     */
    public function miembro()
    {
        return $this->belongsTo(Miembros::class, 'miembro_id');
    }

    /**
     * Relación con la organización.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    // hasmany a proyectos
    public function proyectos(): hasMany
    {
        return $this->hasMany(Proyecto::class, 'miembro_responsable_id');
    }

    /**
     * Verifica si la directiva está activa según la fecha de finalización.
     */
    public function isActive(): bool
    {
        if (!$this->fecha_fin) return true;
        return $this->fecha_fin->isFuture() || $this->fecha_fin->isToday();
    }
}
