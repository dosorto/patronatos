<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Miembros;
use App\Models\Persona;
use App\Models\DetalleCobro;
use App\Models\Organization;
use App\Http\Requests\StoreMiembroRequest;
use App\Http\Requests\UpdateMiembroRequest;
use App\Exports\MiembrosExport;
use Maatwebsite\Excel\Facades\Excel;

class MiembroController extends Controller
{
    public function index()
    {
        return view('miembro.index');
    }

    public function create(Request $request)
    {
        $personas = Persona::all();
        $isWizard = $request->boolean('wizard');
        $servicios = \App\Models\Servicio::where('estado', 'activo')->get();
        // Load free medidores grouped by servicio_id
        $medidoresLibres = \App\Models\Medidores::whereNull('miembro_id')->get()->groupBy('servicio_id');
        return view('miembro.create', compact('personas', 'isWizard', 'servicios', 'medidoresLibres'));
    }

    public function store(StoreMiembroRequest $request)
    {
        $isWizard = $request->boolean('wizard');

        if ($request->crear_persona == '1') {
            $persona = Persona::create([
                'nombre'           => $request->nueva_nombre,
                'apellido'         => $request->nueva_apellido,
                'dni'              => $request->nueva_dni,
                'fecha_nacimiento' => $request->nueva_fecha_nacimiento,
                'sexo'             => $request->nueva_sexo,
                'telefono'         => $request->nueva_telefono,
                'email'            => $request->nueva_email,
                'estado'           => 1,
            ]);
            $personaId = $persona->id;
        } else {
            $personaId = $request->persona_id;
        }
        

        $orgId = session('tenant_organization_id');

        // 🔴 VALIDACIÓN CLAVE
        $existe = Miembros::where('persona_id', $personaId)
                    ->where('organization_id', $orgId)
                    ->exists();
        $existe = Miembros::where('persona_id', $personaId)->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors(['persona_id' => 'Esta persona ya está registrada como miembro.']);
        }

        $miembro = Miembros::create([
            'persona_id'      => $personaId,
            'organization_id' => $orgId,
            'direccion'       => $request->direccion,
            'estado'          => 1,
        ]);

        if ($request->has('suscripciones')) {
            foreach ($request->suscripciones as $subData) {
                if (!empty($subData['servicio_id'])) {
                    $medidorId = null;

                    // Handle Medidor
                    if (!empty($subData['medidor_id'])) {
                        if ($subData['medidor_id'] === 'nuevo' && !empty($subData['nuevo_medidor_numero'])) {
                            // Create new medidor on the fly
                            $servicio = \App\Models\Servicio::find($subData['servicio_id']);
                            $medidor = \App\Models\Medidores::create([
                                'numero_medidor'     => $subData['nuevo_medidor_numero'],
                                'miembro_id'          => $miembro->id,
                                'servicio_id'        => $subData['servicio_id'],
                                'estado'             => 'activo',
                                'unidad_medida'      => $servicio->unidad_medida,
                                'precio_unidad_medida' => $servicio->precio_por_unidad_de_medida ?: 0,
                                'fecha_instalacion'  => now(),
                            ]);
                            $medidorId = $medidor->id;
                        } else {
                            // Use existing and link to member
                            $medidor = \App\Models\Medidores::find($subData['medidor_id']);
                            if ($medidor) {
                                $medidor->miembro_id = $miembro->id;
                                $medidor->save();
                                $medidorId = $medidor->id;
                            }
                        }
                    }

                    // Create Subscription linking to medidor and storing identifier
                    \App\Models\Suscripcion::create([
                        'miembro_id'        => $miembro->id,
                        'servicio_id'       => $subData['servicio_id'],
                        'medidor_id'        => $medidorId,
                        'identificador'     => $subData['identificador'] ?? null,
                        'fecha_inicio'      => now(),
                        'ultimo_mes_pagado' => now()->startOfMonth(),
                        'estado'            => 1,
                    ]);
                }
            }
        }

        $redirect = route('miembro.index') . ($isWizard ? '?wizard=1' : '');
        return redirect($redirect)->with('success', 'Miembro creado exitosamente.');
    }

    public function show($id)
    {
        $miembro = Miembros::with([
            'persona',
            'suscripciones.servicio',
            'cobros.detallesCobros',
            'aportaciones.proyecto',
            'moras',
            'auditLogs'
        ])->findOrFail($id);

        $orgId = session('tenant_organization_id');
        $organization = \App\Models\Organization::with([
            'municipio.departamento.pais',
            'departamento'
        ])->find($orgId);

        // Separar donaciones (detalles ligan a cobro que liga a miembro)
        $donaciones = \App\Models\DetalleCobro::whereHas('cobro', function($q) use ($miembro) {
            $q->where('miembro_id', $miembro->id);
        })->where('es_donacion', true)->get();

        return view('miembro.show', compact('miembro', 'organization', 'donaciones'));
    }

    public function edit(Request $request, $id)
    {
        $miembro  = \App\Models\Miembros::findOrFail($id);
        $personas = Persona::all();
        $isWizard = $request->boolean('wizard');
        return view('miembro.edit', compact('miembro', 'personas', 'isWizard'));
    }

    public function update(UpdateMiembroRequest $request, $id)
    {
        $isWizard = $request->boolean('wizard');
        $miembro  = \App\Models\Miembros::findOrFail($id);

        $miembro->persona->update([
            'nombre'           => $request->nombre,
            'apellido'         => $request->apellido,
            'dni'              => $request->dni,
            'sexo'             => $request->sexo,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'email'            => $request->email,
            'telefono'         => $request->telefono,
        ]);

        $miembro->update([
            'direccion' => $request->direccion,
            'estado'    => $request->estado,
        ]);

        $redirect = route('miembro.index') . ($isWizard ? '?wizard=1' : '');
        return redirect($redirect)->with('success', 'Miembro y datos de persona actualizados exitosamente.');
    }

    public function destroy(Miembros $miembro)
    {
        $miembro->delete();

        return redirect()->route('miembro.index')
            ->with('success', 'Miembro eliminado exitosamente.');
    }

    public function exportExcel()
    {
        return Excel::download(new MiembrosExport, 'miembros_' . now()->format('Y_m_d_His') . '.xlsx');
    }
}