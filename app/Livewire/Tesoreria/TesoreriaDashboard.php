<?php

namespace App\Livewire\Tesoreria;

use App\Models\Cobro;
use App\Models\DetalleCobro;
use App\Models\DetallePago;
use App\Models\Pago;
use Livewire\Component;

class TesoreriaDashboard extends Component
{
    public ?string $fechaInicio = null;
    public ?string $fechaFin = null;

    public function mount(): void
    {
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
    }

    public function limpiarFiltros(): void
    {
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
    }

    public function getIngresosQueryProperty()
    {
        $orgId = session('tenant_organization_id');

        return Cobro::query()
            ->where('organization_id', $orgId)
            ->when($this->fechaInicio, fn ($q) => $q->whereDate('fecha_cobro', '>=', $this->fechaInicio))
            ->when($this->fechaFin, fn ($q) => $q->whereDate('fecha_cobro', '<=', $this->fechaFin));
    }

    public function getEgresosQueryProperty()
    {
        $orgId = session('tenant_organization_id');

        return Pago::query()
            ->where('organization_id', $orgId)
            ->when($this->fechaInicio, fn ($q) => $q->whereDate('fecha_pago', '>=', $this->fechaInicio))
            ->when($this->fechaFin, fn ($q) => $q->whereDate('fecha_pago', '<=', $this->fechaFin));
    }

    public function getTotalIngresosProperty(): float
    {
        return (float) $this->ingresosQuery->sum('total');
    }

    public function getTotalEgresosProperty(): float
    {
        return (float) $this->egresosQuery->sum('total');
    }

    public function getBalanceProperty(): float
    {
        return $this->totalIngresos - $this->totalEgresos;
    }

    public function getCantidadCobrosProperty(): int
    {
        return (int) $this->ingresosQuery->count();
    }

    public function getCantidadPagosProperty(): int
    {
        return (int) $this->egresosQuery->count();
    }

    public function getIngresosPorTipoProperty()
    {
        $orgId = session('tenant_organization_id');

        return DetalleCobro::query()
            ->whereHas('cobro', function ($q) use ($orgId) {
                $q->where('organization_id', $orgId)
                    ->when($this->fechaInicio, fn ($qq) => $qq->whereDate('fecha_cobro', '>=', $this->fechaInicio))
                    ->when($this->fechaFin, fn ($qq) => $qq->whereDate('fecha_cobro', '<=', $this->fechaFin));
            })
            ->selectRaw('concepto, SUM(monto) as total')
            ->groupBy('concepto')
            ->orderByDesc('total')
            ->get();
    }

    public function getEgresosPorTipoProperty()
    {
        $orgId = session('tenant_organization_id');

        return DetallePago::query()
            ->whereHas('pago', function ($q) use ($orgId) {
                $q->where('organization_id', $orgId)
                    ->when($this->fechaInicio, fn ($qq) => $qq->whereDate('fecha_pago', '>=', $this->fechaInicio))
                    ->when($this->fechaFin, fn ($qq) => $qq->whereDate('fecha_pago', '<=', $this->fechaFin));
            })
            ->selectRaw('tipo_detalle, SUM(monto) as total')
            ->groupBy('tipo_detalle')
            ->orderByDesc('total')
            ->get();
    }

    public function getCobrosRecientesProperty()
    {
        return $this->ingresosQuery
            ->with(['miembro.persona', 'recibos'])
            ->latest('fecha_cobro')
            ->limit(10)
            ->get();
    }

    public function getPagosRecientesProperty()
    {
        return $this->egresosQuery
            ->with(['empleado.persona', 'recibo', 'detalles'])
            ->latest('fecha_pago')
            ->limit(10)
            ->get();
    }

    public function getIngresosClasificadosProperty()
    {
        $orgId = session('tenant_organization_id');

        $detalles = DetalleCobro::query()
            ->whereHas('cobro', function ($q) use ($orgId) {
                $q->where('organization_id', $orgId)
                    ->when($this->fechaInicio, fn ($qq) => $qq->whereDate('fecha_cobro', '>=', $this->fechaInicio))
                    ->when($this->fechaFin, fn ($qq) => $qq->whereDate('fecha_cobro', '<=', $this->fechaFin));
            })
            ->get();

        $grupos = [
            'Servicios' => [],
            'Aportaciones' => [],
            'Donaciones' => [],
            'Otros' => [],
        ];

        foreach ($detalles as $detalle) {
            if ($detalle->es_donacion) {
                $grupo = 'Donaciones';
            } elseif (str_starts_with($detalle->concepto ?? '', 'Aportación:')) {
                $grupo = 'Aportaciones';
            } elseif (!is_null($detalle->servicio_id)) {
                $grupo = 'Servicios';
            } else {
                $grupo = 'Otros';
            }

            if (!isset($grupos[$grupo][$detalle->concepto])) {
                $grupos[$grupo][$detalle->concepto] = 0;
            }

            $grupos[$grupo][$detalle->concepto] += (float) $detalle->monto;
        }

        return collect($grupos)->map(function ($conceptos, $grupo) {
            $coleccion = collect($conceptos)->map(function ($total, $concepto) {
                return [
                    'concepto' => $concepto,
                    'total' => $total,
                ];
            })->sortByDesc('total')->values();

            return [
                'grupo' => $grupo,
                'total' => $coleccion->sum('total'),
                'conceptos' => $coleccion,
            ];
        });
    }

    public function getEgresosClasificadosProperty()
    {
        $orgId = session('tenant_organization_id');

        $detalles = DetallePago::query()
            ->whereHas('pago', function ($q) use ($orgId) {
                $q->where('organization_id', $orgId)
                    ->when($this->fechaInicio, fn ($qq) => $qq->whereDate('fecha_pago', '>=', $this->fechaInicio))
                    ->when($this->fechaFin, fn ($qq) => $qq->whereDate('fecha_pago', '<=', $this->fechaFin));
            })
            ->get();

        $grupos = [
            'Salarios' => [],
            'Mantenimientos' => [],
            'Otros pagos' => [],
        ];

        foreach ($detalles as $detalle) {
            if (($detalle->tipo_detalle ?? '') === 'salario') {
                $grupo = 'Salarios';
            } elseif (($detalle->tipo_detalle ?? '') === 'mantenimiento') {
                $grupo = 'Mantenimientos';
            } else {
                $grupo = 'Otros pagos';
            }

            if (!isset($grupos[$grupo][$detalle->concepto])) {
                $grupos[$grupo][$detalle->concepto] = 0;
            }

            $grupos[$grupo][$detalle->concepto] += (float) $detalle->monto;
        }

        return collect($grupos)->map(function ($conceptos, $grupo) {
            $coleccion = collect($conceptos)->map(function ($total, $concepto) {
                return [
                    'concepto' => $concepto,
                    'total' => $total,
                ];
            })->sortByDesc('total')->values();

            return [
                'grupo' => $grupo,
                'total' => $coleccion->sum('total'),
                'conceptos' => $coleccion,
            ];
        });
    }

    public function render()
    {
        return view('livewire.tesoreria.tesoreria-dashboard', [
            'totalIngresos' => $this->totalIngresos,
            'totalEgresos' => $this->totalEgresos,
            'balance' => $this->balance,
            'cantidadCobros' => $this->cantidadCobros,
            'cantidadPagos' => $this->cantidadPagos,
            'ingresosPorTipo' => $this->ingresosPorTipo,
            'egresosPorTipo' => $this->egresosPorTipo,
            'ingresosClasificados' => $this->ingresosClasificados,
            'egresosClasificados' => $this->egresosClasificados,
            'cobrosRecientes' => $this->cobrosRecientes,
            'pagosRecientes' => $this->pagosRecientes,

        ]);
    }
}