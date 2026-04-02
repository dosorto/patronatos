<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('personas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('personas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        try {
            return \DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['fecha_ingreso'] = now()->toDateString();
                
                $persona = Persona::create($data);

                if ($request->wantsJson()) {
                    return response()->json($persona);
                }

                return redirect()->route('personas.index')
                    ->with('success', 'Persona creada exitosamente.');
            });
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Ocurrió un error al crear la persona: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $persona = Persona::findOrFail($id);
        return view('personas.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $persona = Persona::findOrFail($id); // evita route-model binding para no dar 404
        return view('personas.edit', compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonaRequest $request, $id)
    {
        try {
            return \DB::transaction(function () use ($request, $id) {
                $persona = Persona::findOrFail($id);
                $persona->update($request->validated());

                return redirect()->route('personas.index')
                    ->with('success', 'Persona actualizada exitosamente.');
            });
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Ocurrió un error al actualizar la persona: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        $persona->delete();

        return redirect()->route('personas.index')
            ->with('success', 'Persona eliminada exitosamente.');
    }

    public function buscar(Request $request)
    {
        $termino = $request->get('q');
        
        $personas = Persona::where('nombre', 'LIKE', "%{$termino}%")
                            ->orWhere('apellido', 'LIKE', "%{$termino}%")
                            ->orWhere('dni', 'LIKE', "%{$termino}%")
                            ->limit(20)
                            ->get();
        
        return response()->json($personas);
    }

    public function findByDni($dni)
    {
        $persona = Persona::where('dni', $dni)->first();
        if (!$persona) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }

        // Formatear fechas para input type="date"
        $data = $persona->toArray();
        if ($persona->fecha_nacimiento) {
            $data['fecha_nacimiento'] = $persona->fecha_nacimiento->format('Y-m-d');
        }

        // Buscar si ya es miembro de la organización actual para traer su dirección
        $orgId = session('tenant_organization_id');
        $miembro = \App\Models\Miembros::where('persona_id', $persona->id)
                                       ->where('organization_id', $orgId)
                                       ->first();
        if ($miembro) {
            $data['direccion'] = $miembro->direccion;
        }

        return response()->json($data);
    }
}
