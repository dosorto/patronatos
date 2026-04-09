<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    use HasFactory;

    protected $table = 'suscripciones';

    protected $fillable = [
        'miembro_id',
        'servicio_id',
        'medidor_id',
        'identificador',
        'fecha_inicio',
        'ultimo_mes_pagado',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'ultimo_mes_pagado' => 'date',
        'estado' => 'boolean',
    ];

    public function miembro()
    {
        return $this->belongsTo(Miembros::class, 'miembro_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function medidor()
    {
        return $this->belongsTo(Medidores::class, 'medidor_id');
    }
}
