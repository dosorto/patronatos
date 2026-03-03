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

    public function show($id)
    {
        $departamento = Departamento::findOrFail($id);
        return view('Departamento.show', compact('departamento'));
    }

    public function edit($id)
    {
        $departamento = Departamento::findOrFail($id); // evita route-model binding para no dar 404
        $paises = Pais::all();
        return view('Departamento.edit', compact('departamento', 'paises'));
    }

    public function update(UpdateDepartamentoRequest $request, $id)
    {
        $departamento = Departamento::findOrFail($id); // evita 404 de route-model binding
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