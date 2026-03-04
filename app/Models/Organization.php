<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organization extends Model
{
    protected $connection = 'mysql';
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'db_connection',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
        'id_tipo_organizacion',
        'id_municipio',
        'id_departamento',
        'direccion',
        'rtn',
        'telefono',
        'fecha_creacion',
        'estado',
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function activos(): HasMany
    {
        return $this->hasMany(Activo::class, 'organization_id');
    }

    public function miembros(): HasMany
    {
        return $this->hasMany(Miembros::class, 'organization_id');
    }

    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class, 'organization_id');
    }
    
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'id_municipio');
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function tipoOrganizacion(): BelongsTo
    {
        return $this->belongsTo(TipoOrganizacion::class, 'id_tipo_organizacion', 'id_tipo_organizacion');
    }
}