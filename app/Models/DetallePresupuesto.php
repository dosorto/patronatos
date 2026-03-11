<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallePresupuesto extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'detalle_presupuestos';

    protected $fillable = [
        'presupuesto_id',
        'nombre',
        'cantidad',
        'unidad_medida',
        'precio_unitario',
        'total',
        'observaciones',
    ];

    protected $casts = [
        'cantidad'        => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'total'           => 'decimal:2',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'presupuesto_id');
    }
}
