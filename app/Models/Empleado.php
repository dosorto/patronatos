<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends BaseModel
{
    use SoftDeletes;

    protected $table = 'empleados';

    protected $fillable = [
        'persona_id',
        'organization_id',
        'cargo',
        'sueldo_mensual',
        'frecuencia_pago',
        'ultimo_mes_pagado',
    ];

    protected $casts = [
        'ultimo_mes_pagado' => 'date',
        'sueldo_mensual' => 'decimal:2',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'empleado_id');
    }

    public function detallesPago()
    {
        return $this->hasMany(DetallePago::class, 'empleado_id');
    }

    
}
