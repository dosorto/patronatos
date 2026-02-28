<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cooperante extends BaseModel
{
    protected $table = 'cooperantes';
    protected $primaryKey = 'id_cooperante';

    protected $fillable = [
        'id_organizacion',
        'nombre',
        'tipo_cooperante',
        'telefono',
        'direccion',
    ];

    // Relación con Organizacion
    public function organizacion()
    {
        // Asegúrate de que el modelo Organizacion use 'id_organizacion' como PK
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'id_organizacion');
    }
}