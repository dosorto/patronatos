<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Proyecto;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with('proyecto')->paginate(10);
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();
        return view('servicios.create', compact('proyectos'));
    }

    public function store(StoreServicioRequest $request)
    {
        $servicio = Servicio::create($request->validated());

        if ($request->has('medidor_numeros') && $servicio->tiene_medidor) {
            foreach ($request->medidor_numeros as $index => $numero) {
                if ($numero) {
                    \App\Models\Medidores::create([
                        'numero_medidor' => $numero,
                        'fecha_instalacion' => !empty($request->medidor_fechas[$index]) ? $request->medidor_fechas[$index] : now(),
                        'estado' => 'activo',
                        'unidad_medida' => $servicio->unidad_medida,
                        'precio_unidad_medida' => $servicio->precio_por_unidad_de_medida ?: 0,
                        'miembro_id' => null,
                        'servicio_id' => $servicio->id,
                    ]);
                }
            }
        }

        return redirect()
            ->route('servicios.index', $request->boolean('wizard') ? ['wizard' => 1] : [])
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        $proyectos = Proyecto::all();
        return view('servicios.edit', compact('servicio', 'proyectos'));
    }

    public function update(UpdateServicioRequest $request, Servicio $servicio)
    {
        $servicio->update($request->validated());
        return redirect()
            ->route('servicios.index', $request->boolean('wizard') ? ['wizard' => 1] : []) // ← agrega esto
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')
                         ->with('success', 'Servicio eliminado correctamente.');
    }
}