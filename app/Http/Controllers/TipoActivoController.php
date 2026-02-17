<?php

namespace App\Http\Controllers;

use App\Models\TipoActivo;
use App\Http\Requests\StoreTipoActivoRequest;
use App\Http\Requests\UpdateTipoActivoRequest;
use App\Exports\TipoActivoExport; 
use Maatwebsite\Excel\Facades\Excel;  

class TipoActivoController extends Controller
{
    public function index()
    {
        $tipoActivos = TipoActivo::latest()->paginate(10);
        return view('tipoactivo.index', compact('tipoActivos'));
    }

    public function create()
    {
        return view('tipoactivo.create');
    }

    public function store(StoreTipoActivoRequest $request)
    {
        TipoActivo::create($request->validated());
        return redirect()->route('tipoactivo.index')
            ->with('success', 'Tipo de Activo creado correctamente.');
    }

    public function show(TipoActivo $tipoActivo)
    {
        return view('tipoactivo.show', compact('tipoActivo'));
    }

    public function edit(TipoActivo $tipoActivo)
    {
        return view('tipoactivo.edit', compact('tipoActivo'));
    }

    public function update(UpdateTipoActivoRequest $request, TipoActivo $tipoActivo)
    {
        $tipoActivo->update($request->validated());
        return redirect()->route('tipoactivo.index')
            ->with('success', 'Tipo de Activo actualizado correctamente.');
    }

    public function destroy(TipoActivo $tipoActivo)
    {
        $tipoActivo->delete();
        return redirect()->route('tipoactivo.index')
            ->with('success', 'Tipo de Activo eliminado correctamente.');
    }

    public function exportExcel()
    {
        return Excel::download(
            new TipoActivoExport(),
            'tipo_activos_' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}