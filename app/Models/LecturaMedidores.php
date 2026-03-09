<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LecturaMedidores extends BaseModel
{
    protected $fillable = [
        'fecha_lectura',
        'lectura_anterior',
        'lectura_actual',
        'consumo',
        'medidor_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'fecha_lectura' => 'date',
        'lectura_anterior' => 'decimal:2',
        'lectura_actual' => 'decimal:2',
        'consumo' => 'decimal:2',
    ];

    public function medidor()
    {
        return $this->belongsTo(Medidores::class, 'medidor_id');
    }
}
