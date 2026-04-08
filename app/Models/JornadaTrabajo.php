<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class JornadaTrabajo extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'jornadas_trabajo';

    protected $fillable = [
        'proyecto_id',
        'numero_jornada',
        'fecha',
        'hora_inicio',
        'descripcion',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha'       => 'date',
        'hora_inicio' => 'datetime:H:i',
    ];

    // ── Relaciones ──────────────────────────────────────────
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function asistencias()
    {
        return $this->hasMany(AsistenciaJornada::class, 'jornada_id');
    }
}
