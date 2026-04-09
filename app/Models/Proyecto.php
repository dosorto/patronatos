<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'proyectos';

    protected $fillable = [
        'organization_id',
        'nombre_proyecto',
        'tipo_proyecto',
        'descripcion',
        'justificacion',
        'descripcion_beneficiarios',
        'benef_hombres',
        'benef_mujeres',
        'benef_ninos',
        'benef_familias',
        'fecha_aprobacion_asamblea',
        'numero_acta',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'departamento_id',
        'municipio_id',
        'miembro_responsable_id',
    ];

    protected $casts = [
        'fecha_aprobacion_asamblea' => 'date',
        'fecha_inicio'              => 'date',
        'fecha_fin'                 => 'date',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function miembroResponsable()
    {
        return $this->belongsTo(Directiva::class, 'miembro_responsable_id');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'proyecto_id');
    }

    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class, 'proyecto_id');
    }

    public function configuracionAportacion()
    {
        return $this->hasOne(ConfiguracionAportacion::class, 'proyecto_id');
    }

    public function aportaciones()
    {
        return $this->hasMany(Aportacion::class, 'proyecto_id');
    }

    public function jornadasTrabajo()
    {
        return $this->hasMany(JornadaTrabajo::class, 'proyecto_id');
    }
}