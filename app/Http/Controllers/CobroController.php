<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CobroController extends Controller
{
        public function index()
        {
            return view('Cobro.index');
        }
    
        public function create()
        {
            abort_if(!auth()->user()->can('cobro.create'), 403);
            return view('cobro.create');
        }
    
        public function store(Request $request)
        {
            // Lógica para almacenar un nuevo cobro en la base de datos
        }
    
        public function show($id)
        {
            // Lógica para mostrar los detalles de un cobro específico
        }
    
        public function edit($id)
        {
            // Lógica para mostrar el formulario de edición de un cobro
        }
    
        public function update(Request $request, $id)
        {
            // Lógica para actualizar un cobro existente en la base de datos
        }
    
        public function destroy($id)
        {
            // Lógica para eliminar un cobro de la base de datos
        }

        public function export()
        {
            abort_if(!auth()->user()->can('cobro.export'), 403);
            
            $orgId = session('tenant_organization_id');
            $org = \App\Models\Organization::find($orgId);
            $orgNombre = $org ? \Illuminate\Support\Str::slug($org->name) : 'organization';
            $fecha = now()->format('Y_m_d_His');

            return Excel::download(
                new CobroExport($orgId),
                $orgNombre . '_cobros_' . $fecha . '.xlsx'
            );
        }
}
