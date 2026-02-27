<?php

namespace App\Http\Controllers;

use App\Models\TipoActivo;
use App\Http\Requests\StoreTipoActivoRequest;
use App\Http\Requests\UpdateTipoActivoRequest;
use App\Exports\TipoActivoExport;
use Maatwebsite\Excel\Facades\Excel;

class TipoActivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tipoactivo.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipoactivo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoActivoRequest $request)
    {
        TipoActivo::create($request->validated());

        return redirect()->route('tipoactivo.index')
            ->with('success', 'Tipo de Activo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoActivo $tipoactivo)
    {
        return view('tipoactivo.show', compact('tipoactivo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoActivo $tipoactivo)
    {
        return view('tipoactivo.edit', compact('tipoactivo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoActivoRequest $request, TipoActivo $tipoactivo)
    {
        $tipoactivo->update($request->validated());
        

        return redirect()->route('tipoactivo.index')
            ->with('success', 'Tipo de Activo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoActivo $tipoactivo)
    {
        $tipoactivo->delete();

        return redirect()->route('tipoactivo.index')
            ->with('success', 'Tipo de Activo eliminado correctamente.');
    }

    /**
     * Export resource to Excel.
     */
    public function exportExcel()
    {
        return Excel::download(
            new TipoActivoExport(),
            'tipo_activos_' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}