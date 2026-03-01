<?php

namespace App\Http\Controllers;

use App\Models\Cooperante;
use App\Models\Organizacion;
use Illuminate\Http\Request;

class CooperanteController extends Controller
{
    public function index()
    {
        $cooperantes = Cooperante::with('organizacion')->get();
        return view('cooperante.index', compact('cooperantes'));
    }

    public function create()
    {
        $organizaciones = Organizacion::all();
        return view('cooperante.create', compact('organizaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_organizacion'  => 'required|integer',
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
        $cooperante = Cooperante::with('organizacion')->findOrFail($id);
        return view('cooperante.show', compact('cooperante'));
    }

    public function edit($id)
    {
        $cooperante = Cooperante::findOrFail($id);
        $organizaciones = Organizacion::all();
        return view('cooperante.edit', compact('cooperante', 'organizaciones'));
    }

    public function update(Request $request, $id)
    {
        $cooperante = Cooperante::findOrFail($id);

        $request->validate([
            'id_organizacion'  => 'required|integer',
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