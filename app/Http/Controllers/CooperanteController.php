<?php

namespace App\Http\Controllers;

use App\Models\Cooperante;
use App\Models\Organization;
use Illuminate\Http\Request;

class CooperanteController extends Controller
{
    public function index()
    {
        $cooperantes = Cooperante::with('organization')->get();
        return view('cooperante.index', compact('cooperantes'));
    }

    public function create()
    {
        $organizations = Organization::all();
        return view('cooperante.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization_id'  => 'required|exists:organizations,id',
            'nombre'           => 'required|string|max:255',
            'tipo_cooperante'  => 'required|string|max:255',
            'telefono'         => 'required|string|max:20',
            'direccion'        => 'required|string|max:255',
        ]);

        Cooperante::create($request->all());
        return redirect()->route('cooperantes.index')
                         ->with('success', 'Cooperante creado exitosamente.');
    }

    public function show($id)
    {
        $cooperante = Cooperante::with('organization')->findOrFail($id);
        return view('cooperante.show', compact('cooperante'));
    }

    public function edit($id)
    {
        $cooperante = Cooperante::findOrFail($id); // evita route-model binding para no dar 404
        $organizations = Organization::all();
        return view('cooperante.edit', compact('cooperante', 'organizations'));
    }

    public function update(Request $request, $id)
    {
        $cooperante = Cooperante::findOrFail($id); // evita 404 de route-model binding

        $request->validate([
            'organization_id'  => 'required|exists:organizations,id',
            'nombre'           => 'required|string|max:255',
            'tipo_cooperante'  => 'required|string|max:255',
            'telefono'         => 'required|string|max:20',
            'direccion'        => 'required|string|max:255',
        ]);

        $cooperante->update($request->all());
        return redirect()->route('cooperantes.index')
                         ->with('success', 'Cooperante actualizado.');
    }

    public function destroy($id)
    {
        Cooperante::findOrFail($id)->delete();
        return redirect()->route('cooperantes.index')
                         ->with('success', 'Cooperante eliminado.');
    }
}