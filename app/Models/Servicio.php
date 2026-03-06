<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'servicios';

    protected $fillable = [
        'organization_id',
        'nombre',
        'descripcion',
        'precio',
        'estado',
        'tiene_medidor',
        'unidad_medida',
        'precio_por_unidad_de_medida',
        'es_aportacion',
        'proyecto_id',
    ];

    protected $casts = [
        'tiene_medidor' => 'boolean',
        'es_aportacion' => 'boolean',
        'precio' => 'decimal:2',
        'precio_por_unidad_de_medida' => 'decimal:2',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function detallesCobros()
    {
        return $this->hasMany(DetalleCobro::class, 'servicio_id');
    }
}
