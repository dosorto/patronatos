<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoActivo extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_activos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}