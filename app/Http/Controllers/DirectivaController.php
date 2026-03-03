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
        $miembros = \App\Models\Miembros::with('persona')->get();
        $organizations = \App\Models\Organization::all();
        return view('directiva.create', compact('miembros', 'organizations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDirectivaRequest $request)
    {
        Directiva::create($request->validated());

        return redirect()->route('directiva.index')
            ->with('success', 'Miembro de directiva creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Directiva $directiva)
    {
        return view('directiva.show', compact('directiva'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Directiva $directiva)
    {
        $miembros = \App\Models\Miembros::with('persona')->get();
        $organizations = \App\Models\Organization::all();
        return view('directiva.edit', compact('directiva', 'miembros', 'organizations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDirectivaRequest $request, Directiva $directiva)
    {
        $directiva->update($request->validated());

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
