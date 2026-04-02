<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activo extends BaseModel
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'ubicacion',
        'fecha_adquisicion',
        'valor_estimado',
        'estado',
        'organization_id',
        'tipo_activo_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // <-- Esto convierte automáticamente los campos a los tipos correctos
    protected $casts = [
        'fecha_adquisicion' => 'datetime', // se convierte en Carbon
        'estado' => 'boolean',             // se convierte en true/false
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function tipoActivo()
    {
        return $this->belongsTo(TipoActivo::class, 'tipo_activo_id');
    }
}