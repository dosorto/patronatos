<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Miembros;
use App\Models\Persona;
use App\Http\Requests\StoreMiembroRequest;
use App\Http\Requests\UpdateMiembroRequest;
use App\Exports\MiembrosExport;
use Maatwebsite\Excel\Facades\Excel;

class MiembroController extends Controller
{
    public function index()
    {
        return view('Miembro.index');
    }

    public function create()
    {
        $personas = Persona::all();
        return view('Miembro.create', compact('personas'));
    }

    public function store(StoreMiembroRequest $request)
    {
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

        Miembros::create([
            'persona_id' => $personaId,
            'direccion'  => $request->direccion,
            'estado'     => 1,
        ]);

        return redirect()->route('miembro.index')
            ->with('success', 'Miembro creado exitosamente.');
    }

    public function show($id)
    {
        $miembro = Miembros::findOrFail($id);
        $orgId = session('tenant_organization_id');
        $organization = \App\Models\Organization::with([
            'municipio.departamento.pais',
            'departamento'
        ])->find($orgId);

        return view('Miembro.show', compact('miembro', 'organization'));
    }

    public function edit($id)
    {
        // Se utiliza la ruta completa para evitar route-model binding y que no retorne 404 inesperados
        $miembro = \App\Models\Miembros::findOrFail($id); 
        $personas = Persona::all();

        return view('Miembro.edit', compact('miembro', 'personas'));
    }

    public function update(UpdateMiembroRequest $request, $id)
    {
        // Se utiliza la ruta completa para evitar 404 de route-model binding
        $miembro = \App\Models\Miembros::findOrFail($id); 
        $miembro->update($request->validated());

        return redirect()->route('miembro.index')
            ->with('success', 'Miembro actualizado exitosamente.');
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