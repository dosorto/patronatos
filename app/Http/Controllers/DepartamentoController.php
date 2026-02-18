<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Pais;
use App\Http\Requests\StoreDepartamentoRequest;
use App\Http\Requests\UpdateDepartamentoRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DepartamentosExport;

class DepartamentoController extends Controller
{
    public function index()
    {
        return view('Departamento.index');
    }

    public function create()
    {
        $paises = Pais::all();
        return view('Departamento.create', compact('paises'));
    }

    public function store(StoreDepartamentoRequest $request)
    {
        Departamento::create($request->validated());

        return redirect()->route('departamento.index')
            ->with('success', 'Departamento creado exitosamente.');
    }

    public function show(Departamento $departamento)
    {
        return view('Departamento.show', compact('departamento'));
    }

    public function edit(Departamento $departamento)
    {
        $paises = Pais::all();
        return view('Departamento.edit', compact('departamento', 'paises'));
    }

    public function update(UpdateDepartamentoRequest $request, Departamento $departamento)
    {
        $departamento->update($request->validated());

        return redirect()->route('departamento.index')
            ->with('success', 'Departamento actualizado exitosamente.');
    }

    public function destroy(Departamento $departamento)
    {
        $departamento->delete();

        return redirect()->route('departamento.index')
            ->with('success', 'Departamento eliminado exitosamente.');
    }

    public function exportExcel()
    {
        return Excel::download(new DepartamentosExport, 'departamentos_' . now()->format('Y_m_d_His') . '.xlsx');
    }
}