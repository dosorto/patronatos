<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsistenciaJornada extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'asistencias_jornada';

    protected $fillable = [
        'jornada_id',
        'miembro_id',
        'asistio',
        'mando_sustituto',
        'nombre_sustituto',
        'observaciones',
    ];

    protected $casts = [
        'asistio'          => 'boolean',
        'mando_sustituto'  => 'boolean',
    ];

    // ── Relaciones ──────────────────────────────────────────
    public function jornada()
    {
        return $this->belongsTo(JornadaTrabajo::class, 'jornada_id');
    }

    public function miembro()
    {
        return $this->belongsTo(Miembros::class, 'miembro_id');
    }
}
