<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aportacion;
use App\Models\Miembros;
use App\Models\Proyecto;
use App\Http\Requests\StoreAportacionRequest;
use App\Http\Requests\UpdateAportacionRequest;

class AportacionController extends Controller
{
    public function index()
    {
        $aportaciones = Aportacion::with(['miembro', 'proyecto'])
            ->latest()
            ->paginate(15);

        return view('aportaciones.index', compact('aportaciones'));
    }

    public function create(Request $request)
    {
        $miembros = Miembros::with('persona')->get();
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();

        return view('aportaciones.create', compact('miembros', 'proyectos'));
    }

    public function store(StoreAportacionRequest $request)
    {
        Aportacion::create([
            'id_miembro'       => $request->id_miembro,
            'id_proyecto'      => $request->id_proyecto,
            'monto'            => $request->monto,
            'fecha_aportacion' => $request->fecha_aportacion,
            'estado'           => $request->estado ?? 1,
        ]);

        return redirect()->route('aportacion.index')
            ->with('success', 'Aportación registrada correctamente.');
    }

    public function show($id)
    {
        $aportacion = Aportacion::with(['miembro', 'proyecto'])->findOrFail($id);

        return view('aportaciones.show', compact('aportacion'));
    }

    public function edit(Request $request, $id)
    {
        $aportacion = Aportacion::findOrFail($id);
        $miembros = Miembros::with('persona')->get();
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();

        return view('aportaciones.edit', compact('aportacion', 'miembros', 'proyectos'));
    }

    public function update(UpdateAportacionRequest $request, $id)
    {
        $aportacion = Aportacion::findOrFail($id);

        $aportacion->update([
            'id_miembro'       => $request->id_miembro,
            'id_proyecto'      => $request->id_proyecto,
            'monto'            => $request->monto,
            'fecha_aportacion' => $request->fecha_aportacion,
            'estado'           => $request->estado,
        ]);

        return redirect()->route('aportacion.index')
            ->with('success', 'Aportación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $aportacion = Aportacion::findOrFail($id);
        $aportacion->delete();

        return redirect()->route('aportacion.index')
            ->with('success', 'Aportación eliminada correctamente.');
    }
}