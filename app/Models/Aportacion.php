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
    public function miembro() {
        return $this->belongsTo(Miembro::class, 'id_miembro', 'id_miembro');
    }

    public function proyecto() {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }
}
