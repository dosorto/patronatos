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
        $cargos = [
            'Presidente(a)',
            'Vicepresidente(a)',
            'Secretario(a)',
            'Prosecretario',
            'Tesorero(a)',
            'Vocal 1',
            'Vocal 2',
            'Vocal 3',
            'Vocal 4',
            'Vocal 5',
        ];

        // Obtener miembros actuales con sus cargos para pre-poblar o mostrar advertencias
        $directivaActual = Directiva::with('miembro.persona')
            ->where('organization_id', session('tenant_organization_id'))
            ->get();

        return view('directiva.create', compact('cargos', 'directivaActual'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDirectivaRequest $request)
    {
        try {
            \DB::beginTransaction();

            $orgId = session('tenant_organization_id');
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;

            // 1. Validar que no haya duplicados en el envío actual
            $personaIds = array_filter(array_column($request->cargos, 'persona_id'));
            if (count($personaIds) !== count(array_unique($personaIds))) {
                \DB::rollBack();
                return back()->withInput()->with('error', 'Error: No se puede asignar la misma persona a múltiples cargos en la misma junta.');
            }

            foreach ($request->cargos as $cargoData) {
                if (empty($cargoData['persona_id'])) continue;

                $personaId = $cargoData['persona_id'];

                // 2. CADENA: Obtener o crear el Miembro para esta organización
                // Buscamos si ya es miembro
                $miembro = \App\Models\Miembros::where('persona_id', $personaId)
                    ->where(function ($q) use ($orgId) {
                        $q->where('organization_id', $orgId)
                          ->orWhereNull('organization_id');
                    })
                    ->first();
                
                if ($miembro) {
                    if (empty($miembro->organization_id)) {
                        $miembro->update(['organization_id' => $orgId]);
                    }
                } else {
                    // Si no es miembro (pero existe como Persona), lo creamos en el patronato actual
                    $miembro = \App\Models\Miembros::create([
                        'persona_id' => $personaId,
                        'organization_id' => $orgId,
                        'direccion'  => 'Asignación automática desde Junta Directiva',
                        'estado'     => 'Activo',
                    ]);
                }

                // 3. Asignar el cargo en la Directiva
                Directiva::updateOrCreate(
                    [
                        'organization_id' => $orgId,
                        'cargo' => $cargoData['cargo_name'],
                    ],
                    [
                        'miembro_id' => $miembro->id,
                        'fecha_inicio' => $fecha_inicio,
                        'fecha_fin' => $fecha_fin,
                    ]
                );
            }

            \DB::commit();

            return redirect()->route('directiva.index')
                ->with('success', 'Junta Directiva actualizada exitosamente.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->with('error', 'Ocurrió un error al guardar la directiva: ' . $e->getMessage());
        }
    }

    /**
     * Guarda automáticamente un cargo de la directiva vía petición AJAX.
     */
    public function assignCargo(Request $request)
    {
        $request->validate([
            'cargo' => 'required|string',
            'persona_id' => 'nullable|integer|exists:personas,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);

        try {
            \DB::beginTransaction();

            $orgId = session('tenant_organization_id');
            $cargo = $request->cargo;
            $personaId = $request->persona_id;

            if (empty($personaId)) {
                // Si la persona se removió del select, eliminamos el cargo de la directiva actual
                Directiva::where('organization_id', $orgId)
                         ->where('cargo', $cargo)
                         ->delete();
            } else {
                // Obtener o crear el Miembro para esta organización
                $miembro = \App\Models\Miembros::where('persona_id', $personaId)
                    ->where(function ($q) use ($orgId) {
                        $q->where('organization_id', $orgId)
                          ->orWhereNull('organization_id');
                    })
                    ->first();
                
                if ($miembro) {
                    if (empty($miembro->organization_id)) {
                        $miembro->update(['organization_id' => $orgId]);
                    }
                } else {
                    $miembro = \App\Models\Miembros::create([
                        'persona_id' => $personaId,
                        'organization_id' => $orgId,
                        'direccion'  => 'Asignación automática desde Junta Directiva',
                        'estado'     => 'Activo',
                    ]);
                }

                // Asignar el cargo en la Directiva
                Directiva::updateOrCreate(
                    [
                        'organization_id' => $orgId,
                        'cargo' => $cargo,
                    ],
                    [
                        'miembro_id' => $miembro->id,
                        'fecha_inicio' => $request->fecha_inicio,
                        'fecha_fin' => $request->fecha_fin,
                    ]
                );
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cargo actualizado correctamente.'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el cargo: ' . $e->getMessage()
            ], 500);
        }
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
        try {
            \DB::beginTransaction();

            $directiva = Directiva::findOrFail($id);
            $data = $request->validated();
            $data['organization_id'] = session('tenant_organization_id');

            $directiva->update($data);

            \DB::commit();

            return redirect()->route('directiva.index')
                ->with('success', 'Miembro de directiva actualizado exitosamente.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el cargo de la directiva: ' . $e->getMessage());
        }
    }

    /**
     * Search personas for the wizard.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $orgId = session('tenant_organization_id');

        if (!$query) {
            $miembros = \App\Models\Miembros::with('persona')
                ->where('organization_id', $orgId)
                ->limit(5)
                ->get();

            $results = [];
            foreach ($miembros as $m) {
                if ($m->persona) {
                    $results[] = [
                        'id' => $m->persona->id,
                        'dni' => $m->persona->dni,
                        'text' => "{$m->persona->nombre} {$m->persona->apellido} ({$m->persona->dni})",
                        'type' => 'miembro',
                        'badge' => 'MIEMBRO'
                    ];
                }
            }

            // Fill with personas if less than 5 members
            if (count($results) < 5) {
                $miembroPersonaIds = $miembros->pluck('persona_id')->toArray();
                $personasExternas = \App\Models\Persona::whereNotIn('id', $miembroPersonaIds)
                    ->limit(5 - count($results))
                    ->get();
                
                foreach ($personasExternas as $p) {
                    $results[] = [
                        'id' => $p->id,
                        'dni' => $p->dni,
                        'text' => "{$p->nombre} {$p->apellido} ({$p->dni})",
                        'type' => 'persona',
                        'badge' => 'PERSONA EXTERNA'
                    ];
                }
            }

            return response()->json($results);
        }

        // Buscar primero en Miembros de esta organización
        $miembros = \App\Models\Miembros::with('persona')
            ->where('organization_id', $orgId)
            ->whereHas('persona', function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('apellido', 'LIKE', "%{$query}%")
                  ->orWhere('dni', 'LIKE', "%{$query}%");
            })
            ->get();

        $miembroPersonaIds = $miembros->pluck('persona_id')->toArray();

        // Buscar Personas que NO son miembros de esta organización
        $personasExternas = \App\Models\Persona::whereNotIn('id', $miembroPersonaIds)
            ->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('apellido', 'LIKE', "%{$query}%")
                  ->orWhere('dni', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get();

        $results = [];

        // Agregar Miembros primero con badge diferenciado
        foreach ($miembros as $m) {
            $results[] = [
                'id' => $m->persona->id,
                'dni' => $m->persona->dni,
                'text' => "{$m->persona->nombre} {$m->persona->apellido} ({$m->persona->dni})",
                'type' => 'miembro',
                'badge' => 'MIEMBRO'
            ];
        }

        // Agregar Personas Externas
        foreach ($personasExternas as $p) {
            $results[] = [
                'id' => $p->id,
                'dni' => $p->dni,
                'text' => "{$p->nombre} {$p->apellido} ({$p->dni})",
                'type' => 'persona',
                'badge' => 'PERSONA EXTERNA'
            ];
        }

        return response()->json($results);
    }

    /**
     * Registro rápido de Persona + Miembro vía AJAX.
     */
    public function storeQuickMember(Request $request)
    {
        // Validar datos ANTES de la transacción
        $request->validate([
            'dni' => 'required|string|max:20',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'telefono' => 'required|string|max:25',
            'email' => 'nullable|email|max:100',
            'direccion' => 'required|string|max:500',
        ]);

        try {
            \DB::beginTransaction();

            $orgId = session('tenant_organization_id');

            // Buscar o crear Persona (Normalizar DNI)
            $dni = preg_replace('/[^0-9]/', '', $request->dni);
            $persona = \App\Models\Persona::where('dni', $dni)->first();
            
            $personaData = [
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'estado' => 'Activo',
                'fecha_ingreso' => $persona ? $persona->fecha_ingreso : now(),
            ];

            if ($persona) {
                $persona->update($personaData);
            } else {
                $personaData['dni'] = $request->dni;
                $persona = \App\Models\Persona::create($personaData);
            }

            // Crear o capturar Miembro inmediatamente para la organización actual
            $miembro = \App\Models\Miembros::where('persona_id', $persona->id)
                ->where(function ($q) use ($orgId) {
                    $q->where('organization_id', $orgId)
                      ->orWhereNull('organization_id');
                })
                ->first();

            if ($miembro) {
                if (empty($miembro->organization_id)) {
                    $miembro->update(['organization_id' => $orgId]);
                }
            } else {
                $miembro = \App\Models\Miembros::create([
                    'persona_id' => $persona->id,
                    'organization_id' => $orgId,
                    'direccion' => $request->direccion ?: 'Registro rápido desde Directiva',
                    'estado' => 'Activo',
                ]);
            }

            \DB::commit();

            return response()->json([
                'id' => $persona->id,
                'dni' => $persona->dni,
                'nombre' => $persona->nombre,
                'apellido' => $persona->apellido,
                'text' => "{$persona->nombre} {$persona->apellido} ({$persona->dni})",
                'type' => 'miembro',
                'badge' => 'NUEVO MIEMBRO'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Directiva $directiva)
    {
        try {
            \DB::beginTransaction();
            
            $directiva->delete();

            \DB::commit();

            return redirect()->route('directiva.index')
                ->with('success', 'Miembro de directiva eliminado exitosamente.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->with('error', 'Ocurrió un error al eliminar el miembro de la directiva: ' . $e->getMessage());
        }
    }
}
