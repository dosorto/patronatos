<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medidores extends BaseModel
{
    protected $fillable = [
        'numero_medidor',
        'fecha_instalacion',
        'estado',
        'unidad_medida',
        'precio_unidad_medida',
        'miembro_id',
        'servicio_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'fecha_instalacion' => 'date',
        'precio_unidad_medida' => 'decimal:2',
    ];

    public function miembro()
    {
        return $this->belongsTo(Miembros::class, 'miembro_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function lecturaMedidores()
    {
        return $this->hasMany(LecturaMedidores::class, 'medidor_id');
    }
}
