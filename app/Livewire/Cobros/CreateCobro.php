<?php

namespace App\Livewire\Cobros;

use App\Models\Aportacion;
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
    // Búsqueda de miembro
    public string $searchQuery = '';
    public array $searchResults = [];
    public ?Miembros $selectedMiembro = null;
    public ?Persona $selectedPersona = null;

    // Servicios disponibles (cargados al seleccionar miembro)
    public array $servicios = [];
    public ?int $selectedServicioId = null;

    // Items acumulados en el cobro
    public array $agregadosServicios = [];

    // Modal medidor
    public bool $showModalMedidor = false;
    public ?int $servicioEnProceso = null;
    public ?float $lecturaAnterior = null;
    public ?float $lecturaActual = null;
    public ?float $consumoCalculado = null;
    public ?Medidores $medidorActual = null;

    // Aportaciones pendientes del miembro
    public array $aportacionesPendientes = [];
    public ?int $aportacionSeleccionadaId = null;

    // UI
    public bool $showSearchResults = false;
    public string $tipoCobroActual = 'servicios'; // 'servicios' | 'aportaciones' | 'otro_pago'

    // Otro Pago
    public string $conceptoOtroPago = '';
    public float $montoOtroPago = 0;

    // ─── Búsqueda ─────────────────────────────────────────────────────────────────

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->searchResults     = [];
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
                    'id'        => $miembro->id,
                    'dni'       => $miembro->persona->dni,
                    'nombre'    => $miembro->persona->nombre . ' ' . $miembro->persona->apellido,
                    'direccion' => $miembro->direccion,
                ];
            })
            ->toArray();

        $this->showSearchResults = count($this->searchResults) > 0;
    }

    public function selectMiembro($miembroId)
    {
        $this->selectedMiembro   = Miembros::with('persona')->findOrFail($miembroId);
        $this->selectedPersona   = $this->selectedMiembro->persona;
        $this->searchQuery       = '';
        $this->searchResults     = [];
        $this->showSearchResults = false;
        $this->agregadosServicios = [];
        $this->tipoCobroActual   = 'servicios';

        $orgId = session('tenant_organization_id');

        $this->servicios = Servicio::where('organization_id', $orgId)
            ->where('estado', 1)
            ->select('id', 'nombre', 'precio', 'tiene_medidor', 'precio_por_unidad_de_medida')
            ->get()
            ->toArray();
    }

    // ─── Cambiar pestaña ──────────────────────────────────────────────────────────

    public function cambiarTipoCobro($tipo)
    {
        $this->tipoCobroActual = $tipo;

        if ($tipo === 'aportaciones' && $this->selectedMiembro) {
            $this->cargarAportacionesPendientes();
        }
    }

    // ─── Aportaciones ─────────────────────────────────────────────────────────────

    private function cargarAportacionesPendientes()
    {
        $yaAgregados = collect($this->agregadosServicios)
            ->where('tipo', 'aportacion')
            ->pluck('aportacion_id')
            ->filter()
            ->toArray();

        $this->aportacionesPendientes = Aportacion::with('proyecto')
            ->where('id_miembro', $this->selectedMiembro->id)
            ->where('estado', true)
            ->whereNull('id_cobro')
            ->whereNotIn('id_aportacion', $yaAgregados)
            ->get()
            ->map(function ($a) {
                return [
                    'id'              => $a->id_aportacion,
                    'proyecto_nombre' => $a->proyecto->nombre_proyecto ?? 'Sin proyecto',
                    'monto'           => (float) $a->monto,
                    'fecha'           => $a->fecha_aportacion
                        ? \Carbon\Carbon::parse($a->fecha_aportacion)->format('d/m/Y')
                        : null,
                ];
            })
            ->toArray();

        $this->aportacionSeleccionadaId = null;
    }

    public function addAportacion()
    {
        if (!$this->aportacionSeleccionadaId) {
            session()->flash('error', 'Selecciona una aportación');
            return;
        }

        $aportacion = collect($this->aportacionesPendientes)
            ->firstWhere('id', $this->aportacionSeleccionadaId);

        if (!$aportacion) {
            session()->flash('error', 'Aportación no encontrada');
            return;
        }

        $this->agregadosServicios[] = [
            'id'            => uniqid(),
            'tipo'          => 'aportacion',
            'servicio_id'   => null,
            'aportacion_id' => $aportacion['id'],
            'nombre'        => 'Aportación: ' . $aportacion['proyecto_nombre'],
            'monto'         => $aportacion['monto'],
            'tiene_medidor' => false,
            'consumo'       => null,
        ];

        $this->cargarAportacionesPendientes();
        session()->flash('success', 'Aportación agregada');
    }

    // ─── Servicios ────────────────────────────────────────────────────────────────

    public function addServicio()
    {
        if (!$this->selectedServicioId || !$this->selectedMiembro) {
            session()->flash('error', 'Selecciona un servicio');
            return;
        }

        $servicio = Servicio::findOrFail($this->selectedServicioId);

        if ($servicio->tiene_medidor) {
            $this->servicioEnProceso = $servicio->id;
            $this->medidorActual     = Medidores::where('miembro_id', $this->selectedMiembro->id)
                ->where('servicio_id', $servicio->id)
                ->first();

            if ($this->medidorActual) {
                $ultimaLectura         = LecturaMedidores::where('medidor_id', $this->medidorActual->id)
                    ->orderBy('fecha_lectura', 'desc')
                    ->first();
                $this->lecturaAnterior = $ultimaLectura ? $ultimaLectura->lectura_actual : 0;
            }

            $this->showModalMedidor = true;
            return;
        }

        $this->agregadosServicios[] = [
            'id'            => uniqid(),
            'tipo'          => 'servicio',
            'servicio_id'   => $servicio->id,
            'aportacion_id' => null,
            'nombre'        => $servicio->nombre,
            'monto'         => (float) $servicio->precio,
            'tiene_medidor' => false,
            'consumo'       => null,
        ];

        $this->selectedServicioId = null;
        session()->flash('success', 'Servicio agregado correctamente');
    }

    // ─── Modal Medidor ────────────────────────────────────────────────────────────

    public function updatedLecturaActual()
    {
        if ($this->lecturaActual !== null && $this->lecturaAnterior !== null) {

            if ($this->lecturaActual < $this->lecturaAnterior) {
                $this->consumoCalculado = null;

                session()->flash('error', 'La lectura actual no puede ser menor que la anterior');
                return;
            }

            $this->consumoCalculado = $this->lecturaActual - $this->lecturaAnterior;
        }
    }

    public function guardarLecturaMedidor()
    {
        if (!$this->lecturaActual || !$this->medidorActual) {
            session()->flash('error', 'Ingresa la lectura actual');
            return;
        }

        // 🚨 VALIDACIÓN CLAVE
        if ($this->lecturaActual < ($this->lecturaAnterior ?? 0)) {
            session()->flash('error', 'La lectura actual no puede ser menor que la anterior');
            return;
        }

        $servicio = Servicio::findOrFail($this->servicioEnProceso);

        $consumo = $this->lecturaActual - ($this->lecturaAnterior ?? 0);

        $monto = $consumo * (float) $servicio->precio_por_unidad_de_medida;

        LecturaMedidores::create([
            'medidor_id'       => $this->medidorActual->id,
            'fecha_lectura'    => now()->toDateString(),
            'lectura_anterior' => $this->lecturaAnterior ?? 0,
            'lectura_actual'   => $this->lecturaActual,
            'consumo'          => $consumo,
        ]);

        $this->agregadosServicios[] = [
            'id'            => uniqid(),
            'tipo'          => 'servicio',
            'servicio_id'   => $servicio->id,
            'aportacion_id' => null,
            'nombre'        => $servicio->nombre,
            'monto'         => (float) $monto,
            'tiene_medidor' => true,
            'consumo'       => $consumo,
        ];

        $this->cancelarLecturaMedidor();

        session()->flash('success', 'Servicio agregado correctamente (con lectura de medidor)');
    }

    public function cancelarLecturaMedidor()
    {
        $this->showModalMedidor   = false;
        $this->servicioEnProceso  = null;
        $this->lecturaAnterior    = null;
        $this->lecturaActual      = null;
        $this->consumoCalculado   = null;
        $this->medidorActual      = null;
    }

    // ─── Otro Pago ────────────────────────────────────────────────────────────────

    public function addOtroPago()
    {
        if (!$this->conceptoOtroPago || $this->montoOtroPago <= 0) {
            session()->flash('error', 'Ingresa concepto y monto válido');
            return;
        }

        $this->agregadosServicios[] = [
            'id'            => uniqid(),
            'tipo'          => 'otro_pago',
            'servicio_id'   => null,
            'aportacion_id' => null,
            'nombre'        => $this->conceptoOtroPago,
            'monto'         => (float) $this->montoOtroPago,
            'tiene_medidor' => false,
            'consumo'       => null,
        ];

        $this->conceptoOtroPago = '';
        $this->montoOtroPago    = 0;
        session()->flash('success', 'Otro pago agregado');
    }

    // ─── Eliminar ítem ────────────────────────────────────────────────────────────

    public function removeItem($id)
    {
        $this->agregadosServicios = array_values(
            array_filter($this->agregadosServicios, fn($s) => $s['id'] !== $id)
        );

        if ($this->tipoCobroActual === 'aportaciones' && $this->selectedMiembro) {
            $this->cargarAportacionesPendientes();
        }
    }

    // ─── Total ────────────────────────────────────────────────────────────────────

    public function getTotal()
    {
        return array_reduce(
            $this->agregadosServicios,
            fn($carry, $s) => $carry + $s['monto'],
            0
        );
    }

    // ─── Generar Recibo ───────────────────────────────────────────────────────────

    public function generarRecibo()
    {
        if (!$this->selectedMiembro || count($this->agregadosServicios) == 0) {
            session()->flash('error', 'Completa todos los campos');
            return;
        }

        try {
            DB::beginTransaction();

            $orgId = session('tenant_organization_id');
            $total = $this->getTotal();

            $cobro = Cobro::create([
                'organization_id' => $orgId,
                'miembro_id'      => $this->selectedMiembro->id,
                'fecha_cobro'     => now()->toDateString(),
                'tipo_cobro'      => 'normal',
                'total'           => $total,
            ]);

            foreach ($this->agregadosServicios as $item) {
                DetalleCobro::create([
                    'cobro_id'      => $cobro->id,
                    'servicio_id'   => $item['servicio_id'],
                    'aportacion_id' => $item['aportacion_id'],
                    'periodo'       => now()->format('Y-m'),
                    'concepto'      => $item['nombre'],
                    'monto'         => $item['monto'],
                ]);

                // Marcar aportación como cobrada
                if ($item['tipo'] === 'aportacion' && $item['aportacion_id']) {
                    Aportacion::where('id_aportacion', $item['aportacion_id'])
                        ->update([
                            'id_cobro' => $cobro->id,
                        ]);
                }
            }

            $correlativo = Recibo::where('anio', now()->year)->max('correlativo') ?? 0;
            $correlativo++;

            $recibo = Recibo::create([
                'pago_id'       => null,
                'cobro_id'      => $cobro->id,
                'correlativo'   => $correlativo,
                'nombre'        => 'REC-' . now()->year . '-' . str_pad($correlativo, 6, '0', STR_PAD_LEFT),
                'fecha_emision' => now()->toDateString(),
                'anio'          => now()->year,
                'monto'         => $total,
                'user_id'       => auth()->id(),
            ]);

            DB::commit();

            session()->flash('success', 'Recibo generado correctamente');
            return $this->redirect(route('recibo.show', $recibo->id), navigate: true);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error generando recibo: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }
    // ─── Limpiar ──────────────────────────────────────────────────────────────────

    public function limpiar()
    {
        $this->selectedMiembro          = null;
        $this->selectedPersona          = null;
        $this->agregadosServicios       = [];
        $this->searchQuery              = '';
        $this->showSearchResults        = false;
        $this->selectedServicioId       = null;
        $this->tipoCobroActual          = 'servicios';
        $this->aportacionesPendientes   = [];
        $this->aportacionSeleccionadaId = null;
        $this->conceptoOtroPago         = '';
        $this->montoOtroPago            = 0;
    }

    // ─── Render ───────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.cobros.create-cobro', [
            'servicios' => $this->servicios,
            'total'     => $this->getTotal(),
        ]);
    }
}