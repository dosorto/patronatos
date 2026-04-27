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
        $diaLimite = $org->dias_pago ?? 30;
        $hoy = Carbon::now();

        // 1. Sincronizar Suscripciones
        foreach ($miembro->suscripciones as $suscripcion) {
            if (!$suscripcion->estado) continue; 

            $ultimoMesPagado = $suscripcion->ultimo_mes_pagado 
                                ? Carbon::parse($suscripcion->ultimo_mes_pagado)->startOfMonth() 
                                : Carbon::parse($suscripcion->created_at)->startOfMonth()->subMonth();

            // A) Cancelar moras viejas si se avanzó el último mes pagado.
            Mora::where('suscripcion_id', $suscripcion->id)
                ->where('mes_referencia', '<=', $ultimoMesPagado)
                ->where('estado', 'Pendiente')
                ->update([
                    'estado' => 'Cancelado',
                    'monto_pendiente' => 0
                ]);

            // B) Crear nuevas moras.
            $mesAEvaluar = (clone $ultimoMesPagado)->addMonth();
            
            // Recorremos desde el mes siguiente al ultimo pagado hasta el mes actual
            while ($mesAEvaluar <= $mesActual) {
                
                // Determinar si este mes YA debería estar en mora
                $mesesDeDiferencia = (int) $ultimoMesPagado->diffInMonths($mesAEvaluar);
                
                $enMora = false;
                $umbralMeses = $mesesGracia + 1;
                
                if ($mesesDeDiferencia > $umbralMeses) {
                    // Ya pasó el umbral de meses de gracia completo
                    $enMora = true;
                } elseif ($mesesDeDiferencia == $umbralMeses) {
                    // Estamos en el mes del umbral, revisar el día límite
                    if ($hoy->day > $diaLimite || $mesAEvaluar->lt($mesActual)) {
                        $enMora = true;
                    }
                }

                if (!$enMora) {
                    // Si previamente existía una mora Pendiente para este mes pero las reglas 
                    // de configuración cambiaron, se revierte eliminándola.
                    Mora::where('suscripcion_id', $suscripcion->id)
                        ->where('mes_referencia', $mesAEvaluar->toDateString())
                        ->where('estado', 'Pendiente')
                        ->delete();

                    $mesAEvaluar->addMonth();
                    continue;
                }
                
                $existeMora = Mora::where('suscripcion_id', $suscripcion->id)
                                  ->where('mes_referencia', $mesAEvaluar->toDateString())
                                  ->exists();

                if (!$existeMora) {
                    $monto = 0;
                    if (!$suscripcion->servicio->tiene_medidor) {
                        $monto = $suscripcion->servicio->precio;
                    }

                    // Generar registro de mora si el monto > 0 o si es un servicio medido (para forzar su pago/lectura)
                    if ($monto > 0 || $suscripcion->servicio->tiene_medidor) {
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
            $proyecto = $aportacion->proyecto;
            
            // Si el proyecto está Cancelado o Completado, sus aportaciones ya no son exigibles.
            if ($proyecto && in_array($proyecto->estado, ['Cancelado', 'Completado'])) {
                $mora = Mora::where('aportacion_id', $aportacion->id)->first();
                if ($mora && $mora->estado !== 'Cancelado') {
                    $mora->update([
                        'estado' => 'Cancelado',
                        'monto_pendiente' => 0
                    ]);
                }
                continue;
            }

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
