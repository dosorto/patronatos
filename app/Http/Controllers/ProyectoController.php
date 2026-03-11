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

        return view('Proyecto.create', compact('directivas', 'cooperantes', 'tiposProyecto', 'unidadesMedida'));
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

        // Guardar presupuestos y sus detalles
        if ($request->has('presupuestos')) {
            foreach ($request->presupuestos as $presupuestoData) {
                $presupuesto = Presupuesto::create([
                    'proyecto_id'           => $proyecto->id,
                    'anio_presupuesto'      => $presupuestoData['anio_presupuesto'] ?? null,
                    'presupuesto_total'     => $presupuestoData['presupuesto_total'] ?? null,
                    'monto_financiador'     => $presupuestoData['monto_financiador'] ?? null,
                    'monto_comunidad'       => $presupuestoData['monto_comunidad'] ?? null,
                    'porcentaje_financiador'=> $presupuestoData['porcentaje_financiador'] ?? null,
                    'porcentaje_comunidad'  => $presupuestoData['porcentaje_comunidad'] ?? null,
                    'estado'                => $presupuestoData['estado'] ?? null,
                    'fecha_aprobacion'      => $presupuestoData['fecha_aprobacion'] ?? null,
                    'es_donacion'           => $presupuestoData['es_donacion'] ?? false,
                    'id_cooperante'         => $presupuestoData['id_cooperante'] ?? null,
                ]);

                // Guardar detalles del presupuesto
                if (!empty($presupuestoData['detalles'])) {
                    foreach ($presupuestoData['detalles'] as $detalleData) {
                        DetallePresupuesto::create([
                            'presupuesto_id' => $presupuesto->id,
                            'nombre'         => $detalleData['nombre'] ?? null,
                            'cantidad'       => $detalleData['cantidad'] ?? null,
                            'unidad_medida'  => $detalleData['unidad_medida'] ?? null,
                            'precio_unitario'=> $detalleData['precio_unitario'] ?? null,
                            'total'          => $detalleData['total'] ?? null,
                            'observaciones'  => $detalleData['observaciones'] ?? null,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('proyecto.index')
            ->with('success', 'Proyecto creado exitosamente.');
    }

    public function show($id)
    {
        $proyecto = Proyecto::with([
            'organizacion',
            'departamento',
            'municipio',
            'miembroResponsable.miembro.persona',
            'presupuestos.detalles',
            'presupuestos.cooperante',
        ])->findOrFail($id);

        return view('Proyecto.show', compact('proyecto'));
    }

    public function edit($id)
    {
        $proyecto    = Proyecto::with('presupuestos.detalles')->findOrFail($id);
        $directivas  = Directiva::with('miembro.persona')->get();
        $orgId       = session('tenant_organization_id');
        $cooperantes = Cooperante::where('organization_id', $orgId)->get();
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

        return view('Proyecto.edit', compact('proyecto', 'directivas', 'cooperantes', 'unidadesMedida', 'tiposProyecto'));
    }

    public function update(UpdateProyectoRequest $request, $id)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $proyecto = Proyecto::findOrFail($id);
            $proyecto->update($request->validated());

            // IDs of budgets and details preserved in the request
            $preservedPresupuestoIds = [];
            $preservedDetalleIds = [];

            if ($request->has('presupuestos')) {
                foreach ($request->presupuestos as $presupuestoData) {
                    $esDonacion = isset($presupuestoData['es_donacion']) && $presupuestoData['es_donacion'];

                    $data = [
                        'proyecto_id'            => $proyecto->id,
                        'anio_presupuesto'       => $presupuestoData['anio_presupuesto'] ?? null,
                        'presupuesto_total'      => $presupuestoData['presupuesto_total'] ?? null,
                        'monto_financiador'      => $presupuestoData['monto_financiador'] ?? null,
                        'monto_comunidad'        => $presupuestoData['monto_comunidad'] ?? null,
                        'porcentaje_financiador' => $presupuestoData['porcentaje_financiador'] ?? null,
                        'porcentaje_comunidad'   => $presupuestoData['porcentaje_comunidad'] ?? null,
                        'estado'                 => $presupuestoData['estado'] ?? null,
                        'fecha_aprobacion'       => $presupuestoData['fecha_aprobacion'] ?? null,
                        'es_donacion'            => $esDonacion,
                        'id_cooperante'          => $esDonacion ? ($presupuestoData['id_cooperante'] ?? null) : null,
                    ];

                    // Upsert Budget
                    if (!empty($presupuestoData['id'])) {
                        $presupuesto = Presupuesto::findOrFail($presupuestoData['id']);
                        $presupuesto->update($data);
                    } else {
                        $presupuesto = Presupuesto::create($data);
                    }

                    $preservedPresupuestoIds[] = $presupuesto->id;

                    // Upsert Details for this Budget
                    if (!empty($presupuestoData['detalles'])) {
                        foreach ($presupuestoData['detalles'] as $detalleData) {
                            $detalleParams = [
                                'presupuesto_id'  => $presupuesto->id,
                                'nombre'          => $detalleData['nombre'] ?? null,
                                'cantidad'        => $detalleData['cantidad'] ?? null,
                                'unidad_medida'   => $detalleData['unidad_medida'] ?? null,
                                'precio_unitario' => $detalleData['precio_unitario'] ?? null,
                                'total'           => $detalleData['total'] ?? null,
                                'observaciones'   => $detalleData['observaciones'] ?? null,
                            ];

                            if (!empty($detalleData['id'])) {
                                $detalle = DetallePresupuesto::findOrFail($detalleData['id']);
                                $detalle->update($detalleParams);
                            } else {
                                $detalle = DetallePresupuesto::create($detalleParams);
                            }

                            $preservedDetalleIds[] = $detalle->id;
                        }
                    }
                }
            }

            // Delete Budget Details removed from payload
            DetallePresupuesto::whereIn('presupuesto_id', $proyecto->presupuestos->pluck('id'))
                ->whereNotIn('id', $preservedDetalleIds)
                ->delete();

            // Delete Budgets removed from payload
            Presupuesto::where('proyecto_id', $proyecto->id)
                ->whereNotIn('id', $preservedPresupuestoIds)
                ->delete();

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
            'presupuestos.detalles',
            'presupuestos.cooperante',
        ])->findOrFail($id);

        $dateStr = now()->format('Y_m_d_His');
        $fileName = 'proyecto_' . Str::slug($proyecto->nombre_proyecto) . '_' . $dateStr . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Proyecto.pdf', compact('proyecto'))
            ->setPaper('letter', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        // return $pdf->stream($fileName); // To view inline in browser
        return $pdf->download($fileName); // To trigger direct download
    }
}

