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

    public function store(Request $request)
    {
        $step = $request->input('step');
        
        \Log::info('Paso recibido: ' . $step);
        \Log::info('Datos del request paso 1:', $request->all());

        // ======================
        // PASO 1
        // ======================
        if ($step == 1) {

            $validated = $request->validate([
                'persona_id' => 'nullable|exists:personas,id',
                'nueva_nombre' => 'required_without:persona_id|string|max:255',
                'nueva_apellido' => 'required_without:persona_id|string|max:255',
                'nueva_dni' => 'required_without:persona_id|string|max:20',
                'nueva_fecha_nacimiento' => 'nullable|date',
                'nueva_sexo' => 'nullable|in:M,F',
                'nueva_telefono' => 'nullable|string|max:20',
                'nueva_email' => 'nullable|email|max:255',
                'crear_persona' => 'nullable|in:0,1',
            ], [
                'persona_id.unique' => 'Esta persona ya está registrada como miembro.',
                'nueva_dni.unique' => 'Este DNI ya está registrado.',
                'nueva_email.unique' => 'Este email ya está registrado.',
            ]);

            \Log::info('Datos validados paso 1:', $validated);
            
            // Guardar todos los datos en sesión
            $request->session()->put('miembro_datos', $validated);
            
            // Verificar que se guardaron
            \Log::info('Datos en sesión después de guardar:', session()->get('miembro_datos'));

            return redirect()->route('miembro.create', ['step' => 2]);
        }

        // ======================
        // PASO 2
        // ======================
       if ($step == 2) {

            $validatedDireccion = $request->validate([
                'direccion' => 'required|string|max:255',
            ]);

            $miembroDatos = $request->session()->get('miembro_datos');
            
            \Log::info('Datos recuperados de sesión en paso 2:', $miembroDatos ?? ['no hay datos']);

            if (!$miembroDatos) {
                return redirect()->route('miembro.create', ['step' => 1])
                    ->with('error', 'Debes completar el paso 1 primero.');
            }

            // ✅ VALIDACIÓN AQUÍ
            if (!empty($miembroDatos['persona_id'])) {
                $yaExiste = Miembros::where('persona_id', $miembroDatos['persona_id'])->exists();
                if ($yaExiste) {
                    $request->session()->forget('miembro_datos');
                    return redirect()->route('miembro.create', ['step' => 1])
                        ->with('error', 'Esta persona ya está registrada como miembro.');
                }
                $persona = Persona::findOrFail($miembroDatos['persona_id']);
            } else {
                $persona = Persona::create([
                    'nombre' => $miembroDatos['nueva_nombre'],
                    'apellido' => $miembroDatos['nueva_apellido'],
                    'dni' => $miembroDatos['nueva_dni'],
                    'fecha_nacimiento' => $miembroDatos['nueva_fecha_nacimiento'] ?? null,
                    'sexo' => $miembroDatos['nueva_sexo'] ?? null,
                    'telefono' => $miembroDatos['nueva_telefono'] ?? null,
                    'email' => $miembroDatos['nueva_email'] ?? null,
                ]);
            }

            Miembros::create([
                'persona_id' => $persona->id,
                'direccion' => $validatedDireccion['direccion'],
                'estado' => 1,
            ]);

            $request->session()->forget('miembro_datos');

            return redirect()->route('miembro.index')
                ->with('success', 'Miembro creado correctamente.');
        }
        return redirect()->route('miembro.create', ['step' => 1]);
    }

    public function create()
    {
        $personas = Persona::all();
        $step = request()->input('step', 1);
        
        // Obtener datos de la sesión si existen
        $miembroDatos = session()->get('miembro_datos', []);
        
        \Log::info('Vista create - Paso: ' . $step);
        \Log::info('Vista create - Datos de sesión:', $miembroDatos);
        
        return view('Miembro.create', compact('personas', 'step', 'miembroDatos'));
    }
        

    public function show($id)
    {
        $miembro = \App\Models\Miembros::findOrFail($id);

        // Obtener la organización del usuario actual (igual que en export)
        $orgId = session('tenant_organization_id');
        $organization = \App\Models\Organization::with([
            'municipio.departamento.pais',
            'departamento'
        ])->find($orgId);  // ✅ Busca por la organización de sesión

        return view('Miembro.show', compact('miembro', 'organization'));
    }

    public function edit($id)
    {
        $miembro = \App\Models\Miembros::findOrFail($id); // evita route-model binding para no dar 404
        $personas = Persona::all();

        return view('Miembro.edit', compact('miembro', 'personas'));
    }

    public function update(UpdateMiembroRequest $request, $id)
    {
        $miembro = \App\Models\Miembros::findOrFail($id); // evita 404 de route-model binding
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
