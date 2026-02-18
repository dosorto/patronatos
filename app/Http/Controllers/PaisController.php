<?php

namespace App\Http\Controllers;
use App\Http\Requests\StorePaisRequest;
use App\Http\Requests\UpdatePaisRequest;
use Illuminate\Http\Request;
use App\Models\Pais;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaisesExport;


class PaisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Pais.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Pais.create');
    }

    public function store(StorePaisRequest $request)
    {
        // Crear Pais
        Pais::create([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('pais.index')
            ->with('success', 'Pais creado exitosamente.');
    }



    public function show(Pais $pais)
    {
        return view('Pais.show', compact('pais'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pais $pais)
        {
            // Pasamos el modelo a la vista
            return view('Pais.edit', compact('pais'));
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaisRequest $request, Pais $pais)
    {
        $pais->update([
            'nombre' => $request->nombre,   
        ]);

        return redirect()->route('pais.index')
            ->with('success', 'País actualizado exitosamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pais $pais)
    {
        $pais->delete();

        return redirect()->route('pais.index')
            ->with('success', 'País eliminado exitosamente.');
    }

    public function exportExcel()
    {
        return Excel::download(new PaisesExport, 'paises_' . now()->format('Y_m_d_His') . '.xlsx');
    }

}
