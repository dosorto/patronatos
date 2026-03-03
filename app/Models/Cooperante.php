<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cooperante extends BaseModel
{
    protected $table = 'cooperantes';
    protected $primaryKey = 'id_cooperante';

    protected $fillable = [
        'organization_id',
        'nombre',
        'tipo_cooperante',
        'telefono',
        'direccion',
    ];

    // Relación con Organization
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}