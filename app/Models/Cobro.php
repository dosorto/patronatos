<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cobro extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'cobros';

    protected $fillable = [
        'organization_id',
        'miembro_id',
        'fecha_cobro',
        'tipo_cobro',
        'total',
    ];

    protected $casts = [
        'fecha_cobro' => 'date',
        'total' => 'decimal:2',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function miembro()
    {
        return $this->belongsTo(Miembros::class, 'miembro_id');
    }

    public function detallesCobros()
    {
        return $this->hasMany(DetalleCobro::class, 'cobro_id');
    }

    public function recibos()
    {
        return $this->hasMany(Recibo::class, 'cobro_id');
    }

    public function aportaciones()
    {
        return $this->hasMany(Aportacion::class, 'cobro_id');
    }
}
