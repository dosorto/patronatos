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

    // Conversores de dinero decimal a entero y viceversa
    // Guarda en centavos (multiplica x100)
    // public function setSueldoMensualAttribute($value)
    // {
    //     $this->attributes['sueldo_mensual'] = $value * 100;
    // }

    // // Muestra como decimal (divide x100)
    // public function getSueldoMensualAttribute($value)
    // {
    //     return $value / 100;
    // }
}
