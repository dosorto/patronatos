<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfiguracionAportacion extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'configuracion_aportacion';

    protected $fillable = [
        'proyecto_id',
        'tipo_distribucion',
        'monto_total_requerido',
        'fecha_limite',
        'observaciones',
    ];

    protected $casts = [
        'monto_total_requerido' => 'decimal:2',
        'fecha_limite'          => 'date',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}
