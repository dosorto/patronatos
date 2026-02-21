<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use App\Models\TipoOrganizacion;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Http\Requests\StoreOrganizacionRequest;
use App\Http\Requests\UpdateOrganizacionRequest;

class OrganizacionController extends Controller
{
    public function index()
    {
        return view('organizacion.index');
    }

    public function create()
    {
        $tiposOrganizacion = TipoOrganizacion::all();
        $departamentos = Departamento::all();
        $municipios = Municipio::all();

        return view('organizacion.create', compact('tiposOrganizacion', 'departamentos', 'municipios'));
    }

    public function store(StoreOrganizacionRequest $request)
    {
        Organizacion::create($request->validated());

        return redirect()->route('organizacion.index')
            ->with('success', 'Organización creada exitosamente.');
    }

    public function show(Organizacion $organizacion)
    {
        return view('organizacion.show', compact('organizacion'));
    }

    public function edit(Organizacion $organizacion)
    {
        $tiposOrganizacion = TipoOrganizacion::all();
        $departamentos = Departamento::all();
        $municipios = Municipio::all();

        return view('organizacion.edit', compact('organizacion', 'tiposOrganizacion', 'departamentos', 'municipios'));
    }

    public function update(UpdateOrganizacionRequest $request, Organizacion $organizacion)
    {
        $organizacion->update($request->validated());

        return redirect()->route('organizacion.index')
            ->with('success', 'Organización actualizada exitosamente.');
    }

    public function destroy(Organizacion $organizacion)
    {
        $organizacion->delete();

        return redirect()->route('organizacion.index')
            ->with('success', 'Organización eliminada exitosamente.');
    }
}