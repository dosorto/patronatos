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
        Servicio::create($request->validated());
        return redirect()->route('servicios.index')
                         ->with('success', 'Servicio creado correctamente.');
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
        return redirect()->route('servicios.index')
                         ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')
                         ->with('success', 'Servicio eliminado correctamente.');
    }
}