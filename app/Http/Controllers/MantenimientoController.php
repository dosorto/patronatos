<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Models\Activo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MantenimientoController extends Controller
{
    public function index()
    {
        $mantenimientos = Mantenimiento::with('activo')
            ->where('organization_id', session('tenant_organization_id'))
            ->get();

        return view('mantenimiento.index', compact('mantenimientos'));
    }

    public function create()
    {
        // Se cargan los activos de la organización actual para el select
        $activos = Activo::where('organization_id', session('tenant_organization_id'))->get();
        return view('mantenimiento.create', compact('activos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activo_id' => [
                'nullable',
                Rule::exists('activos', 'id')->where(function ($query) {
                    $query->where('organization_id', session('tenant_organization_id'));
                }),
            ],
            'tipo_mantenimiento' => 'required|in:Correctivo,Preventivo,General',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string|max:50',
            'fecha_registro' => 'required|date',
            'costo_estimado' => 'nullable|numeric|min:0',
        ]);

        $mantenimiento = Mantenimiento::create($validated);

        return redirect()
            ->route('mantenimiento.index')
            ->with('success', 'Mantenimiento registrado exitosamente.');
    }

    public function show($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);
        return view('mantenimiento.show', compact('mantenimiento'));
    }

    public function edit($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);
        $activos = Activo::where('organization_id', session('tenant_organization_id'))->get();

        return view('mantenimiento.edit', compact('mantenimiento', 'activos'));
    }

    public function update(Request $request, $id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);

        $validated = $request->validate([
            'activo_id' => [
                'nullable',
                Rule::exists('activos', 'id')->where(function ($query) {
                    $query->where('organization_id', session('tenant_organization_id'));
                }),
            ],
            'tipo_mantenimiento' => 'required|in:Correctivo,Preventivo,General',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string|max:50',
            'fecha_registro' => 'required|date',
            'costo_estimado' => 'nullable|numeric|min:0',
            'estado' => 'nullable|string|max:50',
        ]);

        $mantenimiento->update($validated);

        return redirect()
            ->route('mantenimiento.index')
            ->with('success', 'Mantenimiento actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);
        $mantenimiento->delete();

        return redirect()
            ->route('mantenimiento.index')
            ->with('success', 'Mantenimiento eliminado exitosamente.');
    }
}
