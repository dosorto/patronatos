<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PagoExport;

class PagoController extends Controller
{
    public function index()
    {
        return view('pago.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('pago.create'), 403);
        return view('pago.create');
    }

    public function store(Request $request)
    {
        // La lógica real la está manejando Livewire en CreatePago
    }

    public function show($id)
    {
        // Luego aquí puedes mostrar el detalle de un pago específico
    }

    public function edit($id)
    {
        // Luego aquí puedes mostrar el formulario de edición
    }

    public function update(Request $request, $id)
    {
        // Luego aquí actualizas el pago
    }

    public function destroy($id)
    {
        // Luego aquí eliminas el pago
    }

    public function exportExcel()
    {
        abort_if(!auth()->user()->can('pago.export'), 403);

        $orgId = session('tenant_organization_id');
        $org = \App\Models\Organization::find($orgId);
        $orgNombre = $org ? \Illuminate\Support\Str::slug($org->name) : 'organization';
        $fecha = now()->format('Y_m_d_His');

        return Excel::download(
            new PagoExport($orgId),
            $orgNombre . '_pagos_' . $fecha . '.xlsx'
        );
    }
}