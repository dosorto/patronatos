<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presupuesto extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'presupuestos';

    protected $fillable = [
        'proyecto_id',
        'anio_presupuesto',
        'presupuesto_total',
        'monto_financiador',
        'monto_comunidad',
        'porcentaje_financiador',
        'porcentaje_comunidad',
        'estado',
        'fecha_aprobacion',
        'es_donacion',
        'id_cooperante',
    ];

    protected $casts = [
        'fecha_aprobacion'  => 'date',
        'es_donacion'       => 'boolean',
        'presupuesto_total' => 'decimal:2',
        'monto_financiador' => 'decimal:2',
        'monto_comunidad'   => 'decimal:2',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
    
    public function detalles()
    {
        return $this->hasMany(DetallePresupuesto::class, 'presupuesto_id');
    }
}
