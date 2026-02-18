<?php

namespace App\Http\Controllers;

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
        return view('Municipio.index');
    }

    public function create()
    {
        $departamentos = Departamento::all();
        return view('Municipio.create', compact('departamentos'));
    }

    public function store(StoreMunicipioRequest $request)
    {
        Municipio::create($request->validated());

        return redirect()->route('municipio.index')
            ->with('success', 'Municipio creado exitosamente.');
    }

    public function show(Municipio $municipio)
    {
        return view('Municipio.show', compact('municipio'));
    }

    public function edit(Municipio $municipio)
    {
        $departamentos = Departamento::all();
        return view('Municipio.edit', compact('municipio', 'departamentos'));
    }

    public function update(UpdateMunicipioRequest $request, Municipio $municipio)
    {
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