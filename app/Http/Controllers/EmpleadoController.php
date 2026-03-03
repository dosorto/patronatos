<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Persona;
use App\Http\Requests\StoreEmpleadoRequest;
use App\Http\Requests\UpdateEmpleadoRequest;
use App\Exports\EmpleadosExport;
use App\Models\Organization;
use Maatwebsite\Excel\Facades\Excel;

class EmpleadoController extends Controller
{
    public function index()
    {
        $organization = \App\Models\Organization::first();
        return view('Empleado.index', compact('organization'));
        
    }

    public function create()
    {
        $personas = Persona::all();
        return view('Empleado.create', compact('personas'));
    }

    public function store(StoreEmpleadoRequest $request)
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

        Empleado::create([
            'persona_id'      => $personaId,
            'organization_id' => auth()->user()->organization_id,
            'cargo'           => $request->cargo,
            'sueldo_mensual'  => $request->sueldo_mensual,
        ]);

        return redirect()->route('empleado.index')
            ->with('success', 'Empleado creado exitosamente.');
    }

    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);

        // arregla esta consulta para traer la organización del empleado
        $orgId = session('tenant_organization_id');
        $organization = \App\Models\Organization::find($orgId);

        return view('Empleado.show', compact('empleado', 'organization'));
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id); // evita route-model binding para no dar 404
        $personas = Persona::all();

        return view('Empleado.edit', compact('empleado', 'personas'));
    }

    public function update(UpdateEmpleadoRequest $request, $id)
    {
        $empleado = Empleado::findOrFail($id); // evita 404 de route-model binding
        $empleado->update($request->validated());

        return redirect()->route('empleado.index')
            ->with('success', 'Empleado actualizado exitosamente.');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return redirect()->route('empleado.index')
            ->with('success', 'Empleado eliminado exitosamente.');
    }

    public function exportExcel()
    {
        return Excel::download(new EmpleadosExport, 'empleados_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function crearPersonaEmpleado(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni'      => 'required|string|max:13|unique:personas,dni',
            'cargo'    => 'required|string|max:255',
            'sueldo_mensual' => 'required|numeric|min:0',
        ]);

        $persona = Persona::create($request->only(['nombre', 'apellido', 'dni']));

        $empleado = Empleado::create([
            'persona_id'      => $persona->id,
            'organization_id' => auth()->user()->organization_id,
            'cargo'           => $request->cargo,
            'sueldo_mensual'  => $request->sueldo_mensual,
            'estado'          => 1,
        ]);

        return response()->json([
            'success'  => true,
            'persona'  => $persona,
            'empleado' => $empleado,
        ]);
    }

}
