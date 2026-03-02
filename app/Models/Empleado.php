<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends BaseModel
{
    use SoftDeletes;

    protected $table = 'empleados';

    protected $fillable = [
        'persona_id',
        'organizacion_id',
        'cargo',
        'sueldo_mensual'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id');
    }

    
}
