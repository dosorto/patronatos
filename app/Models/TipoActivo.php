<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function activos(): HasMany
    {
        return $this->hasMany(Activo::class, 'tipo_activo_id');
    }
}