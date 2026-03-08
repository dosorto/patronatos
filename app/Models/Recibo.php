<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recibo extends BaseModel
{
    use HasFactory;

    protected $table = 'recibos';

    protected $fillable = [
        'pago_id',
        'cobro_id',
        'correlativo',
        'nombre',
        'fecha_emision',
        'anio',
        'monto',
        'observaciones',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'monto' => 'decimal:2',
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }

    public function cobro()
    {
        return $this->belongsTo(Cobro::class, 'cobro_id');
    }
}
