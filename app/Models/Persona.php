<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Persona extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'email',
        'estado',
        'fecha_ingreso',
    ];



    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
    ];

    /**
     * Get the persona's full name.
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Get the formatted DNI with hyphens (XXXX-XXXX-XXXXX).
     */
    public function getFormattedDniAttribute(): string
    {
        $dni = preg_replace('/[^0-9]/', '', $this->dni);
        if (strlen($dni) === 13) {
            return substr($dni, 0, 4) . '-' . substr($dni, 4, 4) . '-' . substr($dni, 8);
        }
        return $this->dni;
    }

    /**
     * Set the DNI attribute, stripping non-numeric characters.
     */
    public function setDniAttribute($value)
    {
        $this->attributes['dni'] = preg_replace('/[^0-9]/', '', $value);
    }

 

    public function miembros()
    {
        return $this->hasMany(Miembros::class, 'persona_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'persona_id');
    }
}
