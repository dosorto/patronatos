<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Persona;
use App\Models\Organizacion;
use App\Http\Requests\StoreEmpleadoRequest;
use App\http\Requests\UpdateEmpleadoRequest;
use App\Exports\EmpleadosExport;
use Maatwebsite\Excel\Facades\Excel;

class EmpleadoController extends Controller
{
    public function index()
    {
        return view('Empleado.index');
    }

    public function create()
    {
        $personas = Persona::all();
        $organizaciones = Organizacion::all();        

        return view('Empleado.create', compact('personas', 'organizaciones'));
    }

    public function store(StoreEmpleadoRequest $request)
    {
        Empleado::create($request->validated());

        return redirect()->route('empleado.index')
            ->with('success', 'Empleado creado exitosamente.');
    }

    public function show(Empleado $empleado)
    {
        return view('Empleado.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        $personas = Persona::all();
        $organizaciones = Organizacion::all();

        return view('Empleado.edit', compact('empleado', 'personas', 'organizaciones'));
    }

    public function update(UpdateEmpleadoRequest $request, Empleado $empleado)
    {
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

}
