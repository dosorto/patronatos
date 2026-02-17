<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DepartamentosExport;
use App\Models\Pais;


class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Departamento.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paises = Pais::all();
        return view('Departamento.create', compact('paises'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre',
            'pais_id' => 'required|exists:pais,id',
        ]);

        Departamento::create([
            'nombre' => $request->nombre,
            'pais_id' => $request->pais_id,
        ]);

        return redirect()->route('departamento.index')
            ->with('success', 'Departamento creado exitosamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Departamento $departamento)
    {
        return view('Departamento.show', compact('departamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departamento $departamento)
    {
        $paises = Pais::all();
        return view('Departamento.edit', compact('departamento', 'paises'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre,' . $departamento->id,
            'pais_id' => 'required|exists:pais,id',
        ]);

        $departamento->update([
            'nombre' => $request->nombre,
            'pais_id' => $request->pais_id,
        ]);

        return redirect()->route('departamento.index')
            ->with('success', 'Departamento actualizado exitosamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departamento $departamento)
    {
        $departamento->delete();

        return redirect()->route('departamento.index')
            ->with('success', 'Departamento eliminado exitosamente.');
    }

    /**
     * Export to Excel
     */
    public function exportExcel()
    {
        return Excel::download(new DepartamentosExport, 'departamentos_' . now()->format('Y_m_d_His') . '.xlsx');
    }
}
