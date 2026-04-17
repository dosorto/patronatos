<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleCobro extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'detalle_cobros';

    protected $fillable = [
        'cobro_id',
        'servicio_id',
        'id_cooperante',
        'periodo',
        'concepto',
        'monto_original',
        'monto_ajuste',
        'tipo_ajuste',
        'monto',
        'es_donacion',
    ];

    protected $casts = [
        'monto_original' => 'decimal:2',
        'monto_ajuste'   => 'decimal:2',
        'monto'          => 'decimal:2',
        'es_donacion'    => 'boolean',
    ];

    public function cobro()
    {
        return $this->belongsTo(Cobro::class, 'cobro_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function cooperante()
    {
        return $this->belongsTo(Cooperante::class, 'id_cooperante', 'id_cooperante');
    }
}