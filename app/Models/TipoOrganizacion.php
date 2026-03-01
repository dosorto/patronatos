<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoOrganizacion extends BaseModel
{
    use HasFactory;

    protected $table = 'tipo_organizacion';
    protected $primaryKey = 'id_tipo_organizacion';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'id_tipo_organizacion', 'id_tipo_organizacion');
    }
}