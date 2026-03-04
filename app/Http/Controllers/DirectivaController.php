<?php

namespace App\Http\Controllers;

use App\Models\Directiva;
use App\Http\Requests\StoreDirectivaRequest;
use App\Http\Requests\UpdateDirectivaRequest;
use Illuminate\Http\Request;

class DirectivaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('directiva.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $personas = \App\Models\Persona::all();
        $miembrosPersonaIds = \App\Models\Miembros::pluck('persona_id')->toArray();
        
        // Personas que ya tienen un cargo en la directiva de la organización actual
        $personasConCargoIds = \App\Models\Directiva::where('directivas.organization_id', session('tenant_organization_id'))
            ->join('miembros', 'directivas.miembro_id', '=', 'miembros.id')
            ->pluck('miembros.persona_id')
            ->toArray();

        return view('directiva.create', compact('personas', 'miembrosPersonaIds', 'personasConCargoIds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDirectivaRequest $request)
    {
        $personaId = $request->persona_id;
        
        // Verificar si la persona ya es un miembro
        $miembro = \App\Models\Miembros::where('persona_id', $personaId)->first();
        
        if (!$miembro) {
            // Si no es miembro, lo creamos automáticamente
            $miembro = \App\Models\Miembros::create([
                'persona_id' => $personaId,
                'direccion'  => 'No especificada (Registro automático desde Directiva)',
                'estado'     => 1,
            ]);
        }

        Directiva::create([
            'miembro_id' => $miembro->id,
            'cargo' => $request->cargo,
            'organization_id' => session('tenant_organization_id'),
        ]);

        return redirect()->route('directiva.index')
            ->with('success', 'Miembro de directiva asignado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $directiva = Directiva::findOrFail($id);
        return view('directiva.show', compact('directiva'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $directiva = Directiva::findOrFail($id); // evita route-model binding para no dar 404
        $miembros = \App\Models\Miembros::with('persona')->get();
        return view('directiva.edit', compact('directiva', 'miembros'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDirectivaRequest $request, $id)
    {
        $directiva = Directiva::findOrFail($id); // evita 404 de route-model binding
        $data = $request->validated();
        $data['organization_id'] = session('tenant_organization_id');

        $directiva->update($data);

        return redirect()->route('directiva.index')
            ->with('success', 'Miembro de directiva actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Directiva $directiva)
    {
        $directiva->delete();

        return redirect()->route('directiva.index')
            ->with('success', 'Miembro de directiva eliminado exitosamente.');
    }
}
