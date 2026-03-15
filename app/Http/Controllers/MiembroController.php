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

    public function create(Request $request)
    {
        $personas = Persona::all();
        $isWizard = $request->boolean('wizard');
        return view('Miembro.create', compact('personas', 'isWizard'));
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

        Miembros::create([
            'persona_id'      => $personaId,
            'organization_id' => $orgId,
            'direccion'       => $request->direccion,
            'estado'          => 1,
        ]);

        $redirect = route('miembro.index') . ($isWizard ? '?wizard=1' : '');
        return redirect($redirect)->with('success', 'Miembro creado exitosamente.');
    }

    public function edit(Request $request, $id)
    {
        $miembro  = \App\Models\Miembros::findOrFail($id);
        $personas = Persona::all();
        $isWizard = $request->boolean('wizard');
        return view('Miembro.edit', compact('miembro', 'personas', 'isWizard'));
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