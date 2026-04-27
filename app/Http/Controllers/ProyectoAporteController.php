<?php

namespace App\Http\Controllers;

use App\Models\Aportacion;
use App\Models\ConfiguracionAportacion;
use App\Models\Cobro;
use App\Models\Miembros;
use App\Models\Proyecto;
use App\Http\Requests\ConfigurarAportacionRequest;
use App\Http\Requests\RegistrarPagoAportacionRequest;
use Illuminate\Support\Facades\DB;

class ProyectoAporteController extends Controller
{
    /**
     * Guardar o actualizar la configuración de aportaciones de un proyecto
     * y generar/regenerar los registros individuales de aportación.
     */
    public function configurar(ConfigurarAportacionRequest $request, Proyecto $proyecto)
    {
        abort_if(in_array($proyecto->estado, ['Cancelado', 'Completado', 'Pausado']), 403, 'No se pueden reconfigurar aportaciones en un proyecto ' . $proyecto->estado);

        DB::beginTransaction();

        try {
            $config = ConfiguracionAportacion::updateOrCreate(
                ['proyecto_id' => $proyecto->id],
                [
                    'tipo_distribucion'     => $request->tipo_distribucion,
                    'monto_total_requerido' => $request->monto_total_requerido,
                    'fecha_limite'          => $request->fecha_limite,
                    'observaciones'         => $request->observaciones,
                ]
            );

            // Obtener miembros activos de la organización (sin filtrar por organization_id redundante)
            $miembrosActivos = Miembros::activos()->get();

            if ($request->tipo_distribucion === 'equitativa') {
                $montoPorMiembro = $miembrosActivos->count() > 0
                    ? round($request->monto_total_requerido / $miembrosActivos->count(), 2)
                    : 0;

                foreach ($miembrosActivos as $miembro) {
                    Aportacion::updateOrCreate(
                        [
                            'proyecto_id' => $proyecto->id,
                            'miembro_id'  => $miembro->id,
                        ],
                        [
                            'monto'          => $montoPorMiembro,
                            'monto_asignado' => $montoPorMiembro,
                            'estado'         => 'pendiente', // Aseguramos el estado inicial
                        ]
                    );
                }
            } else {
                // Distribución manual
                foreach ($request->montos_manuales as $item) {
                    Aportacion::updateOrCreate(
                        [
                            'proyecto_id' => $proyecto->id,
                            'miembro_id'  => $item['miembro_id'],
                        ],
                        [
                            'monto'          => $item['monto'],
                            'monto_asignado' => $item['monto'],
                            'estado'         => 'pendiente', // Aseguramos el estado inicial
                        ]
                    );
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Configuración de aportaciones guardada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al configurar aportaciones: ' . $e->getMessage()]);
        }
    }


}
