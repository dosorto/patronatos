<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends BaseModel
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'organization_id',
        'persona_id',
        'empleado_id',
        'fecha_pago',
        'tipo_pago',
        'total',
        'id_tipo_movimiento',
        'nombre_persona',
        'descripcion',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'total' => 'decimal:2',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePago::class, 'pago_id');
    }

    public function recibo()
    {
        return $this->hasOne(Recibo::class, 'pago_id');
    }

    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class, 'pago_id');
    }
}