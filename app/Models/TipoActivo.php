<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoActivo extends Model
{
    use HasFactory;

    protected $table = 'tipo_activos';
    
    protected $fillable = ['nombre', 'descripcion'];
}