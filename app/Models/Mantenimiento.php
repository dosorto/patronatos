<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\TracksAuditMetadata;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mantenimiento extends BaseModel
{
    use HasFactory, SoftDeletes, Auditable, TracksAuditMetadata;

    protected $table = 'mantenimientos';

    protected $fillable = [
        'organization_id',
        'activo_id',
        'tipo_mantenimiento',
        'descripcion',
        'prioridad',
        'fecha_registro',
        'estado',
        'costo_estimado',
        'pago_id',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'costo_estimado' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->organization_id) {
                $model->organization_id = session('tenant_organization_id');
            }

            if (!$model->estado) {
                $model->estado = 'Activo';
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function activo(): BelongsTo
    {
        return $this->belongsTo(Activo::class, 'activo_id');
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }

    public function detallesPago(): HasMany
    {
        return $this->hasMany(DetallePago::class, 'mantenimiento_id');
    }
}