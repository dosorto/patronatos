<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organizacion extends BaseModel
{
    use HasFactory;

    protected $table = 'organizacion';
    protected $primaryKey = 'id_organizacion';

    protected $fillable = [
        'id_tipo_organizacion',
        'id_municipio',
        'id_departamento',
        'direccion',
        'nombre',
        'rtn',
        'telefono',
        'fecha_creacion',
        'estado',
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
    ];


    public function getDeletedAtColumn()
    {
        return 'deleted_at';
    }

    public function tipoOrganizacion()
    {
        return $this->belongsTo(TipoOrganizacion::class, 'id_tipo_organizacion', 'id_tipo_organizacion');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio', 'id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id');
    }

    public function miembros()
    {
        return $this->hasMany(Miembros::class, 'organizacion_id');
    }
}