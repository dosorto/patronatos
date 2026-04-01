<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Aportacion extends Model {
    use HasFactory;

    protected $table      = 'aportaciones';
    protected $primaryKey = 'id_aportacion';

    protected $fillable = [
        'id_miembro',
        'id_proyecto',
        'id_cobro',        // ← NUEVA
        'monto',
        'fecha_aportacion',
        'estado',
    ];

    protected $casts = [
        'fecha_aportacion' => 'date',
        'monto'            => 'decimal:2',
        'estado'           => 'boolean',
    ];

    // ── Relaciones ──────────────────────────────────────────
    public function miembro()
    {
        return $this->belongsTo(Miembros::class, 'id_miembro', 'id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id');
    }

    public function cobro() {
        return $this->belongsTo(Cobro::class, 'id_cobro', 'id');
    }
}