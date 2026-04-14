<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use App\Models\Municipio;
use App\Models\Departamento;
use App\Http\Requests\StoreMunicipioRequest;
use App\Http\Requests\UpdateMunicipioRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MunicipiosExport;

class MunicipioController extends Controller
{
    public function index()
    {
        return view('municipio.index');
    }

    /*public function create()
    {
        $departamentos = Departamento::all();
        return view('municipio.create', compact('departamentos'));
    }*/
    
    public function create()
    {
        $paises = Pais::all();
        $departamentos = collect(); // vacío, se carga por ajax
        return view('municipio.create', compact('paises', 'departamentos'));
    }

    public function store(StoreMunicipioRequest $request)
    {
        Municipio::create($request->validated());

        return redirect()->route('municipio.index')
            ->with('success', 'Municipio creado exitosamente.');
    }

    // Obtener departamentos por País
    public function getDepartamentos($paisId)
    {
        $departamentos = Departamento::where('pais_id', $paisId)->get();
        return response()->json($departamentos);
    }

    public function show($id)
    {
        $municipio = Municipio::findOrFail($id);
        return view('municipio.show', compact('municipio'));
    }

    public function edit($id)
    {
        $municipio = Municipio::findOrFail($id); // evita route-model binding para no dar 404
        $paises = Pais::all();
        $departamentos = Departamento::where('pais_id', $municipio->departamento->pais_id)->get();
        return view('municipio.edit', compact('municipio', 'paises', 'departamentos'));
    }

    public function update(UpdateMunicipioRequest $request, $id)
    {
        $municipio = Municipio::findOrFail($id); // evita 404 de route-model binding
        $municipio->update($request->validated());

        return redirect()->route('municipio.index')
            ->with('success', 'Municipio actualizado exitosamente.');
    }

    public function destroy(Municipio $municipio)
    {
        $municipio->delete();

        return redirect()->route('municipio.index')
            ->with('success', 'Municipio eliminado exitosamente.');
    }

    public function exportExcel()
    {
        return Excel::download(new MunicipiosExport, 'municipios_' . now()->format('Y_m_d_His') . '.xlsx');
    }
}