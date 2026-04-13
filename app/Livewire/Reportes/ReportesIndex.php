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
    public $filterMode = 'range'; // 'range' or 'monthly'
    public $selectedMonth;
    public $selectedYear;
    public $dateFrom;
    public $dateTo;
    public $results = [];
    public $summary = [];

    protected $queryString = [
        'reportType' => ['except' => 'ingresos'],
        'filterMode' => ['except' => 'range'],
        'selectedMonth',
        'selectedYear',
        'dateFrom',
        'dateTo'
    ];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;
        
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedReportType()
    {
        $this->results = [];
        $this->summary = [];
    }

    public function updatedFilterMode()
    {
        if ($this->filterMode === 'monthly') {
            $this->syncDatesFromMonth();
        }
    }

    public function updatedSelectedMonth()
    {
        if ($this->filterMode === 'monthly') {
            $this->syncDatesFromMonth();
        }
    }

    public function updatedSelectedYear()
    {
        if ($this->filterMode === 'monthly') {
            $this->syncDatesFromMonth();
        }
    }

    private function syncDatesFromMonth()
    {
        $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1);
        $this->dateFrom = $date->startOfMonth()->format('Y-m-d');
        $this->dateTo = $date->endOfMonth()->format('Y-m-d');
    }

    public function generate()
    {
        $orgId = session('tenant_organization_id');
        
        switch ($this->reportType) {
            case 'ingresos':
                $this->results = Cobro::where('organization_id', $orgId)
                    ->whereBetween('fecha_cobro', [$this->dateFrom, $this->dateTo])
                    ->with('miembro.persona')
                    ->orderBy('fecha_cobro', 'desc')
                    ->get();
                $this->summary['total'] = $this->results->sum('total');
                break;

            case 'egresos':
                $this->results = Pago::where('organization_id', $orgId)
                    ->whereBetween('fecha_pago', [$this->dateFrom, $this->dateTo])
                    ->orderBy('fecha_pago', 'desc')
                    ->get();
                $this->summary['total'] = $this->results->sum('total');
                break;

            case 'mantenimientos':
                $this->results = Mantenimiento::where('organization_id', $orgId)
                    ->whereNull('pago_id')
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
                $this->summary['total_pendiente'] = $this->results->sum('monto_pendiente');
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
