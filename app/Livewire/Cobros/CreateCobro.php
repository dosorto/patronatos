<?php

namespace App\Livewire\Cobros;

use App\Models\Aportacion;
use App\Models\Cobro;
use App\Models\Cooperante;
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
    // Tipo de movimiento
    public string $tipoMovimiento = 'cobro'; // 'cobro' o 'donacion'

    // Búsqueda de miembro (para cobro)
    public string $searchQuery = '';
    public array $searchResults = [];
    public ?Miembros $selectedMiembro = null;
    public ?Persona $selectedPersona = null;

    // Cooperante (para donación)
    public array $cooperantesDisponibles = [];
    public ?int $cooperanteSeleccionado = null;

    // Suscripciones disponibles (cargadas al seleccionar miembro)
    public array $suscripciones = [];
    public ?int $selectedSuscripcionId = null;
    public int $cantidadMeses = 1;

    // Items acumulados en el cobro
    public array $agregadosServicios = [];

    // Modal medidor
    public bool $showModalMedidor = false;
    public ?int $servicioEnProceso = null;
    public ?int $suscripcionEnProceso = null;
    public ?float $lecturaAnterior = null;
    public ?float $lecturaActual = null;
    public ?float $consumoCalculado = null;
    public ?Medidores $medidorActual = null;

    // Aportaciones pendientes del miembro
    public array $aportacionesPendientes = [];
    public ?int $aportacionSeleccionadaId = null;
    public ?float $montoAportacion = null;

    // UI
    public bool $showSearchResults = false;
    public string $tipoCobroActual = 'servicios'; // 'servicios' | 'aportaciones' | 'otro_pago'

    // Otro Pago
    public string $conceptoOtroPago = '';
    public float $montoOtroPago = 0;

    // Donación
    public string $conceptoDonacion = '';
    public float $montoDonacion = 0;

    // Modal Cooperante
    public bool $showModalCooperante = false;
    public string $nombreCoop = '';
    public string $tipoCoop = '';
    public string $telCoop = '';
    public string $dirCoop = '';

    // Modal Ajuste (Cargo Extra / Descuento)
    public bool $showModalAjuste = false;
    public ?string $ajusteItemId = null;
    public float $montoAjuste = 0;
    public string $tipoAjuste = 'adicional'; // 'adicional' | 'descuento'

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
                    'dni' => $miembro->persona->dni,
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

        $this->suscripciones = \App\Models\Suscripcion::with('servicio', 'medidor')
            ->where('miembro_id', $this->selectedMiembro->id)
            ->where('estado', true)
            ->get()
            ->map(function ($s) {
                $ultimoMesPagado = $s->ultimo_mes_pagado ? \Carbon\Carbon::parse($s->ultimo_mes_pagado) : \Carbon\Carbon::now()->startOfMonth();
                $mesActual = \Carbon\Carbon::now()->startOfMonth();
                $pendientes = (int) max(0, floor($ultimoMesPagado->diffInMonths($mesActual, false)));
                if ($ultimoMesPagado->greaterThanOrEqualTo($mesActual)) {
                     $pendientes = 0;
                }
                
                return [
                    'id'               => $s->id,
                    'servicio_id'      => $s->servicio_id,
                    'nombre'           => $s->servicio->nombre . ($s->identificador ? " ({$s->identificador})" : ''),
                    'precio'           => $s->servicio->precio,
                    'tiene_medidor'    => $s->servicio->tiene_medidor,
                    'medidor_id'       => $s->medidor_id,
                    'numero_medidor'   => $s->medidor?->numero_medidor,
                    'identificador'    => $s->identificador,
                    'meses_pendientes' => $pendientes,
                    'ultimo_mes'       => $s->ultimo_mes_pagado ? $ultimoMesPagado->format('m/Y') : 'N/A'
                ];
            })
            ->toArray();
    }

    public function selectCooperante($cooperanteId)
    {
        $this->cooperanteSeleccionado = $cooperanteId;
    }

    // ─── Cargar cooperantes ──────────────────────────────────────────────────────

    public function mount()
    {
        $orgId = session('tenant_organization_id');
        $this->cooperantesDisponibles = Cooperante::where('organization_id', $orgId)
            ->select('id_cooperante', 'nombre', 'tipo_cooperante')
            ->get()
            ->map(function ($c) {
                return [
                    'id'     => $c->id_cooperante,
                    'nombre' => $c->nombre . ' (' . $c->tipo_cooperante . ')',
                ];
            })
            ->toArray();
    }

    // ─── Cambiar pestaña ──────────────────────────────────────────────────────────

    public function updatedSelectedSuscripcionId($value)
    {
        if ($value) {
            $suscripcion = collect($this->suscripciones)->firstWhere('id', $value);
            if ($suscripcion) {
                // Si debe meses, preseleccionamos esa cantidad. Si está al día, por defecto 1 para adelantar.
                $this->cantidadMeses = max(1, $suscripcion['meses_pendientes']);
            }
        }
    }

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

        $yaAgregadosProyectoIds = collect($this->agregadosServicios)
            ->where('tipo', 'aportacion')
            ->where('aportacion_id', null)
            ->pluck('proyecto_id')
            ->filter()
            ->toArray();

        // 1. Aportaciones ya existentes (deudas individuales)
        $aportesExistentes = Aportacion::with('proyecto')
            ->where('miembro_id', $this->selectedMiembro->id)
            ->whereIn('estado', ['pendiente', 'parcial'])
            ->whereNotIn('id', $yaAgregados)
            ->get();

        $pendientes = [];

        foreach ($aportesExistentes as $a) {
            $pendientes[] = [
                'id'              => $a->id,
                'proyecto_id'     => $a->proyecto_id,
                'proyecto_nombre' => $a->proyecto->nombre_proyecto ?? 'Sin proyecto',
                'monto'           => (float) ($a->monto_asignado - $a->monto_pagado),
                'fecha'           => $a->fecha_aportacion ? $a->fecha_aportacion->format('d/m/Y') : null,
                'es_nuevo'        => false
            ];
        }

        // 2. Proyectos con aportes configurados que el miembro NO tiene asignados aún
        $proyectoIdsConAporteMiembro = Aportacion::where('miembro_id', $this->selectedMiembro->id)
            ->pluck('proyecto_id')
            ->toArray();

        $proyectosConConfig = \App\Models\Proyecto::whereHas('configuracionAportacion')
            ->with('configuracionAportacion')
            ->whereNotIn('id', $proyectoIdsConAporteMiembro)
            ->whereNotIn('id', $yaAgregadosProyectoIds)
            ->get();

        foreach ($proyectosConConfig as $p) {
            // Calcular monto para este miembro (asumiendo equitativa por defecto si no hay registro)
            $monto = 0;
            if ($p->configuracionAportacion->tipo_distribucion === 'equitativa') {
            $totalMiembros = \App\Models\Miembros::activos()->count();
                $monto = $totalMiembros > 0 ? ($p->configuracionAportacion->monto_total_requerido / $totalMiembros) : 0;
            } else {
                // Si es manual y no tiene registro, tal vez no debería pagar,
                // pero lo mostraremos con monto 0 para que el cajero defina o lo ignore.
                $monto = 0;
            }

            $pendientes[] = [
                'id'              => null, // No tiene ID todavía
                'proyecto_id'     => $p->id,
                'proyecto_nombre' => "[PROYECTO] " . $p->nombre_proyecto,
                'monto'           => (float) $monto,
                'fecha'           => null,
                'es_nuevo'        => true
            ];
        }

        $this->aportacionesPendientes = $pendientes;
        $this->aportacionSeleccionadaId = null;
        $this->montoAportacion = null;
    }

    public function updatedAportacionSeleccionadaId($value)
    {
        if ($value !== null && $value !== '') {
            // Buscar por id (si tiene) o por el índice si es nuevo? 
            // Para simplificar, buscaremos en la colección que acabamos de cargar.
            // Dado que Livewire serializa los arrays, el ID null puede ser un problema para firstWhere.
            // Usaremos una búsqueda manual.
            $aportacion = null;
            // Si el valor es numérico pero no hay ID, Livewire podría estar pasando un índice o valor especial.
            // Sin embargo, en el blade usaremos 'new-PROYECTOID' para los nuevos.
            
            if (str_starts_with($value, 'new-')) {
                $pid = substr($value, 4);
                $aportacion = collect($this->aportacionesPendientes)->first(fn($a) => $a['es_nuevo'] && $a['proyecto_id'] == $pid);
            } else {
                $aportacion = collect($this->aportacionesPendientes)->firstWhere('id', $value);
            }

            if ($aportacion) {
                $this->montoAportacion = $aportacion['monto'];
            }
        } else {
            $this->montoAportacion = null;
        }
    }

    public function addAportacion()
    {
        if (!$this->aportacionSeleccionadaId) {
            session()->flash('error', 'Selecciona una aportación');
            return;
        }

        $aportacion = null;
        if (str_starts_with($this->aportacionSeleccionadaId, 'new-')) {
            $pid = substr($this->aportacionSeleccionadaId, 4);
            $aportacion = collect($this->aportacionesPendientes)->first(fn($a) => $a['es_nuevo'] && $a['proyecto_id'] == $pid);
        } else {
            $aportacion = collect($this->aportacionesPendientes)->firstWhere('id', $this->aportacionSeleccionadaId);
        }

        if (!$aportacion) {
            session()->flash('error', 'Aportación no encontrada');
            return;
        }

        if (!$this->montoAportacion || $this->montoAportacion < 0) {
            session()->flash('error', 'Monto inválido para la aportación.');
            return;
        }

        $this->agregadosServicios[] = [
            'id'            => uniqid(),
            'tipo'          => 'aportacion',
            'servicio_id'   => null,
            'aportacion_id' => $aportacion['id'], // Puede ser null
            'proyecto_id'   => $aportacion['proyecto_id'],
            'nombre'        => $aportacion['proyecto_nombre'],
            'monto'         => (float) $this->montoAportacion,
            'tiene_medidor' => false,
            'consumo'       => null,
        ];

        $this->cargarAportacionesPendientes();
        session()->flash('success', 'Aportación agregada');
    }

    // ─── Servicios ────────────────────────────────────────────────────────────────

    public function addServicio()
    {
        if (!$this->selectedSuscripcionId || !$this->selectedMiembro) {
            session()->flash('error', 'Selecciona una suscripción');
            return;
        }

        if ($this->cantidadMeses < 1) {
            session()->flash('error', 'Debes pagar al menos 1 mes');
            return;
        }

        $suscripcionInfo = collect($this->suscripciones)->firstWhere('id', $this->selectedSuscripcionId);
        
        if (!$suscripcionInfo) {
            session()->flash('error', 'Suscripción no válida');
            return;
        }

        if ($suscripcionInfo['tiene_medidor']) {
            $this->servicioEnProceso = $suscripcionInfo['servicio_id'];
            $this->suscripcionEnProceso = $suscripcionInfo['id'];
            
            if (!$suscripcionInfo['medidor_id']) {
                session()->flash('error', 'Este servicio requiere un medidor pero esta suscripción no tiene uno vinculado.');
                return;
            }

            $medidor = Medidores::find($suscripcionInfo['medidor_id']);
                                
            if (!$medidor) {
                session()->flash('error', 'El medidor vinculado a esta suscripción ya no existe.');
                return;
            }
            
            $this->medidorActual = $medidor;
            
            $ultimaLectura = LecturaMedidores::where('medidor_id', $medidor->id)
                                             ->latest('fecha_lectura')
                                             ->first();
                                             
            $this->lecturaAnterior = $ultimaLectura ? $ultimaLectura->lectura_actual : 0;
            $this->lecturaActual = null;
            $this->consumoCalculado = null;
            $this->showModalMedidor = true;
            return;
        }

        $montoTotal = $suscripcionInfo['precio'] * $this->cantidadMeses;

        $this->agregadosServicios[] = [
            'id'             => uniqid(),
            'tipo'           => 'suscripcion',
            'suscripcion_id' => $suscripcionInfo['id'],
            'servicio_id'    => $suscripcionInfo['servicio_id'],
            'aportacion_id'  => null,
            'nombre'         => $suscripcionInfo['nombre'] . ' (' . $this->cantidadMeses . ' ' . ($this->cantidadMeses == 1 ? 'mes' : 'meses') . ')',
            'identificador'  => $suscripcionInfo['identificador'],
            'numero_medidor' => null,
            'cantidad_meses' => $this->cantidadMeses,
            'monto'          => (float) $montoTotal,
            'tiene_medidor'  => false,
            'consumo'        => null,
        ];

        $this->selectedSuscripcionId = null;
        $this->cantidadMeses = 1;
        session()->flash('success', 'Suscripción agregada correctamente');
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

        // Recuperar el identificador de la suscripción para incluirlo en el concepto
        $suscInfo = collect($this->suscripciones)->firstWhere('id', $this->suscripcionEnProceso);
        $idSusc = $suscInfo['identificador'] ?? null;
        $numMedidor = $this->medidorActual->numero_medidor ?? null;

        $this->agregadosServicios[] = [
            'id'             => uniqid(),
            'tipo'           => 'suscripcion',
            'suscripcion_id' => $this->suscripcionEnProceso,
            'servicio_id'    => $servicio->id,
            'aportacion_id'  => null,
            'nombre'         => $servicio->nombre . ' (Mes actual) (Lec. ' . number_format($this->lecturaActual, 2) . ')',
            'identificador'  => $idSusc,
            'numero_medidor' => $numMedidor,
            'cantidad_meses' => 1,
            'monto'          => (float) $monto,
            'tiene_medidor'  => true,
            'consumo'        => $consumo,
        ];

        $this->cancelarLecturaMedidor();

        session()->flash('success', 'Servicio agregado correctamente (con lectura de medidor)');
    }

    public function cancelarLecturaMedidor()
    {
        $this->showModalMedidor     = false;
        $this->servicioEnProceso    = null;
        $this->suscripcionEnProceso = null;
        $this->lecturaAnterior      = null;
        $this->lecturaActual        = null;
        $this->consumoCalculado     = null;
        $this->medidorActual        = null;
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

    // ─── Donación ────────────────────────────────────────────────────────────────

    public function addDonacion()
    {
        if (!$this->conceptoDonacion || $this->montoDonacion <= 0) {
            session()->flash('error', 'Ingresa concepto y monto válido');
            return;
        }

        if ($this->tipoMovimiento === 'donacion' && !$this->cooperanteSeleccionado) {
            session()->flash('error', 'Selecciona un cooperante');
            return;
        }

        $this->agregadosServicios[] = [
            'id'              => uniqid(),
            'tipo'            => 'donacion',
            'cooperante_id'   => $this->cooperanteSeleccionado,
            'nombre'          => $this->conceptoDonacion,
            'monto'           => (float) $this->montoDonacion,
            'consumo'         => null,
        ];

        $this->conceptoDonacion = '';
        $this->montoDonacion    = 0;
        session()->flash('success', 'Donación agregada');
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
        if ($this->tipoMovimiento === 'cobro') {
            $this->generarReciboCobro();
        } else {
            $this->generarReciboDonacioon();
        }
    }

    private function generarReciboCobro()
    {
        if (!$this->selectedMiembro || count($this->agregadosServicios) == 0) {
            session()->flash('error', 'Selecciona un miembro y agrega items');
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
                if ($item['tipo'] === 'aportacion') {
                    $aportacion = null;
                    if ($item['aportacion_id']) {
                        $aportacion = Aportacion::find($item['aportacion_id']);
                    } else {
                        // Crear registro nuevo on-the-fly si viene de un proyecto
                        $aportacion = Aportacion::create([
                            'miembro_id'     => $this->selectedMiembro->id,
                            'proyecto_id'    => $item['proyecto_id'],
                            'monto_asignado' => $item['monto'], // El monto actual se vuelve el asignado
                            'monto'          => $item['monto'],
                            'monto_pagado'   => 0,
                            'estado'         => 'pendiente',
                        ]);
                    }

                    if ($aportacion) {
                        $nuevoPagado = $aportacion->monto_pagado + $item['monto'];
                        $estado = 'pendiente';
                        if ($nuevoPagado >= $aportacion->monto_asignado) {
                            $estado = 'pagado';
                        } elseif ($nuevoPagado > 0) {
                            $estado = 'parcial';
                        }
                        
                        $aportacion->update([
                            'cobro_id'         => $cobro->id,
                            'monto_pagado'     => $nuevoPagado,
                            'estado'           => $estado,
                            'fecha_aportacion' => now()->toDateString()
                        ]);
                    }
                    
                    \App\Models\DetalleCobro::create([
                        'cobro_id'      => $cobro->id,
                        'servicio_id'   => null,
                        'id_cooperante' => null,
                        'periodo'       => now()->format('Y-m'),
                        'concepto'      => $item['nombre'],
                        'monto'         => $item['monto'],
                        'es_donacion'   => false,
                    ]);
                } elseif ($item['tipo'] === 'suscripcion') {
                    $suscripcion = \App\Models\Suscripcion::find($item['suscripcion_id']);
                    $ultimoMesPagado = $suscripcion->ultimo_mes_pagado ? clone $suscripcion->ultimo_mes_pagado : \Carbon\Carbon::now()->startOfMonth();

                    // Armar sufijo de identificacion: casa/lote y/o número de medidor
                    $sufijo = '';
                    if (!empty($item['identificador'])) {
                        $sufijo .= ' - ' . $item['identificador'];
                    }
                    if (!empty($item['numero_medidor'])) {
                        $sufijo .= ' [Med. ' . $item['numero_medidor'] . ']';
                    }
                    
                    for ($i = 0; $i < $item['cantidad_meses']; $i++) {
                        $mesAPagar = (clone $ultimoMesPagado)->addMonths($i + 1);
                        \App\Models\DetalleCobro::create([
                            'cobro_id'      => $cobro->id,
                            'servicio_id'   => $item['servicio_id'],
                            'id_cooperante' => null,
                            'periodo'       => $mesAPagar->format('Y-m'),
                            'concepto'      => $suscripcion->servicio->nombre . $sufijo . ' (' . $mesAPagar->format('m/Y') . ')',
                            'monto'         => $item['monto'] / $item['cantidad_meses'],
                            'es_donacion'   => false,
                        ]);
                    }

                    $nuevoUltimoMes = (clone $ultimoMesPagado)->addMonths($item['cantidad_meses']);
                    $suscripcion->update(['ultimo_mes_pagado' => $nuevoUltimoMes]);
                } else {
                    \App\Models\DetalleCobro::create([
                        'cobro_id'      => $cobro->id,
                        'servicio_id'   => $item['servicio_id'] ?? null,
                        'id_cooperante' => null,
                        'periodo'       => now()->format('Y-m'),
                        'concepto'      => $item['nombre'],
                        'monto'         => $item['monto'],
                        'es_donacion'   => ($item['tipo'] === 'donacion'),
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

            try {
                $moraService = new \App\Services\MoraService();
                $moraService->syncMember($this->selectedMiembro->id);
            } catch (\Exception $e) {
                \Log::error('Mora sync error tras cobrar: ' . $e->getMessage());
            }

            session()->flash('success', 'Recibo generado correctamente');
            return $this->redirect(route('recibo.show', $recibo->id), navigate: true);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error generando recibo: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    private function generarReciboDonacioon()
    {
        if (!$this->cooperanteSeleccionado || count($this->agregadosServicios) == 0) {
            session()->flash('error', 'Selecciona cooperante y agrega donaciones');
            return;
        }

        try {
            DB::beginTransaction();

            $orgId = session('tenant_organization_id');
            $total = $this->getTotal();

            // Crear COBRO sin miembro, solo con cooperante
            $cobro = Cobro::create([
                'organization_id' => $orgId,
                'miembro_id'      => null,
                'fecha_cobro'     => now()->toDateString(),
                'tipo_cobro'      => 'donacion',
                'total'           => $total,
            ]);

            foreach ($this->agregadosServicios as $item) {
                DetalleCobro::create([
                    'cobro_id'      => $cobro->id,
                    'servicio_id'   => null,
                    'id_cooperante' => $item['cooperante_id'],
                    'periodo'       => now()->format('Y-m'),
                    'concepto'      => $item['nombre'],
                    'monto'         => $item['monto'],
                    'es_donacion'   => true,
                ]);
            }

            $correlativo = Recibo::where('anio', now()->year)->max('correlativo') ?? 0;
            $correlativo++;

            $recibo = Recibo::create([
                'pago_id'       => null,
                'cobro_id'      => $cobro->id,
                'correlativo'   => $correlativo,
                'nombre'        => 'DON-' . now()->year . '-' . str_pad($correlativo, 6, '0', STR_PAD_LEFT),
                'fecha_emision' => now()->toDateString(),
                'anio'          => now()->year,
                'monto'         => $total,
                'user_id'       => auth()->id(),
            ]);

            DB::commit();

            session()->flash('success', 'Recibo de donación generado correctamente');
            return $this->redirect(route('recibo.show', $recibo->id), navigate: true);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error generando recibo donación: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    // ─── Limpiar ──────────────────────────────────────────────────────────────────

    public function limpiar()
    {
        $this->selectedMiembro          = null;
        $this->selectedPersona          = null;
        $this->cooperanteSeleccionado   = null;
        $this->agregadosServicios       = [];
        $this->searchQuery              = '';
        $this->showSearchResults        = false;
        $this->suscripciones            = [];
        $this->selectedSuscripcionId    = null;
        $this->cantidadMeses            = 1;
        $this->tipoCobroActual          = 'servicios';
        $this->aportacionesPendientes   = [];
        $this->aportacionSeleccionadaId = null;
        $this->conceptoOtroPago         = '';
        $this->montoOtroPago            = 0;
        $this->conceptoDonacion         = '';
        $this->montoDonacion            = 0;

        // Limpiar modal cooperante
        $this->cerrarModalCooperante();
    }

    // ─── Modal Cooperante ─────────────────────────────────────────────────────────

    public function abrirModalCooperante()
    {
        $this->showModalCooperante = true;
        $this->reset(['nombreCoop', 'tipoCoop', 'telCoop', 'dirCoop']);
    }

    public function cerrarModalCooperante()
    {
        $this->showModalCooperante = false;
        $this->reset(['nombreCoop', 'tipoCoop', 'telCoop', 'dirCoop']);
    }

    public function guardarCooperante()
    {
        $this->validate([
            'nombreCoop' => 'required|string|max:255',
            'tipoCoop'   => 'required|string|max:100',
            'telCoop'    => 'required|string|max:20',
            'dirCoop'    => 'required|string|max:255',
        ], [
            'nombreCoop.required' => 'El nombre es obligatorio',
            'tipoCoop.required'   => 'El tipo es obligatorio',
            'telCoop.required'    => 'El teléfono es obligatorio',
            'dirCoop.required'    => 'La dirección es obligatoria',
        ]);

        try {
            $orgId = session('tenant_organization_id');

            $cooperante = Cooperante::create([
                'organization_id' => $orgId,
                'nombre'          => $this->nombreCoop,
                'tipo_cooperante' => $this->tipoCoop,
                'telefono'        => $this->telCoop,
                'direccion'       => $this->dirCoop,
            ]);

            // Actualizar lista de disponibles
            $this->mount();
            
            // Seleccionar el nuevo cooperante
            $this->cooperanteSeleccionado = $cooperante->id_cooperante;

            error_log("Cooperante creado: " . $cooperante->id_cooperante);

            $this->cerrarModalCooperante();
            session()->flash('success', 'Cooperante registrado correctamente');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al registrar cooperante: ' . $e->getMessage());
        }
    }

    // ─── Modal Ajuste ─────────────────────────────────────────────────────────────

    public function abrirModalAjuste($itemId, $tipo)
    {
        $this->ajusteItemId = $itemId;
        $this->tipoAjuste = $tipo;
        $this->montoAjuste = 0;
        $this->showModalAjuste = true;
    }

    public function cerrarModalAjuste()
    {
        $this->showModalAjuste = false;
        $this->ajusteItemId = null;
        $this->montoAjuste = 0;
    }

    public function aplicarAjuste()
    {
        $this->validate([
            'montoAjuste' => 'required|numeric|min:0.01'
        ], [
            'montoAjuste.required' => 'El monto es obligatorio',
            'montoAjuste.numeric' => 'El monto debe ser numérico',
            'montoAjuste.min' => 'El monto debe ser mayor a 0'
        ]);

        $key = array_search($this->ajusteItemId, array_column($this->agregadosServicios, 'id'));

        if ($key !== false) {
            if ($this->tipoAjuste === 'adicional') {
                $this->agregadosServicios[$key]['monto'] += (float) $this->montoAjuste;
                session()->flash('success', 'Importe adicional aplicado correctamente');
            } else {
                // El ajuste de descuento ahora es porcentual
                $montoOriginalRow = $this->agregadosServicios[$key]['monto'];
                $descuentoCalculado = ($montoOriginalRow * ($this->montoAjuste / 100));

                if ($descuentoCalculado > $montoOriginalRow) {
                    session()->flash('error', 'El descuento (' . number_format($this->montoAjuste, 1) . '%) supera el monto actual.');
                    return;
                }

                $this->agregadosServicios[$key]['monto'] -= (float) $descuentoCalculado;
                session()->flash('success', 'Descuento del ' . $this->montoAjuste . '% aplicado correctamente');
            }
        }

        $this->cerrarModalAjuste();
    }

    // ─── Render ───────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.cobros.create-cobro', [
            'suscripciones'          => $this->suscripciones,
            'cooperantesDisponibles' => $this->cooperantesDisponibles,
            'total'                  => $this->getTotal(),
        ]);
    }
}