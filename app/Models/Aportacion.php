<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aportacion extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'aportaciones';

    protected $fillable = [
        'cobro_id',
        'miembro_id',
        'proyecto_id',
        'monto',
        'monto_asignado',
        'monto_pagado',
        'fecha_aportacion',
        'estado',
    ];

    protected $casts = [
        'fecha_aportacion' => 'date',
        'monto'            => 'decimal:2',
        'monto_asignado'   => 'decimal:2',
        'monto_pagado'     => 'decimal:2',
    ];

    // ── Relaciones ──────────────────────────────────────────
    public function miembro()
    {
        return $this->belongsTo(Miembros::class, 'miembro_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function cobro()
    {
        return $this->belongsTo(Cobro::class, 'cobro_id');
    }
}