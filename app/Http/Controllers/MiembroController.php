<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Miembros;
use App\Models\Organizacion;
use App\Models\Municipio;
use App\Models\Persona;
use App\Models\Pais;
use App\Models\Departamento;
use App\Http\Requests\StoreMiembroRequest;
use App\http\Requests\UpdateMiembroRequest;
use App\Exports\MiembrosExport;
use Maatwebsite\Excel\Facades\Excel;

class MiembroController extends Controller
{
    public function index()
    {
        return view('Miembro.index');
    }

    public function create()
    {
        $personas = Persona::all();
        $organizaciones = Organizacion::all();
        $municipios = Municipio::all();
        $paises = Pais::all(); 

        return view('Miembro.create', compact('personas', 'organizaciones', 'municipios', 'paises'));
    }

    public function store(StoreMiembroRequest $request)
    {
        Miembros::create($request->validated());

        return redirect()->route('miembro.index')
            ->with('success', 'Miembro creado exitosamente.');
    }

    // Obtener departamentos por Pais
    public function getDepartamentos($paisId)
    {
        $departamentos = Departamento::where('pais_id', $paisId)->get();
        return response()->json($departamentos);
    }

    // Obtener municipios por Departamento
    public function getMunicipios($departamentoId)
    {
        $municipios = Municipio::where('departamento_id', $departamentoId)->get();
        return response()->json($municipios);
    }

    public function show(Miembros $miembro)
    {
        return view('Miembro.show', compact('miembro'));
    }

   public function edit(Miembros $miembro)
    {
        $personas = Persona::all();
        $organizaciones = Organizacion::all();
        $municipios = Municipio::all();
        $paises = Pais::all(); 

        // Pasa también el miembro que se va a editar
        return view('Miembro.edit', compact('miembro', 'personas', 'organizaciones', 'municipios', 'paises'));
    }

    public function update(UpdateMiembroRequest $request, Miembros $miembro)
    {
        $miembro->update($request->validated());

        return redirect()->route('miembro.index')
            ->with('success', 'Miembro actualizado exitosamente.');
    }

    public function destroy(Miembros $miembro)
    {
        $miembro->delete();

        return redirect()->route('miembro.index')
            ->with('success', 'Miembro eliminado exitosamente.');
    }

    public function exportExcel()
    {
        return Excel::download(new MiembrosExport, 'miembros_' . now()->format('Y_m_d_His') . '.xlsx');
    }

}
