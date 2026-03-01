<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activo;
use App\Models\TipoActivo;
use App\Http\Requests\StoreActivoRequest;
use App\Http\Requests\UpdateActivoRequest;
use App\Exports\ActivosExport;
use Maatwebsite\Excel\Facades\Excel;

class ActivoController extends Controller
{
    public function index()
    {
        return view('Activo.index');
    }

    public function create()
    {
        $tiposActivos = TipoActivo::all();
        return view('Activo.create', compact('tiposActivos'));
    }

    public function store(StoreActivoRequest $request)
    {
        Activo::create([
            ...$request->validated(),
            'organizacion_id' => auth()->user()->organization_id,
            'estado' => 1,
        ]);

        return redirect()
            ->route('activo.index')
            ->with('success', 'Activo creado exitosamente.');
    }


    public function show(Activo $activo)
    {
        return view('Activo.show', compact('activo'));
    }

   public function edit(Activo $activo)
    {
        $tiposActivos = TipoActivo::all();
        return view('Activo.edit', compact('activo', 'tiposActivos'));
    }

    public function update(UpdateActivoRequest $request, Activo $activo)
    {
        $activo->update($request->validated());

        return redirect()->route('activo.index')
            ->with('success', 'Activo actualizado exitosamente.');
    }

    public function destroy(Activo $activo)
    {
        $activo->delete();

        return redirect()->route('activo.index')
            ->with('success', 'Activo eliminado exitosamente.');
    }

    public function export()
    {
        $orgNombre = auth()->user()->organizacion->nombre ?? 'N/A'; // o la que corresponda
        return Excel::download(new ActivosExport($orgNombre), 'activos.xlsx');
    }
}
