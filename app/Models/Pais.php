<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends BaseModel
{
    use SoftDeletes;

    protected $table = 'pais';
    
    protected $fillable = [
        'nombre',
    ];

    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }
}


