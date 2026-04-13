<?php

namespace App\Livewire\Reportes;

use Livewire\Component;
use App\Models\Cobro;
use App\Models\Pago;
use App\Models\Mantenimiento;
use App\Models\Miembros;
use App\Models\Organization;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesIndex extends Component
{
    public $reportType = 'ingresos';
    public $dateFrom;
    public $dateTo;
    public $results = [];
    public $summary = [];

    protected $queryString = ['reportType', 'dateFrom', 'dateTo'];

    public function mount()
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function generate()
    {
        $orgId = session('tenant_organization_id');
        
        switch ($this->reportType) {
            case 'ingresos':
                $this->results = Cobro::where('organization_id', $orgId)
                    ->whereBetween('fecha', [$this->dateFrom, $this->dateTo])
                    ->with('miembro.persona')
                    ->orderBy('fecha', 'desc')
                    ->get();
                $this->summary['total'] = $this->results->sum('total');
                break;

            case 'egresos':
                $this->results = Pago::where('organization_id', $orgId)
                    ->whereBetween('fecha', [$this->dateFrom, $this->dateTo])
                    ->orderBy('fecha', 'desc')
                    ->get();
                $this->summary['total'] = $this->results->sum('total');
                break;

            case 'mantenimientos':
                $this->results = Mantenimiento::where('organization_id', $orgId)
                    ->whereBetween('fecha_registro', [$this->dateFrom, $this->dateTo])
                    ->with('activo')
                    ->orderBy('fecha_registro', 'desc')
                    ->get();
                $this->summary['total_estimado'] = $this->results->sum('costo_estimado');
                break;

            case 'miembros':
                $this->results = Miembros::where('organization_id', $orgId)
                    ->with('persona')
                    ->get();
                break;

            case 'moras':
                // Aquí podrías filtrar moras activas
                $this->results = \App\Models\Mora::where('organization_id', $orgId)
                    ->where('estado', 'Pendiente')
                    ->with('miembro.persona')
                    ->get();
                $this->summary['total_pendiente'] = $this->results->sum('monto');
                break;
        }
    }

    public function exportPdf()
    {
        $this->generate();
        $orgId = session('tenant_organization_id');
        $organization = Organization::find($orgId);

        $pdf = Pdf::loadView('reportes.pdf.' . $this->reportType, [
            'results' => $this->results,
            'summary' => $this->summary,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'organization' => $organization
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'reporte_' . $this->reportType . '_' . now()->format('YmdHis') . '.pdf');
    }

    public function render()
    {
        return view('livewire.reportes.reportes-index');
    }
}
