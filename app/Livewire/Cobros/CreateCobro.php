<?php

namespace App\Livewire\Cobros;

use App\Models\Cobro;
use App\Models\DetalleCobro;
use App\Models\LecturaMedidores;
use App\Models\Medidores;
use App\Models\Miembros;
use App\Models\Persona;
use App\Models\Recibo;
use App\Models\Servicio;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CreateCobro extends Component
{
    // Búsqueda de persona
    public string $searchQuery = '';
    public array $searchResults = [];
    public ?Miembros $selectedMiembro = null;
    public ?Persona $selectedPersona = null;

    // Servicios a agregar
    public array $servicios = [];
    public ?int $selectedServicioId = null;
    public ?float $selectedServicioPrecio = null;
    public array $agregadosServicios = [];

    // Modal de medidor
    public bool $showModalMedidor = false;
    public ?int $servicioEnProceso = null;
    public ?float $lecturaAnterior = null;
    public ?float $lecturaActual = null;
    public ?float $consumoCalculado = null;
    public ?Medidores $medidorActual = null;

    // Estados
    public bool $showSearchResults = false;
    public bool $showServiciosAñadidos = false;
    public int $currentStep = 1;

    public function goToStep(int $step): void
    {
        $this->currentStep = $step;
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->searchResults = [];
            $this->showSearchResults = false;
            return;
        }

        $this->searchResults = Miembros::with('persona')
            ->whereHas('persona', function ($query) {
                $query->where('dni', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('nombre', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('apellido', 'like', '%' . $this->searchQuery . '%');
            })
            ->get()
            ->map(function ($miembro) {
                return [
                    'id' => $miembro->id,
                    'persona_id' => $miembro->persona_id,
                    'dni' => $miembro->persona->dni,
                    'nombre' => $miembro->persona->nombre . ' ' . $miembro->persona->apellido,
                    'direccion' => $miembro->direccion,
                ];
            })
            ->toArray();

        $this->showSearchResults = count($this->searchResults) > 0;
    }

    public function selectMiembro($miembroId)
    {
        $this->selectedMiembro = Miembros::with('persona')->findOrFail($miembroId);
        $this->selectedPersona = $this->selectedMiembro->persona;
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->showSearchResults = false;

        $orgId = session('tenant_organization_id');
        $this->servicios = Servicio::where('organization_id', $orgId)
            ->where('estado', 1)
            ->select('id', 'nombre', 'precio', 'tiene_medidor', 'precio_por_unidad_de_medida')
            ->get()
            ->toArray();
    }

    public function addServicio()
    {
        if (!$this->selectedServicioId || !$this->selectedMiembro) {
            $this->currentStep = 2;
            session()->flash('error', 'Selecciona un servicio');
            return;
        }

        $servicio = Servicio::findOrFail($this->selectedServicioId);

        if ($servicio->tiene_medidor) {
            $this->servicioEnProceso = $servicio->id;
            $this->medidorActual = Medidores::where('miembro_id', $this->selectedMiembro->id)
                ->where('servicio_id', $servicio->id)
                ->first();

            if ($this->medidorActual) {
                $ultimaLectura = LecturaMedidores::where('medidor_id', $this->medidorActual->id)
                    ->orderBy('fecha_lectura', 'desc')
                    ->first();

                $this->lecturaAnterior = $ultimaLectura ? $ultimaLectura->lectura_actual : 0;
            }

            $this->currentStep = 2;
            $this->showModalMedidor = true;
            return;
        }

        $monto = $servicio->precio;

        $this->agregadosServicios[] = [
            'id' => uniqid(),
            'servicio_id' => $servicio->id,
            'nombre' => $servicio->nombre,
            'monto' => (float) $monto,
            'tiene_medidor' => $servicio->tiene_medidor,
            'consumo' => null,
        ];

        $this->selectedServicioId = null;
        $this->showServiciosAñadidos = true;
        $this->currentStep = 2;

        session()->flash('success', 'Servicio agregado correctamente');
    }

    public function updatedLecturaActual()
    {
        if ($this->lecturaActual !== null && $this->lecturaAnterior !== null) {
            $this->consumoCalculado = $this->lecturaActual - $this->lecturaAnterior;
        }
    }

    public function guardarLecturaMedidor()
    {
        if (!$this->lecturaActual || !$this->medidorActual) {
            $this->currentStep = 2;
            session()->flash('error', 'Ingresa la lectura actual');
            return;
        }

        $servicio = Servicio::findOrFail($this->servicioEnProceso);
        $consumo = $this->lecturaActual - ($this->lecturaAnterior ?? 0);
        $monto = $consumo * (float) $servicio->precio_por_unidad_de_medida;

        LecturaMedidores::create([
            'medidor_id' => $this->medidorActual->id,
            'fecha_lectura' => now()->toDateString(),
            'lectura_anterior' => $this->lecturaAnterior ?? 0,
            'lectura_actual' => $this->lecturaActual,
            'consumo' => $consumo,
        ]);

        $this->agregadosServicios[] = [
            'id' => uniqid(),
            'servicio_id' => $servicio->id,
            'nombre' => $servicio->nombre,
            'monto' => (float) $monto,
            'tiene_medidor' => true,
            'consumo' => $consumo,
        ];

        $this->showModalMedidor = false;
        $this->servicioEnProceso = null;
        $this->lecturaAnterior = null;
        $this->lecturaActual = null;
        $this->consumoCalculado = null;
        $this->medidorActual = null;
        $this->selectedServicioId = null;
        $this->showServiciosAñadidos = true;
        $this->currentStep = 2;

        session()->flash('success', 'Servicio agregado correctamente (con lectura de medidor)');
    }

    public function cancelarLecturaMedidor()
    {
        $this->showModalMedidor = false;
        $this->servicioEnProceso = null;
        $this->lecturaAnterior = null;
        $this->lecturaActual = null;
        $this->consumoCalculado = null;
        $this->medidorActual = null;
        $this->currentStep = 2;
    }

    public function removeServicio($id)
    {
        $this->agregadosServicios = array_values(array_filter(
            $this->agregadosServicios,
            fn($s) => $s['id'] !== $id
        ));

        if (count($this->agregadosServicios) === 0) {
            $this->showServiciosAñadidos = false;
        }

        $this->currentStep = 2;
    }

    public function getTotal()
    {
        return array_reduce(
            $this->agregadosServicios,
            fn($carry, $servicio) => $carry + $servicio['monto'],
            0
        );
    }

    public function generarRecibo()
    {
        if (!$this->selectedMiembro || count($this->agregadosServicios) == 0) {
            $this->currentStep = 3;
            session()->flash('error', 'Completa todos los campos');
            return;
        }

        try {
            DB::beginTransaction();

            $orgId = session('tenant_organization_id');
            $total = $this->getTotal();

            $cobro = Cobro::create([
                'organization_id' => $orgId,
                'miembro_id' => $this->selectedMiembro->id,
                'fecha_cobro' => now()->toDateString(),
                'tipo_cobro' => 'normal',
                'total' => $total,
            ]);

            foreach ($this->agregadosServicios as $servicio) {
                DetalleCobro::create([
                    'cobro_id' => $cobro->id,
                    'servicio_id' => $servicio['servicio_id'],
                    'periodo' => now()->format('Y-m'),
                    'concepto' => $servicio['nombre'],
                    'monto' => $servicio['monto'],
                ]);
            }

            $correlativo = Recibo::where('anio', now()->year)->max('correlativo') ?? 0;
            $correlativo++;

            $recibo = Recibo::create([
                'pago_id' => null,
                'cobro_id' => $cobro->id,
                'correlativo' => $correlativo,
                'nombre' => 'REC-' . now()->year . '-' . str_pad($correlativo, 6, '0', STR_PAD_LEFT),
                'fecha_emision' => now()->toDateString(),
                'anio' => now()->year,
                'monto' => $total,
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            session()->flash('success', 'Recibo generado correctamente');
            return $this->redirect(route('recibo.show', $recibo->id), navigate: true);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error generando recibo: ' . $e->getMessage());
            $this->currentStep = 3;
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function limpiar()
    {
        $this->selectedMiembro = null;
        $this->selectedPersona = null;
        $this->agregadosServicios = [];
        $this->searchQuery = '';
        $this->showServiciosAñadidos = false;
        $this->currentStep = 1;
    }

    public function render()
    {
        return view('livewire.cobros.create-cobro', [
            'servicios' => $this->servicios,
            'total' => $this->getTotal(),
        ]);
    }
}