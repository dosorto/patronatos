<?php

namespace App\Livewire\Pagos;

use App\Models\DetallePago;
use App\Models\Empleado;
use App\Models\Mantenimiento;
use App\Models\Pago;
use App\Models\Recibo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreatePago extends Component
{
    public string $tipoPago = 'Efectivo'; // fijo por defecto
    public string $tipoPagoActual = 'salarios'; // salarios | mantenimientos | otro_pago

    public array $empleadosDisponibles = [];
    public ?int $empleadoSeleccionadoId = null;

    public array $mantenimientosPendientes = [];
    public ?int $mantenimientoSeleccionadoId = null;

    public string $conceptoOtroPago = '';
    public string $descripcionOtroPago = '';
    public float $montoOtroPago = 0;

    // Búsqueda de empleados
    public string $searchEmpleado = '';
    public array $searchEmpleadoResults = [];
    public bool $showSearchEmpleadoResults = false;
    public ?array $empleadoSeleccionado = null;

    public array $itemsAgregados = [];

    public function mount(): void
    {
        $this->cargarEmpleados();
        $this->cargarMantenimientosPendientes();
    }

    public function cargarEmpleados(): void
    {
        $this->empleadosDisponibles = Empleado::with('persona')
            ->whereHas('persona')
            ->get()
            ->map(function ($empleado) {
                $nombre = trim(($empleado->persona->nombre ?? '') . ' ' . ($empleado->persona->apellido ?? ''));

                return [
                    'id' => $empleado->id,
                    'nombre' => $nombre !== '' ? $nombre : 'Empleado sin nombre',
                    'cargo' => $empleado->cargo ?? 'Sin cargo',
                    'sueldo_mensual' => (float) ($empleado->sueldo_mensual ?? 0),
                    'persona_id' => $empleado->persona_id,
                ];
            })
            ->toArray();
    }

    public function updatedSearchEmpleado()
    {
        if (strlen($this->searchEmpleado) < 2) {
            $this->searchEmpleadoResults = [];
            $this->showSearchEmpleadoResults = false;
            return;
        }

        $this->searchEmpleadoResults = Empleado::with('persona')
            ->whereHas('persona', function ($query) {
                $query->where('nombre', 'like', '%' . $this->searchEmpleado . '%')
                    ->orWhere('apellido', 'like', '%' . $this->searchEmpleado . '%');
            })
            ->get()
            ->map(function ($empleado) {
                return [
                    'id' => $empleado->id,
                    'nombre' => trim(($empleado->persona->nombre ?? '') . ' ' . ($empleado->persona->apellido ?? '')),
                    'cargo' => $empleado->cargo ?? 'Sin cargo',
                    'sueldo_mensual' => (float) ($empleado->sueldo_mensual ?? 0),
                    'persona_id' => $empleado->persona_id,
                ];
            })
            ->toArray();

        $this->showSearchEmpleadoResults = count($this->searchEmpleadoResults) > 0;
    }

    public function selectEmpleado($empleadoId): void
    {
        $empleado = collect($this->empleadosDisponibles)->firstWhere('id', (int) $empleadoId);

        if (!$empleado) {
            $empleadoModel = Empleado::with('persona')->find($empleadoId);

            if (!$empleadoModel) {
                session()->flash('error', 'Empleado no encontrado');
                return;
            }

            $empleado = [
                'id' => $empleadoModel->id,
                'nombre' => trim(($empleadoModel->persona->nombre ?? '') . ' ' . ($empleadoModel->persona->apellido ?? '')),
                'cargo' => $empleadoModel->cargo ?? 'Sin cargo',
                'sueldo_mensual' => (float) ($empleadoModel->sueldo_mensual ?? 0),
                'persona_id' => $empleadoModel->persona_id,
            ];
        }

        $this->empleadoSeleccionado = $empleado;
        $this->empleadoSeleccionadoId = $empleado['id'];
        $this->searchEmpleado = '';
        $this->searchEmpleadoResults = [];
        $this->showSearchEmpleadoResults = false;
    }

    public function cargarMantenimientosPendientes(): void
    {
        $orgId = session('tenant_organization_id');

        $yaAgregados = collect($this->itemsAgregados)
            ->where('tipo', 'mantenimiento')
            ->pluck('mantenimiento_id')
            ->filter()
            ->toArray();

        $this->mantenimientosPendientes = Mantenimiento::where('organization_id', $orgId)
            ->whereNull('pago_id')
            ->whereNotIn('id', $yaAgregados)
            ->orderByDesc('fecha_registro')
            ->get()
            ->map(function ($mantenimiento) {
                return [
                    'id' => $mantenimiento->id,
                    'tipo_mantenimiento' => $mantenimiento->tipo_mantenimiento,
                    'descripcion' => $mantenimiento->descripcion,
                    'prioridad' => $mantenimiento->prioridad,
                    'fecha_registro' => optional($mantenimiento->fecha_registro)->format('d/m/Y'),
                    'estado' => $mantenimiento->estado,
                    'costo_estimado' => (float) ($mantenimiento->costo_estimado ?? 0),
                ];
            })
            ->toArray();
    }

    public function cambiarTipoPago(string $tipo): void
    {
        $this->tipoPagoActual = $tipo;

        if ($tipo === 'mantenimientos') {
            $this->cargarMantenimientosPendientes();
        }
    }

    public function addSalario(): void
    {
        if (!$this->empleadoSeleccionadoId || !$this->empleadoSeleccionado) {
            session()->flash('error', 'Selecciona un empleado');
            return;
        }

        $empleado = $this->empleadoSeleccionado;

        $yaExiste = collect($this->itemsAgregados)->contains(function ($item) use ($empleado) {
            return ($item['tipo'] ?? null) === 'salario'
                && ($item['empleado_id'] ?? null) === $empleado['id'];
        });

        if ($yaExiste) {
            session()->flash('error', 'Ese salario ya fue agregado');
            return;
        }

        if (($empleado['sueldo_mensual'] ?? 0) <= 0) {
            session()->flash('error', 'El empleado no tiene sueldo mensual válido');
            return;
        }

        $this->itemsAgregados[] = [
            'id' => uniqid(),
            'tipo' => 'salario',
            'empleado_id' => $empleado['id'],
            'mantenimiento_id' => null,
            'persona_id' => $empleado['persona_id'] ?? null,
            'concepto' => 'Salario - ' . $empleado['nombre'],
            'descripcion' => 'Pago de salario mensual (' . ($empleado['cargo'] ?: 'Sin cargo') . ')',
            'monto' => (float) $empleado['sueldo_mensual'],
            'periodo' => now()->format('Y-m'),
            'nombre_persona' => $empleado['nombre'],
        ];

        $this->empleadoSeleccionado = null;
        $this->empleadoSeleccionadoId = null;
        $this->searchEmpleado = '';

        session()->flash('success', 'Salario agregado correctamente');
    }

    public function addMantenimiento(): void
    {
        if (!$this->mantenimientoSeleccionadoId) {
            session()->flash('error', 'Selecciona un mantenimiento');
            return;
        }

        $mantenimiento = collect($this->mantenimientosPendientes)
            ->firstWhere('id', $this->mantenimientoSeleccionadoId);

        if (!$mantenimiento) {
            session()->flash('error', 'Mantenimiento no encontrado');
            return;
        }

        if (($mantenimiento['costo_estimado'] ?? 0) <= 0) {
            session()->flash('error', 'El mantenimiento no tiene costo estimado válido');
            return;
        }

        $this->itemsAgregados[] = [
            'id' => uniqid(),
            'tipo' => 'mantenimiento',
            'empleado_id' => null,
            'mantenimiento_id' => $mantenimiento['id'],
            'persona_id' => null,
            'concepto' => 'Mantenimiento - ' . $mantenimiento['tipo_mantenimiento'],
            'descripcion' => $mantenimiento['descripcion'],
            'monto' => (float) $mantenimiento['costo_estimado'],
            'periodo' => null,
            'nombre_persona' => null,
        ];

        $this->mantenimientoSeleccionadoId = null;
        $this->cargarMantenimientosPendientes();

        session()->flash('success', 'Mantenimiento agregado correctamente');
    }

    public function addOtroPago(): void
    {
        if (trim($this->conceptoOtroPago) === '' || $this->montoOtroPago <= 0) {
            session()->flash('error', 'Ingresa concepto y monto válido');
            return;
        }

        $this->itemsAgregados[] = [
            'id' => uniqid(),
            'tipo' => 'otro_pago',
            'empleado_id' => null,
            'mantenimiento_id' => null,
            'persona_id' => null,
            'concepto' => trim($this->conceptoOtroPago),
            'descripcion' => trim($this->descripcionOtroPago) !== '' ? trim($this->descripcionOtroPago) : null,
            'monto' => (float) $this->montoOtroPago,
            'periodo' => null,
            'nombre_persona' => null,
        ];

        $this->conceptoOtroPago = '';
        $this->descripcionOtroPago = '';
        $this->montoOtroPago = 0;

        session()->flash('success', 'Otro pago agregado correctamente');
    }

    public function removeItem(string $id): void
    {
        $this->itemsAgregados = array_values(array_filter(
            $this->itemsAgregados,
            fn ($item) => $item['id'] !== $id
        ));

        $this->cargarMantenimientosPendientes();
    }

    public function getTotal(): float
    {
        return (float) array_reduce(
            $this->itemsAgregados,
            fn ($carry, $item) => $carry + (float) $item['monto'],
            0
        );
    }

    public function generarRecibo()
    {
        if (count($this->itemsAgregados) === 0) {
            session()->flash('error', 'Agrega al menos un item al pago');
            return;
        }

        $tieneSalarios = collect($this->itemsAgregados)
            ->contains(fn ($item) => $item['tipo'] === 'salario');

        $tieneNoSalarios = collect($this->itemsAgregados)
            ->contains(fn ($item) => $item['tipo'] !== 'salario');

        if ($tieneSalarios && $tieneNoSalarios) {
            session()->flash('error', 'No puedes mezclar salarios con mantenimientos u otros pagos en el mismo proceso.');
            return;
        }

        $soloSalarios = collect($this->itemsAgregados)
            ->every(fn ($item) => $item['tipo'] === 'salario');

        if ($soloSalarios) {
            return $this->generarPagosSalariosIndividuales();
        }

        return $this->generarPagoAgrupado();
    }

    private function generarPagosSalariosIndividuales()
    {
        try {
            DB::beginTransaction();

            $orgId = session('tenant_organization_id');
            $cantidadRecibos = 0;

            foreach ($this->itemsAgregados as $item) {
                $pago = Pago::create([
                    'organization_id'   => $orgId,
                    'persona_id'        => $item['persona_id'] ?? null,
                    'empleado_id'       => $item['empleado_id'] ?? null,
                    'fecha_pago'        => now()->toDateString(),
                    'tipo_pago'         => 'Efectivo',
                    'total'             => $item['monto'],
                    'id_tipo_movimiento'=> null,
                    'nombre_persona'    => $item['nombre_persona'] ?? null,
                    'descripcion'       => $item['descripcion'] ?? 'Pago de salario',
                ]);

                DetallePago::create([
                    'pago_id'          => $pago->id,
                    'tipo_detalle'     => 'salario',
                    'empleado_id'      => $item['empleado_id'] ?? null,
                    'mantenimiento_id' => null,
                    'concepto'         => $item['concepto'],
                    'descripcion'      => $item['descripcion'] ?? null,
                    'monto'            => $item['monto'],
                    'periodo'          => $item['periodo'] ?? now()->format('Y-m'),
                ]);

                $correlativo = Recibo::where('anio', now()->year)->max('correlativo') ?? 0;
                $correlativo++;

                Recibo::create([
                    'pago_id'       => $pago->id,
                    'cobro_id'      => null,
                    'correlativo'   => $correlativo,
                    'nombre'        => 'PAG-' . now()->year . '-' . str_pad($correlativo, 6, '0', STR_PAD_LEFT),
                    'fecha_emision' => now()->toDateString(),
                    'anio'          => now()->year,
                    'monto'         => $item['monto'],
                    'user_id'       => auth()->id(),
                ]);

                $cantidadRecibos++;
            }

            DB::commit();

            $this->limpiar();

            session()->flash('success', 'Se generaron ' . $cantidadRecibos . ' recibos de salarios correctamente.');
            return $this->redirect(route('pago.index'), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error generando recibos individuales de salarios: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    private function generarPagoAgrupado()
    {
        try {
            DB::beginTransaction();

            $orgId = session('tenant_organization_id');
            $total = $this->getTotal();

            $pago = Pago::create([
                'organization_id'    => $orgId,
                'persona_id'         => null,
                'empleado_id'        => null,
                'fecha_pago'         => now()->toDateString(),
                'tipo_pago'          => 'Efectivo',
                'total'              => $total,
                'id_tipo_movimiento' => null,
                'nombre_persona'     => 'Varios beneficiarios',
                'descripcion'        => 'Pago agrupado generado desde módulo de pagos',
            ]);

            foreach ($this->itemsAgregados as $item) {
                DetallePago::create([
                    'pago_id'          => $pago->id,
                    'tipo_detalle'     => $item['tipo'],
                    'empleado_id'      => $item['empleado_id'] ?? null,
                    'mantenimiento_id' => $item['mantenimiento_id'] ?? null,
                    'concepto'         => $item['concepto'],
                    'descripcion'      => $item['descripcion'] ?? null,
                    'monto'            => $item['monto'],
                    'periodo'          => $item['periodo'] ?? null,
                ]);

                if (($item['tipo'] ?? null) === 'mantenimiento' && !empty($item['mantenimiento_id'])) {
                    Mantenimiento::where('id', $item['mantenimiento_id'])->update([
                        'pago_id' => $pago->id,
                    ]);
                }
            }

            $correlativo = Recibo::where('anio', now()->year)->max('correlativo') ?? 0;
            $correlativo++;

            $recibo = Recibo::create([
                'pago_id'       => $pago->id,
                'cobro_id'      => null,
                'correlativo'   => $correlativo,
                'nombre'        => 'PAG-' . now()->year . '-' . str_pad($correlativo, 6, '0', STR_PAD_LEFT),
                'fecha_emision' => now()->toDateString(),
                'anio'          => now()->year,
                'monto'         => $total,
                'user_id'       => auth()->id(),
            ]);

            DB::commit();

            $this->limpiar();

            session()->flash('success', 'Pago generado correctamente.');
            return $this->redirect(route('recibo.show', $recibo->id), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error generando pago agrupado: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }
    public function limpiar(): void
    {
        $this->tipoPago = 'Efectivo';
        $this->tipoPagoActual = 'salarios';
        $this->empleadoSeleccionadoId = null;
        $this->mantenimientoSeleccionadoId = null;
        $this->conceptoOtroPago = '';
        $this->descripcionOtroPago = '';
        $this->montoOtroPago = 0;
        $this->itemsAgregados = [];
        $this->searchEmpleado = '';
        $this->searchEmpleadoResults = [];
        $this->showSearchEmpleadoResults = false;
        $this->empleadoSeleccionado = null;

        $this->cargarEmpleados();
        $this->cargarMantenimientosPendientes();
    }

    public function render()
    {
        return view('livewire.pagos.create-pago', [
            'empleadosDisponibles' => $this->empleadosDisponibles,
            'mantenimientosPendientes' => $this->mantenimientosPendientes,
            'total' => $this->getTotal(),
        ]);
    }
}