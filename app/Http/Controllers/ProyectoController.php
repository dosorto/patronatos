<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
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
        $directivas = Directiva::with('miembro.persona')->get();
        return view('Proyecto.create', compact('directivas'));
    }

    public function store(StoreProyectoRequest $request)
    {
        $orgId        = session('tenant_organization_id');
        $organization = \App\Models\Organization::find($orgId);

        //dd($orgId, $organization, $organization->id_departamento, $organization->id_municipio);

        Proyecto::create([
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

        return redirect()->route('proyecto.index')
            ->with('success', 'Proyecto creado exitosamente.');
    }

    public function show($id)
    {
        $proyecto = Proyecto::with([
            'organizacion',
            'departamento',
            'municipio',
            'miembroResponsable.miembro.persona'
        ])->findOrFail($id);

        return view('Proyecto.show', compact('proyecto'));
    }

    public function edit($id)
    {
        $proyecto   = Proyecto::findOrFail($id);
        $directivas = Directiva::with('miembro.persona')->get();
        return view('Proyecto.edit', compact('proyecto', 'directivas'));
    }

    public function update(UpdateProyectoRequest $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($request->validated());

        return redirect()->route('proyecto.index')
            ->with('success', 'Proyecto actualizado exitosamente.');
    }

    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();

        return redirect()->route('proyecto.index')
            ->with('success', 'Proyecto eliminado exitosamente.');
    }

    public function exportExcel()
    {
        $orgNombre = auth()->user()->organization->name ?? 'organizacion';
        $nombre = Str::slug($orgNombre) . '_proyectos_' . now()->format('Y_m_d_His') . '.xlsx';
        
        return Excel::download(new ProyectosExport, $nombre);
    }
}
