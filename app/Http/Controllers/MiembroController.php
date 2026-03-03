<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Miembros;
use App\Models\Organization;
use App\Models\Municipio;
use App\Models\Persona;
use App\Models\Pais;
use App\Models\Departamento;
use App\Http\Requests\StoreMiembroRequest;
use App\http\Requests\UpdateMiembroRequest;
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
        $miembro = \App\Models\Miembros::findOrFail($id);

        $organization = \App\Models\Organization::with([
            'municipio.departamento.pais',
            'departamento'
        ])->first();

        return view('Miembro.show', compact('miembro', 'organization'));
    }

    public function edit(Miembros $miembro)
    {
        $personas = Persona::all();
        return view('Miembro.edit', compact('miembro', 'personas'));
    }

    public function update(UpdateMiembroRequest $request, Miembros $miembro)
    {
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

    public function crearPersonaMiembro(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:13|unique:personas,dni',
            'direccion' => 'required|string|max:255',
        ]);

        $persona = Persona::create($request->only(['nombre', 'apellido', 'dni']));

        $miembro = Miembros::create([
            'persona_id' => $persona->id,
            'organization_id' => auth()->user()->organization_id,
            'municipio_id' => auth()->user()->organization->id_municipio,
            'direccion' => $request->direccion,
            'estado' => 1,
        ]);

        return response()->json([
            'success' => true,
            'persona' => $persona,
            'miembro' => $miembro,
        ]);
    }

}
