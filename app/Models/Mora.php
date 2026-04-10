<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class Mora extends BaseModel
{
    use HasFactory, SoftDeletes;
 
    protected $table = 'moras';
 
    protected $fillable = [
        'organization_id',
        'miembro_id',
        'periodo',
        'monto_original',
        'monto_pendiente',
        'estado',
        'suscripcion_id',
        'aportacion_id',
        'mes_referencia',
    ];
 
    protected $casts = [
        'monto_original' => 'decimal:2',
        'monto_pendiente' => 'decimal:2',
    ];
 
    public function miembro()
    {
        return $this->belongsTo(Miembros::class, 'miembro_id');
    }
 
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
