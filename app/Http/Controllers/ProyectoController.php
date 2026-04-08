<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Presupuesto;
use App\Models\DetallePresupuesto;
use App\Models\Cooperante;
use App\Models\Miembros;
use App\Models\Directiva;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\ConfiguracionAportacion;
use App\Models\Aportacion;
use App\Models\JornadaTrabajo;
use App\Models\AsistenciaJornada;
use App\Http\Requests\StoreProyectoRequest;
use App\Http\Requests\UpdateProyectoRequest;
use App\Exports\ProyectosExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ProyectoController extends Controller
{
    public function index()
    {
        return view('Proyecto.index');
    }

    public function create()
    {
        $directivas  = Directiva::with('miembro.persona')->get();
        $orgId       = session('tenant_organization_id');
        $cooperantes = Cooperante::where('organization_id', $orgId)->get();

        // Miembros activos para Step 4
        $miembrosActivos = Miembros::with('persona')
            ->where('organization_id', $orgId)
            ->whereRaw("(estado = 1 OR estado = '1' OR LOWER(estado) = 'activo')")
            ->get();
        
        $tiposProyecto = [
            'Infraestructura',
            'Salud',
            'Educación',
            'Medio Ambiente',
            'Desarrollo Comunitario',
            'Agua y Saneamiento',
            'Vivienda',
            'Agricultura',
            'Seguridad Alimentaria',
            'Tecnología',
            'Deporte y Cultura',
            'Otro',
        ];

        $unidadesMedida = [
            'Unidad',
            'Global',
            'Metro lineal',
            'Metro cuadrado',
            'Metro cúbico', 
            'Centímetro',
            'Pulgada',
            'Pie',
            'Kilogramo',
            'Libra',
            'Gramo',
            'Tonelada',
            'Litro',
            'Galón',
            'Mililitro',
            'Bolsa',
            'Caja',
            'Rollo',
            'Par',
            'Docena',
            'Quintal',
            'Hora',
            'Día',
            'Mes',
        ];

        return view('Proyecto.create', compact('directivas', 'cooperantes', 'tiposProyecto', 'unidadesMedida', 'miembrosActivos'));
    }

    public function store(StoreProyectoRequest $request)
    {
        $orgId        = session('tenant_organization_id');
        $organization = \App\Models\Organization::find($orgId);

        $proyecto = Proyecto::create([
            'organization_id'           => $orgId,
            'departamento_id'           => $organization->id_departamento ?? null,
            'municipio_id'              => $organization->id_municipio ?? null,
            'nombre_proyecto'           => $request->nombre_proyecto,
            'tipo_proyecto'             => $request->tipo_proyecto,
            'descripcion'               => $request->descripcion,
            'justificacion'             => $request->justificacion,
            'descripcion_beneficiarios' => $request->descripcion_beneficiarios,
            'benef_hombres'             => $request->benef_hombres,
            'benef_mujeres'             => $request->benef_mujeres,
            'benef_ninos'               => $request->benef_ninos,
            'benef_familias'            => $request->benef_familias,
            'fecha_aprobacion_asamblea' => $request->fecha_aprobacion_asamblea,
            'numero_acta'               => $request->numero_acta,
            'fecha_inicio'              => $request->fecha_inicio,
            'fecha_fin'                 => $request->fecha_fin,
            'estado'                    => 1,
            'miembro_responsable_id'    => $request->directiva_id,
        ]);

        // ── Step 3: Presupuesto ──
        if ($request->has('detalles') && count($request->detalles) > 0) {
            $totalMontoFinanciador = 0;
            $totalMontoComunidad = 0;

            foreach ($request->detalles as $detalleData) {
                $totalLinea = floatval($detalleData['total'] ?? 0);
                if (!empty($detalleData['es_donacion']) && $detalleData['es_donacion']) {
                    $totalMontoFinanciador += $totalLinea;
                } else {
                    $totalMontoComunidad += $totalLinea;
                }
            }
            
            $presupuestoTotal = $totalMontoFinanciador + $totalMontoComunidad;
            $pctFinanciador = $presupuestoTotal > 0 ? round(($totalMontoFinanciador / $presupuestoTotal) * 100, 2) : 0;
            $pctComunidad = $presupuestoTotal > 0 ? round(($totalMontoComunidad / $presupuestoTotal) * 100, 2) : 0;

            $presupuesto = Presupuesto::create([
                'proyecto_id'            => $proyecto->id,
                'anio_presupuesto'       => now()->year,
                'presupuesto_total'      => $presupuestoTotal,
                'monto_financiador'      => $totalMontoFinanciador,
                'monto_comunidad'        => $totalMontoComunidad,
                'porcentaje_financiador' => $pctFinanciador,
                'porcentaje_comunidad'   => $pctComunidad,
                'estado'                 => 'Activo',
                'fecha_aprobacion'       => now(),
            ]);

            foreach ($request->detalles as $detalleData) {
                $esDonacion = !empty($detalleData['es_donacion']) && $detalleData['es_donacion'];

                DetallePresupuesto::create([
                    'presupuesto_id'  => $presupuesto->id,
                    'nombre'          => $detalleData['nombre'] ?? null,
                    'cantidad'        => $detalleData['cantidad'] ?? null,
                    'unidad_medida'   => $detalleData['unidad_medida'] ?? null,
                    'precio_unitario' => $detalleData['precio_unitario'] ?? null,
                    'total'           => $detalleData['total'] ?? null,
                    'observaciones'   => $detalleData['observaciones'] ?? null,
                    'es_donacion'     => $esDonacion,
                    'id_cooperante'   => $esDonacion ? ($detalleData['id_cooperante'] ?? null) : null,
                ]);
            }
        }

        // ── Step 4: Configuración de Aportaciones (opcional) ──
        if ($request->has('config_tipo_distribucion') && $request->config_tipo_distribucion) {
            $config = ConfiguracionAportacion::create([
                'proyecto_id'           => $proyecto->id,
                'tipo_distribucion'     => $request->config_tipo_distribucion,
                'monto_total_requerido' => $request->config_monto_total,
                'fecha_limite'          => $request->config_fecha_limite,
                'observaciones'         => $request->config_observaciones,
            ]);

            $miembrosActivos = Miembros::where('organization_id', $orgId)
                ->whereRaw("(estado = 1 OR estado = '1' OR LOWER(estado) = 'activo')")
                ->get();

            if ($request->config_tipo_distribucion === 'equitativa') {
                $montoPorMiembro = $miembrosActivos->count() > 0
                    ? round($request->config_monto_total / $miembrosActivos->count(), 2)
                    : 0;

                foreach ($miembrosActivos as $miembro) {
                    Aportacion::create([
                        'proyecto_id'    => $proyecto->id,
                        'miembro_id'     => $miembro->id,
                        'monto'          => $montoPorMiembro,
                        'monto_asignado' => $montoPorMiembro,
                        'estado'         => 'pendiente',
                    ]);
                }
            } elseif ($request->has('montos_manuales')) {
                foreach ($request->montos_manuales as $item) {
                    if (!empty($item['miembro_id']) && isset($item['monto'])) {
                        Aportacion::create([
                            'proyecto_id'    => $proyecto->id,
                            'miembro_id'     => $item['miembro_id'],
                            'monto'          => $item['monto'],
                            'monto_asignado' => $item['monto'],
                            'estado'         => 'pendiente',
                        ]);
                    }
                }
            }
        }

        // ── Step 4: Jornadas de Trabajo (opcional) ──
        if ($request->has('jornadas') && is_array($request->jornadas) && count($request->jornadas) > 0) {
            $numJornada = 0;
            foreach ($request->jornadas as $jornadaData) {
                $numJornada++;
                $jornada = JornadaTrabajo::create([
                    'proyecto_id'    => $proyecto->id,
                    'numero_jornada' => $numJornada,
                    'fecha'          => $jornadaData['fecha'] ?? null,
                    'hora_inicio'    => $jornadaData['hora_inicio'] ?? null,
                    'descripcion'    => $jornadaData['descripcion'] ?? null,
                    'estado'         => 'programada',
                ]);

                // Determinar miembros convocados
                $miembroIds = [];
                if (($jornadaData['tipo_convocatoria'] ?? 'todos') === 'todos') {
                    $miembroIds = Miembros::where('organization_id', $orgId)
                        ->whereRaw("(estado = 1 OR estado = '1' OR LOWER(estado) = 'activo')")
                        ->pluck('id')->toArray();
                } else {
                    $miembroIds = $jornadaData['miembros'] ?? [];
                }

                foreach ($miembroIds as $mId) {
                    AsistenciaJornada::create([
                        'jornada_id' => $jornada->id,
                        'miembro_id' => $mId,
                        'asistio'    => false,
                    ]);
                }
            }
        }

        return redirect()->route('proyecto.index')
            ->with('success', 'Proyecto creado exitosamente.');
    }

    public function show(Request $request, $id)
    {
        $proyecto = Proyecto::with([
            'organizacion',
            'departamento',
            'municipio',
            'miembroResponsable.miembro.persona',
            'presupuestos.detalles.cooperante',
            'configuracionAportacion',
        ])->findOrFail($id);

        $aportaciones = $proyecto->aportaciones()
            ->with('miembro.persona')
            ->paginate(15, ['*'], 'page_aportes');

        $jornadas = $proyecto->jornadasTrabajo()
            ->with('asistencias.miembro.persona')
            ->paginate(5, ['*'], 'page_jornadas');

        // Miembros activos para modales
        $miembrosActivos = Miembros::with('persona')
            ->where('organization_id', $proyecto->organization_id)
            ->whereRaw("(estado = 1 OR estado = '1' OR LOWER(estado) = 'activo')")
            ->get();

        return view('Proyecto.show', compact('proyecto', 'miembrosActivos', 'aportaciones', 'jornadas'));
    }

    public function edit($id)
    {
        $proyecto    = Proyecto::with(['presupuestos.detalles', 'configuracionAportacion', 'aportaciones', 'jornadasTrabajo.asistencias'])->findOrFail($id);
        $directivas  = Directiva::with('miembro.persona')->get();
        $orgId       = session('tenant_organization_id');
        $cooperantes = Cooperante::where('organization_id', $orgId)->get();

        $miembrosActivos = Miembros::with('persona')
            ->where('organization_id', $orgId)
            ->whereRaw("(estado = 1 OR estado = '1' OR LOWER(estado) = 'activo')")
            ->get();

        $unidadesMedida = [
            'Unidad',   
            'Lote',
            'Metro cuadrado',
            'Metro cúbico', 
            'Centímetro',
            'Pulgada',
            'Pie',
            'Kilogramo',
            'Libra',
            'Gramo',
            'Tonelada',
            'Litro',
            'Galón',
            'Mililitro',
            'Bolsa',
            'Caja',
            'Rollo',
            'Par',
            'Docena',
            'Quintal',
            'Hora',
            'Día',
            'Mes',
        ];

        $tiposProyecto = [
            'Infraestructura',
            'Salud',
            'Educación',
            'Medio Ambiente',
            'Desarrollo Comunitario',
            'Agua y Saneamiento',
            'Vivienda',
            'Agricultura',
            'Seguridad Alimentaria',
            'Tecnología',
            'Deporte y Cultura',
            'Otro',
        ];

        return view('Proyecto.edit', compact('proyecto', 'directivas', 'cooperantes', 'unidadesMedida', 'tiposProyecto', 'miembrosActivos'));
    }

    public function update(UpdateProyectoRequest $request, $id)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $proyecto = Proyecto::findOrFail($id);
            $proyecto->update($request->validated());

            $preservedDetalleIds = [];

            if ($request->has('detalles') && count($request->detalles) > 0) {
                $totalMontoFinanciador = 0;
                $totalMontoComunidad = 0;

                foreach ($request->detalles as $detalleData) {
                    $totalLinea = floatval($detalleData['total'] ?? 0);
                    if (!empty($detalleData['es_donacion']) && $detalleData['es_donacion']) {
                        $totalMontoFinanciador += $totalLinea;
                    } else {
                        $totalMontoComunidad += $totalLinea;
                    }
                }
                
                $presupuestoTotal = $totalMontoFinanciador + $totalMontoComunidad;
                $pctFinanciador = $presupuestoTotal > 0 ? round(($totalMontoFinanciador / $presupuestoTotal) * 100, 2) : 0;
                $pctComunidad = $presupuestoTotal > 0 ? round(($totalMontoComunidad / $presupuestoTotal) * 100, 2) : 0;

                $presupuesto = Presupuesto::updateOrCreate(
                    ['proyecto_id' => $proyecto->id],
                    [
                        'anio_presupuesto'       => now()->year,
                        'presupuesto_total'      => $presupuestoTotal,
                        'monto_financiador'      => $totalMontoFinanciador,
                        'monto_comunidad'        => $totalMontoComunidad,
                        'porcentaje_financiador' => $pctFinanciador,
                        'porcentaje_comunidad'   => $pctComunidad,
                        'estado'                 => 'Activo',
                        'fecha_aprobacion'       => now(),
                    ]
                );

                foreach ($request->detalles as $detalleData) {
                    $esDonacion = !empty($detalleData['es_donacion']) && $detalleData['es_donacion'];

                    $detalleParams = [
                        'presupuesto_id'  => $presupuesto->id,
                        'nombre'          => $detalleData['nombre'] ?? null,
                        'cantidad'        => $detalleData['cantidad'] ?? null,
                        'unidad_medida'   => $detalleData['unidad_medida'] ?? null,
                        'precio_unitario' => $detalleData['precio_unitario'] ?? null,
                        'total'           => $detalleData['total'] ?? null,
                        'observaciones'   => $detalleData['observaciones'] ?? null,
                        'es_donacion'     => $esDonacion,
                        'id_cooperante'   => $esDonacion ? ($detalleData['id_cooperante'] ?? null) : null,
                    ];

                    if (!empty($detalleData['id'])) {
                        $detalle = DetallePresupuesto::findOrFail($detalleData['id']);
                        $detalle->update($detalleParams);
                    } else {
                        $detalle = DetallePresupuesto::create($detalleParams);
                    }

                    $preservedDetalleIds[] = $detalle->id;
                }

                // Delete Budget Details removed from payload
                DetallePresupuesto::where('presupuesto_id', $presupuesto->id)
                    ->whereNotIn('id', $preservedDetalleIds)
                    ->delete();
            } else {
                // If no details provided, delete the budget and its children
                $presupuestos = Presupuesto::where('proyecto_id', $proyecto->id)->get();
                foreach($presupuestos as $p) {
                   $p->detalles()->each(fn($detalle) => $detalle->delete());
                   $p->delete();
                }
            }

            // ── Step 4: Configuración de Aportaciones ──
            if ($request->has('config_tipo_distribucion') && $request->config_tipo_distribucion) {
                $orgId = session('tenant_organization_id');
                
                $config = ConfiguracionAportacion::updateOrCreate(
                    ['proyecto_id' => $proyecto->id],
                    [
                        'tipo_distribucion'     => $request->config_tipo_distribucion,
                        'monto_total_requerido' => $request->config_monto_total,
                        'fecha_limite'          => $request->config_fecha_limite,
                        'observaciones'         => $request->config_observaciones,
                    ]
                );

                if ($request->config_tipo_distribucion === 'equitativa') {
                    $miembrosActivos = Miembros::where('organization_id', $orgId)
                        ->whereRaw("(estado = 1 OR estado = '1' OR LOWER(estado) = 'activo')")
                        ->get();
                        
                    $montoPorMiembro = $miembrosActivos->count() > 0
                        ? round($request->config_monto_total / $miembrosActivos->count(), 2)
                        : 0;

                    foreach ($miembrosActivos as $miembro) {
                        $aportacion = Aportacion::firstOrNew([
                            'proyecto_id' => $proyecto->id,
                            'miembro_id'  => $miembro->id,
                        ]);
                        
                        $aportacion->monto_asignado = $montoPorMiembro;
                        $aportacion->monto = $montoPorMiembro;
                        
                        $pagado = $aportacion->monto_pagado ?? 0;
                        if ($pagado >= $montoPorMiembro) {
                            $aportacion->estado = 'pagado';
                        } elseif ($pagado > 0) {
                            $aportacion->estado = 'parcial';
                        } else {
                            $aportacion->estado = 'pendiente';
                        }
                        $aportacion->save();
                    }
                } elseif ($request->has('montos_manuales')) {
                    foreach ($request->montos_manuales as $item) {
                        if (!empty($item['miembro_id']) && isset($item['monto'])) {
                            $aportacion = Aportacion::firstOrNew([
                                'proyecto_id' => $proyecto->id,
                                'miembro_id'  => $item['miembro_id'],
                            ]);
                            
                            $nuevoMonto = floatval($item['monto']);
                            $aportacion->monto_asignado = $nuevoMonto;
                            $aportacion->monto = $nuevoMonto;
                            
                            $pagado = $aportacion->monto_pagado ?? 0;
                            if ($pagado >= $nuevoMonto) {
                                $aportacion->estado = 'pagado';
                            } elseif ($pagado > 0) {
                                $aportacion->estado = 'parcial';
                            } else {
                                $aportacion->estado = 'pendiente';
                            }
                            $aportacion->save();
                        }
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('proyecto.index')
                ->with('success', 'Proyecto actualizado exitosamente.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Ocurrió un error al actualizar el proyecto: ' . $e->getMessage()]);
        }
    }

    public function destroy(Proyecto $proyecto)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($proyecto) {
            foreach ($proyecto->presupuestos as $presupuesto) {
                $presupuesto->detalles()->each(fn($detalle) => $detalle->delete());
                $presupuesto->delete();
            }

            $proyecto->delete();
        });

        return redirect()->route('proyecto.index')
            ->with('success', 'Proyecto eliminado exitosamente junto con sus presupuestos y detalles.');
    }

    public function exportExcel()
    {
        $orgNombre = auth()->user()->organization->name ?? 'organizacion';
        $nombre = Str::slug($orgNombre) . '_proyectos_' . now()->format('Y_m_d_His') . '.xlsx';
        
        return Excel::download(new ProyectosExport, $nombre);
    }

    public function exportPdf($id)
    {
        $proyecto = Proyecto::with([
            'organizacion',
            'departamento',
            'municipio',
            'miembroResponsable.miembro.persona',
            'presupuestos.detalles.cooperante',
        ])->findOrFail($id);

        $dateStr = now()->format('Y_m_d_His');
        $fileName = 'proyecto_' . Str::slug($proyecto->id) . '_' . $dateStr . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Proyecto.pdf', compact('proyecto'))
            ->setPaper('letter', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        // return $pdf->stream($fileName); // To view inline in browser
        return $pdf->download($fileName); // To trigger direct download
    }
}
