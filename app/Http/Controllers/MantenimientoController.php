<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Models\Activo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MantenimientoController extends Controller
{
    public function index()
    {
        $orgId = auth()->user()->organization_id ?? session('tenant_organization_id');
        Log::debug('Mantenimiento Index - OrgID: ' . $orgId);

        $mantenimientos = Mantenimiento::with('activo')
            ->where('organization_id', $orgId)
            ->get();

        return view('mantenimiento.index', compact('mantenimientos'));
    }

    public function create()
    {
        $orgId = auth()->user()->organization_id ?? session('tenant_organization_id');
        Log::debug('Mantenimiento Create - OrgID: ' . $orgId);
        
        // Se cargan los activos de la organización actual para el select
        $activos = Activo::where('organization_id', $orgId)->get();
        return view('mantenimiento.create', compact('activos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activo_id' => [
                'nullable',
                Rule::exists('activos', 'id')->where(function ($query) {
                    $orgId = auth()->user()->organization_id ?? session('tenant_organization_id');
                    $query->where('organization_id', $orgId);
                }),
            ],
            'tipo_mantenimiento' => 'required|in:Correctivo,Preventivo,General',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string|max:50',
            'fecha_registro' => 'required|date',
            'costo_estimado' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $orgId = auth()->user()->organization_id ?? session('tenant_organization_id');
            $data = $validated;
            $data['organization_id'] = $orgId;

            if (!$data['organization_id']) {
                throw new \Exception('No se encontró una organización activa vinculada al usuario o a la sesión.');
            }

            $mantenimiento = Mantenimiento::create($data);

            Log::debug('Mantenimiento Store Success', [
                'id' => $mantenimiento->id,
                'org_id' => $mantenimiento->organization_id,
                'estado' => $mantenimiento->estado
            ]);

            DB::commit();

            return redirect()
                ->route('mantenimiento.index')
                ->with('success', 'Mantenimiento registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            $orgId = auth()->user()->organization_id ?? session('tenant_organization_id');
            Log::error('Error registrando mantenimiento: ' . $e->getMessage(), [
                'request' => $request->all(),
                'org_id' => $orgId
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);
        return view('mantenimiento.show', compact('mantenimiento'));
    }

    public function edit($id)
    {
        $orgId = auth()->user()->organization_id ?? session('tenant_organization_id');
        Log::debug('Mantenimiento Edit - OrgID: ' . $orgId);

        $mantenimiento = Mantenimiento::findOrFail($id);
        $activos = Activo::where('organization_id', $orgId)->get();

        return view('mantenimiento.edit', compact('mantenimiento', 'activos'));
    }

    public function update(Request $request, $id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);

        $validated = $request->validate([
            'activo_id' => [
                'nullable',
                Rule::exists('activos', 'id')->where(function ($query) {
                    $orgId = auth()->user()->organization_id ?? session('tenant_organization_id');
                    $query->where('organization_id', $orgId);
                }),
            ],
            'tipo_mantenimiento' => 'required|in:Correctivo,Preventivo,General',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string|max:50',
            'fecha_registro' => 'required|date',
            'costo_estimado' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $mantenimiento->update($validated);

            DB::commit();

            return redirect()
                ->route('mantenimiento.index')
                ->with('success', 'Mantenimiento actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error actualizando mantenimiento: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el mantenimiento.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $mantenimiento = Mantenimiento::findOrFail($id);
            $mantenimiento->delete();

            DB::commit();

            return redirect()
                ->route('mantenimiento.index')
                ->with('success', 'Mantenimiento eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error eliminando mantenimiento: ' . $e->getMessage());

            return redirect()
                ->route('mantenimiento.index')
                ->with('error', 'Ocurrió un error al intentar eliminar el mantenimiento.');
        }
    }
}
