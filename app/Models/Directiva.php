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
}
