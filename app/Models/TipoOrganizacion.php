<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoOrganizacion extends BaseModel
{
    use HasFactory;

    protected $table = 'tipo_organizacion';
    protected $primaryKey = 'id_tipo_organizacion';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}