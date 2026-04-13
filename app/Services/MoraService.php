<?php

namespace App\Services;

use App\Models\Miembros;
use App\Models\Mora;
use App\Models\Organization;
use App\Models\Suscripcion;
use App\Models\Aportacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MoraService
{
    /**
     * Sincroniza las moras para todos los miembros activos de la organización actual.
     */
    public function syncAllMembers()
    {
        $orgId = session('tenant_organization_id');
        
        if (!$orgId) {
            Log::warning('Intento de correr syncAllMembers sin tenant auth');
            return;
        }

        $miembros = Miembros::where('organization_id', $orgId)->activos()->get();

        foreach ($miembros as $miembro) {
            $this->syncMember($miembro->id);
        }
    }

    /**
     * Sincroniza las moras de un miembro específico basado en sus suscripciones y aportaciones.
     */
    public function syncMember($miembroId)
    {
        $miembro = Miembros::with(['suscripciones.servicio', 'aportaciones'])->find($miembroId);

        if (!$miembro) return;

        $orgId = $miembro->organization_id;
        $mesActual = Carbon::now()->startOfMonth();

        // Obtener la configuración de meses de gracia de la organización
        $org = Organization::on('mysql')->find($orgId);
        $mesesGracia = $org->meses_mora ?? 1;

        // 1. Sincronizar Suscripciones
        foreach ($miembro->suscripciones as $suscripcion) {
            if (!$suscripcion->estado) continue; // Solo activas?
            // Si quieres que funcione incluso para las inactivas que dejaron deudas, 
            // no omitir. Para Patronatos, dejémoslo general, pero usualmente las moras 
            // no se generan nuevas para inactivos, solo se arrastran.

            $ultimoMesPagado = $suscripcion->ultimo_mes_pagado 
                                ? Carbon::parse($suscripcion->ultimo_mes_pagado)->startOfMonth() 
                                : $mesActual;

            // A) Cancelar moras viejas si se avanzó el último mes pagado.
            Mora::where('suscripcion_id', $suscripcion->id)
                ->where('mes_referencia', '<=', $ultimoMesPagado)
                ->where('estado', 'Pendiente')
                ->update([
                    'estado' => 'Cancelado',
                    'monto_pendiente' => 0
                ]);

            // B) Crear nuevas moras cuando los meses impagos alcanzan el umbral configurado.
            //    Ejemplo: mesesGracia=3, mesActual=Abril, ultimoMesPagado=Enero
            //    mesesImpagos = 3 (Feb, Mar, Abr) → 3 >= 3 → SÍ genera moras para Feb y Mar
            $mesesImpagos = (int) $ultimoMesPagado->diffInMonths($mesActual);
            $mesAEvaluar = (clone $ultimoMesPagado)->addMonth();

            // Solo generamos moras si los meses impagos alcanzan el umbral
            if ($mesesImpagos < $mesesGracia) {
                continue; // Aún está en periodo de gracia
            }

            while ($mesAEvaluar < $mesActual) {
                
                $existeMora = Mora::where('suscripcion_id', $suscripcion->id)
                                  ->where('mes_referencia', $mesAEvaluar->toDateString())
                                  ->exists();

                if (!$existeMora) {
                    $monto = 0;
                    if (!$suscripcion->servicio->tiene_medidor) {
                        $monto = $suscripcion->servicio->precio;
                    }

                    // No generar registro de mora si el monto es 0 (para servicios medidos sin lectura)
                    if ($monto > 0) {
                        Mora::create([
                            'organization_id' => $orgId,
                            'miembro_id' => $miembro->id,
                            'suscripcion_id' => $suscripcion->id,
                            'periodo' => 'Suscripción: ' . $suscripcion->servicio->nombre . ' - ' . ucfirst($mesAEvaluar->translatedFormat('F Y')),
                            'mes_referencia' => $mesAEvaluar->toDateString(),
                            'monto_original' => $monto,
                            'monto_pendiente' => $monto,
                            'estado' => 'Pendiente',
                        ]);
                    }
                }
                $mesAEvaluar->addMonth();
            }
        }

        // 2. Sincronizar Aportaciones
        foreach ($miembro->aportaciones as $aportacion) {
            $montoPendiente = $aportacion->monto_asignado - $aportacion->monto_pagado;
            
            $estadoMora = 'Pendiente';
            if ($montoPendiente <= 0) {
                $estadoMora = 'Cancelado';
                $montoPendiente = 0;
            } elseif ($aportacion->monto_pagado > 0) {
                $estadoMora = 'Abonado'; // Opciones en validación: Pendiente, Abonado, Cancelado
            }

            // Buscar si ya existe la mora
            $mora = Mora::where('aportacion_id', $aportacion->id)->first();

            if ($mora) {
                // Actualizamos si cambió
                if ($mora->monto_pendiente != $montoPendiente || $mora->estado != $estadoMora) {
                    $mora->update([
                        'monto_pendiente' => $montoPendiente,
                        'estado' => $estadoMora
                    ]);
                }
            } else {
                // Solo crear si está pendiente de pago
                if ($montoPendiente > 0) {
                    // cargar el nombre del proyecto si es necesario
                    $nombreProyecto = $aportacion->proyecto ? $aportacion->proyecto->nombre_proyecto : '';
                    Mora::create([
                        'organization_id' => $orgId,
                        'miembro_id' => $miembro->id,
                        'aportacion_id' => $aportacion->id,
                        'periodo' => 'Aportación a Proyecto: ' . $nombreProyecto,
                        'mes_referencia' => $aportacion->created_at->startOfMonth()->toDateString(), // referencia opcional
                        'monto_original' => $aportacion->monto_asignado,
                        'monto_pendiente' => $montoPendiente,
                        'estado' => $estadoMora,
                    ]);
                }
            }
        }
    }
}
