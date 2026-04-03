<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetallePago extends BaseModel
{
    use HasFactory;

    protected $table = 'detalle_pagos';

    protected $fillable = [
        'pago_id',
        'tipo_detalle',
        'empleado_id',
        'mantenimiento_id',
        'concepto',
        'descripcion',
        'monto',
        'periodo',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function mantenimiento()
    {
        return $this->belongsTo(Mantenimiento::class, 'mantenimiento_id');
    }
}