<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Miembros extends BaseModel
{
    use SoftDeletes;
    protected $table = 'miembros';

    
    protected $fillable = [
        'direccion',
        'estado',
        'persona_id',
        'organizacion_id',
        'municipio_id',
    ];

    public function getEstadoAttribute($value): string
    {
        if (is_null($value)) return 'N/A';
        return in_array($value, ['1', 1, true], true) || strtolower($value) === 'activo' ? 'Activo' : 'Inactivo';
    }
    // Relaciones
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
}