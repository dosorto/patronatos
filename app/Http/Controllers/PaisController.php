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
        return view('pais.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pais.create');
    }

    public function store(StorePaisRequest $request)
    {
        // Crear Pais
        Pais::create([
            'nombre' => $request->nombre,
            'iso' => strtoupper($request->iso),
        ]);


        return redirect()->route('pais.index')
            ->with('success', 'Pais creado exitosamente.');
    }



    public function show($id)
    {
        $pais = Pais::findOrFail($id);
        return view('pais.show', compact('pais'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pais = Pais::findOrFail($id); // evita route-model binding para no dar 404
        // Pasamos el modelo a la vista
        return view('pais.edit', compact('pais'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaisRequest $request, $id)
    {
        $pais = Pais::findOrFail($id); // evita 404 de route-model binding
        $pais->update([
            'nombre' => $request->nombre,
            'iso' => strtoupper($request->iso),
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
