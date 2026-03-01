<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends BaseModel
{
    use SoftDeletes;

    protected $table = 'municipios';

    protected $fillable = [
        'nombre',
        'departamento_id',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function miembros()
    {
        return $this->hasMany(Miembros::class, 'municipio_id');
    }

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'id_municipio');
    }
}

