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
        'periodo',
        'concepto',
        'monto',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    public function cobro()
    {
        return $this->belongsTo(Cobro::class, 'cobro_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
