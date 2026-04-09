<?php

namespace App\Http\Controllers;

use App\Models\JornadaTrabajo;
use App\Models\AsistenciaJornada;
use App\Models\Miembros;
use App\Models\Proyecto;
use App\Http\Requests\StoreJornadaRequest;
use App\Http\Requests\GuardarListaAsistenciaRequest;
use Illuminate\Support\Facades\DB;

class ProyectoJornadaController extends Controller
{
    /**
     * Guardar una nueva jornada de trabajo.
     */
    public function store(StoreJornadaRequest $request, Proyecto $proyecto)
    {
        DB::beginTransaction();

        try {
            $numeroJornada = JornadaTrabajo::where('proyecto_id', $proyecto->id)->max('numero_jornada') + 1;

            $jornada = JornadaTrabajo::create([
                'proyecto_id'    => $proyecto->id,
                'numero_jornada' => $numeroJornada,
                'fecha'          => $request->fecha,
                'hora_inicio'    => $request->hora_inicio,
                'descripcion'    => $request->descripcion,
                'estado'         => 'programada',
            ]);

            // Determinar miembros convocados
            if ($request->tipo_convocatoria === 'todos') {
                $miembros = Miembros::where('organization_id', $proyecto->organization_id)
                    ->whereRaw("(estado = 1 OR estado = '1' OR LOWER(estado) = 'activo')")
                    ->pluck('id');
            } else {
                $miembros = collect($request->miembros);
            }

            // Crear registros de asistencia
            foreach ($miembros as $miembroId) {
                AsistenciaJornada::create([
                    'jornada_id' => $jornada->id,
                    'miembro_id' => $miembroId,
                    'asistio'    => false,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', "Jornada #$numeroJornada creada exitosamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear jornada: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar planilla de asistencia de una jornada.
     */
    public function show(Proyecto $proyecto, JornadaTrabajo $jornada)
    {
        $jornada->load('asistencias.miembro.persona');

        return view('Proyecto.planilla', compact('proyecto', 'jornada'));
    }

    /**
     * Guardar lista de asistencia.
     */
    public function guardarLista(GuardarListaAsistenciaRequest $request, Proyecto $proyecto, JornadaTrabajo $jornada)
    {
        if ($jornada->estado === 'realizada') {
            return back()->withErrors(['error' => 'Esta jornada ya fue cerrada y no puede editarse.']);
        }

        DB::beginTransaction();

        try {
            foreach ($request->asistencias as $data) {
                $asistencia = AsistenciaJornada::findOrFail($data['id']);
                $asistencia->update([
                    'asistio'           => $data['asistio'] ?? false,
                    'mando_sustituto'   => $data['mando_sustituto'] ?? false,
                    'nombre_sustituto'  => $data['nombre_sustituto'] ?? null,
                    'observaciones'     => $data['observaciones'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Lista de asistencia guardada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al guardar lista: ' . $e->getMessage()]);
        }
    }

    /**
     * Cerrar jornada — cambiar estado a 'realizada'.
     */
    public function cerrar(Proyecto $proyecto, JornadaTrabajo $jornada)
    {
        if ($jornada->estado === 'realizada') {
            return back()->withErrors(['error' => 'Esta jornada ya fue cerrada.']);
        }

        $jornada->update(['estado' => 'realizada']);

        return redirect()->back()->with('success', 'Jornada cerrada exitosamente. Ya no puede editarse.');
    }

    /**
     * Exportar jornada a PDF.
     */
    public function exportPdf(Proyecto $proyecto, JornadaTrabajo $jornada)
    {
        $jornada->load(['asistencias.miembro.persona', 'proyecto.organizacion']);

        // Determinar si el instrumento ya fue aplicado (al menos un registro marcado o con notas)
        $instrumentoAplicado = $jornada->asistencias->contains(function ($asist) {
            return $asist->asistio || $asist->mando_sustituto || !empty($asist->nombre_sustituto) || !empty($asist->observaciones);
        });
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Proyecto.jornada_pdf', compact('proyecto', 'jornada', 'instrumentoAplicado'))
            ->setPaper('letter', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('jornada_' . $jornada->numero_jornada . '.pdf');
    }
}
