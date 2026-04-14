<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use Illuminate\Support\Str;

class ReciboController extends Controller
{
    public function show($id)
    {
        $recibo = Recibo::with(['cobro.miembro.persona', 'cobro.detallesCobros.servicio'])->findOrFail($id);
        $orgId = session('tenant_organization_id');
        $organization = \App\Models\Organization::find($orgId);
        
        return view('recibo.show', compact('recibo', 'organization'));
    }

    public function exportPdf($id)
    {
        $recibo = Recibo::with([
            'cobro.miembro.persona',
            'cobro.detallesCobros',
            'user'
        ])->findOrFail($id);

        $organization = \App\Models\Organization::find(session('tenant_organization_id'));

        $dateStr = now()->format('Y_m_d_His');
        $fileName = 'recibo_' . str_pad($recibo->correlativo, 6, '0', STR_PAD_LEFT) . '_' . $dateStr . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('recibo.pdf', compact('recibo', 'organization'))
            ->setPaper('a4', 'landscape')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download($fileName);
    }
}