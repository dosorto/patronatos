<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoActivo extends BaseModel
{
    use HasFactory;

    protected $table = 'tipo_activos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}