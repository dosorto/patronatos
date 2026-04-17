<div class="min-h-screen bg-background dark:bg-slate-900">
    <main class="overflow-auto">

        {{-- Top Bar --}}
        <header class="bg-white dark:bg-slate-950 border-b border-outline-variant/10 px-8 py-4 sticky top-0 z-40 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-on-surface dark:text-white">Nuevo Cobro</h2>
                    <p class="text-sm text-on-surface-variant dark:text-slate-400">Terminal de Facturación Directa</p>
                </div>
                <span class="px-3 py-1 bg-primary-container text-on-primary-container rounded-full text-xs font-bold uppercase">Sesión Activa</span>
            </div>
        </header>

        {{-- Mensajes --}}
        <div class="px-8 py-4">
            @if(session()->has('success'))
                <div class="mb-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-green-800 dark:text-green-200 font-medium text-sm">{{ session('success') }}</p>
                </div>
            @endif
            @if(session()->has('error'))
                <div class="mb-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <p class="text-red-800 dark:text-red-200 font-medium text-sm">{{ session('error') }}</p>
                </div>
            @endif
        </div>

        <div class="px-8 pb-8 space-y-6">

            {{-- ── SELECTOR TIPO DE MOVIMIENTO ──────────────────────────────────── --}}
            <div class="bg-surface-container-lowest dark:bg-slate-900 p-6 rounded-xl border border-outline-variant/10">
                <p class="text-xs font-bold uppercase tracking-widest text-outline mb-4">Tipo de Movimiento</p>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" wire:model.live="tipoMovimiento" value="cobro" class="sr-only">
                        <div class="p-4 rounded-xl border-2 transition-all flex items-center gap-3
                            {{ $tipoMovimiento === 'cobro' ? 'border-primary bg-primary-container/20 dark:bg-primary/10' : 'border-outline-variant/30 hover:border-primary/40' }}">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                                {{ $tipoMovimiento === 'cobro' ? 'bg-primary text-on-primary' : 'bg-surface-container dark:bg-slate-700 text-on-surface-variant' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface dark:text-white text-sm">Cobro a Miembro</p>
                                <p class="text-xs text-on-surface-variant">Servicios, aportaciones y pagos</p>
                            </div>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" wire:model.live="tipoMovimiento" value="donacion" class="sr-only">
                        <div class="p-4 rounded-xl border-2 transition-all flex items-center gap-3
                            {{ $tipoMovimiento === 'donacion' ? 'border-amber-500 bg-amber-500/10' : 'border-outline-variant/30 hover:border-amber-400/40' }}">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                                {{ $tipoMovimiento === 'donacion' ? 'bg-amber-500 text-white' : 'bg-surface-container dark:bg-slate-700 text-on-surface-variant' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface dark:text-white text-sm">Donación de Cooperante</p>
                                <p class="text-xs text-on-surface-variant">Registrar donación externa</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════ --}}
            {{-- FLUJO: COBRO A MIEMBRO                                           --}}
            {{-- ══════════════════════════════════════════════════════════════════ --}}
            @if($tipoMovimiento === 'cobro')

            {{-- ── BÚSQUEDA / INFO MIEMBRO ──────────────────────────────────────── --}}
            <div class="bg-surface-container-lowest dark:bg-slate-900 p-8 rounded-xl border border-outline-variant/10">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-outline">Buscar Cliente</h3>
                </div>

                @if(!$selectedMiembro)
                    <label class="block text-xs font-medium text-on-surface-variant mb-2">DNI o Nombre</label>
                    <div class="relative mb-4">
                        <input
                            wire:model.live="searchQuery"
                            type="text"
                            placeholder="Ingresa DNI, nombre o apellido..."
                            class="w-full px-4 py-3 bg-surface-container border border-outline-variant/30 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white"
                        >
                        <button
                            type="button"
                            wire:click="$refresh"
                            class="absolute right-3 top-1/2 -translate-y-1/2 px-3 py-1 bg-primary hover:bg-primary-dim text-white rounded-lg text-sm font-bold transition-all"
                        >
                            Buscar
                        </button>
                    </div>

                    @if($showSearchResults && count($searchResults) > 0)
                    <div class="bg-white dark:bg-slate-800 border border-outline-variant/20 dark:border-slate-700 rounded-lg shadow-lg overflow-hidden">
                        <div class="max-h-64 overflow-y-auto">
                            @foreach($searchResults as $result)
                            <button
                                wire:click="selectMiembro({{ $result['id'] }})"
                                type="button"
                                class="w-full text-left px-6 py-4 hover:bg-primary-container/10 dark:hover:bg-slate-700 border-b border-outline-variant/10 dark:border-slate-700 last:border-b-0 transition-colors"
                            >
                                <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $result['nombre'] }}</p>
                                <p class="text-xs text-gray-600 dark:text-slate-300">DNI: {{ $result['dni'] }}</p>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @else
                    <div class="grid grid-cols-12 gap-6">
                        {{-- Info --}}
                        <div class="col-span-12 lg:col-span-7">
                            <div class="p-6 bg-primary-container/10 border border-primary/30 rounded-lg space-y-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-outline mb-1">Nombre Completo</p>
                                    <p class="font-bold text-lg text-on-surface dark:text-white">{{ $selectedPersona->nombre }} {{ $selectedPersona->apellido }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-outline mb-1">DNI</p>
                                        <p class="font-medium text-on-surface dark:text-white">{{ $selectedPersona->dni }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-outline mb-1">Dirección</p>
                                        <p class="font-medium text-on-surface dark:text-white">{{ $selectedMiembro->direccion ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <button
                                    wire:click="limpiar"
                                    type="button"
                                    class="w-full px-4 py-3 bg-gradient-to-r from-primary to-primary-dim text-on-primary font-bold rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 active:scale-95 flex items-center justify-center gap-2 uppercase tracking-wider text-sm"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Cambiar Miembro
                                </button>
                            </div>
                        </div>

                        {{-- Resumen --}}
                        <div class="col-span-12 lg:col-span-5 bg-primary text-on-primary p-8 rounded-xl shadow-xl relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dim opacity-50"></div>
                            <div class="relative z-10 space-y-6">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-primary-fixed-dim">Resumen Total</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-primary-fixed-dim/80">
                                        <span class="text-xs uppercase font-bold">Items</span>
                                        <span class="font-bold">{{ count($agregadosServicios) }}</span>
                                    </div>
                                    <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                                        <span class="text-xs uppercase font-bold text-white">Total a Pagar</span>
                                        <span class="text-4xl font-bold">L. {{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    onclick="document.getElementById('confirmModal').classList.remove('hidden')"
                                    {{ count($agregadosServicios) == 0 ? 'disabled' : '' }}
                                    class="w-full bg-white text-primary py-3 rounded-lg font-bold text-sm uppercase tracking-wider shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 active:scale-95 flex items-center justify-center gap-2"
                                >
                                    @if(count($agregadosServicios) == 0)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Agrega items
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Generar Recibo
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── TABS TIPO DE COBRO ────────────────────────────────────────────── --}}
            @if($selectedMiembro)
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl p-4 border border-outline-variant/10">
                <div class="grid grid-cols-3 gap-3">
                    {{-- Servicios --}}
                    <button type="button" wire:click="cambiarTipoCobro('servicios')"
                        class="px-4 py-3 rounded-lg font-bold text-sm uppercase tracking-wider transition-all flex items-center justify-center gap-2
                            {{ $tipoCobroActual === 'servicios' ? 'bg-primary text-on-primary shadow-lg' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-highest dark:bg-slate-700 dark:text-slate-300' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Servicios
                    </button>

                    {{-- Aportaciones --}}
                    <button type="button" wire:click="cambiarTipoCobro('aportaciones')"
                        class="px-4 py-3 rounded-lg font-bold text-sm uppercase tracking-wider transition-all flex items-center justify-center gap-2
                            {{ $tipoCobroActual === 'aportaciones' ? 'bg-emerald-600 text-white shadow-lg' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-highest dark:bg-slate-700 dark:text-slate-300' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Aportaciones
                    </button>

                    {{-- Otro Pago --}}
                    <button type="button" wire:click="cambiarTipoCobro('otro_pago')"
                        class="px-4 py-3 rounded-lg font-bold text-sm uppercase tracking-wider transition-all flex items-center justify-center gap-2
                            {{ $tipoCobroActual === 'otro_pago' ? 'bg-amber-500 text-white shadow-lg' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-highest dark:bg-slate-700 dark:text-slate-300' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Otro Pago
                    </button>

                    {{-- Donación --}}
                    <button type="button" wire:click="cambiarTipoCobro('donacion')"
                        class="px-4 py-3 rounded-lg font-bold text-sm uppercase tracking-wider transition-all flex items-center justify-center gap-2
                            {{ $tipoCobroActual === 'donacion' ? 'bg-rose-500 text-white shadow-lg' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-highest dark:bg-slate-700 dark:text-slate-300' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Donación
                    </button>
                </div>
            </div>

            {{-- ── PANEL: SUSCRIPCIONES ──────────────────────────────────────────────── --}}
            @if($tipoCobroActual === 'servicios')
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl border border-outline-variant/10 overflow-hidden">
                <div class="p-5 border-b border-outline-variant/10 flex items-center gap-3">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <h3 class="font-bold text-on-surface dark:text-white">Abonar a Suscripciones del Cliente</h3>
                </div>
                <div class="p-6">
                    @if(count($suscripciones) === 0)
                        <div class="py-10 text-center">
                            <svg class="w-12 h-12 mx-auto text-outline-variant/30 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-bold text-on-surface dark:text-white mb-1">Sin suscripciones</p>
                            <p class="text-sm text-on-surface-variant">Este miembro no tiene servicios recurrentes asignados.</p>
                        </div>
                    @else
                        <div class="space-y-3 mb-5">
                            @foreach($suscripciones as $s)
                            <label
                                wire:click="$set('selectedSuscripcionId', {{ $s['id'] }})"
                                class="flex items-center justify-between p-4 rounded-lg border cursor-pointer transition-all
                                    {{ $selectedSuscripcionId == $s['id']
                                        ? 'border-primary bg-primary-container/10 dark:bg-primary/20'
                                        : 'border-outline-variant/30 hover:border-primary/50 hover:bg-surface-container-highest/20' }}"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $selectedSuscripcionId == $s['id']
                                            ? 'border-primary'
                                            : 'border-outline-variant/50' }}"
                                    >
                                        @if($selectedSuscripcionId == $s['id'])
                                        <div class="w-2.5 h-2.5 rounded-full bg-primary"></div>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="font-bold text-sm text-on-surface dark:text-white">{{ $s['nombre'] }}</p>
                                        <div class="text-xs mt-1 flex items-center gap-2">
                                            @if($s['meses_pendientes'] > 0)
                                                <span class="px-2 py-0.5 rounded text-red-700 bg-red-100 font-bold border border-red-200">
                                                    Debe {{ $s['meses_pendientes'] }} {{ $s['meses_pendientes'] == 1 ? 'mes' : 'meses' }}
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-emerald-700 bg-emerald-100 font-bold border border-emerald-200">
                                                    Al Día
                                                </span>
                                            @endif
                                            <span class="text-gray-500 dark:text-gray-300">Último pagado: {{ $s['ultimo_mes'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <span class="font-bold text-primary dark:text-primary-fixed-dim text-sm whitespace-nowrap ml-4">
                                    @if($s['tiene_medidor'])
                                        <span class="text-xs text-gray-400 italic">N/A</span>
                                    @else
                                        L. {{ number_format($s['precio'], 2) }} / mes
                                    @endif
                                </span>
                            </label>
                            @endforeach
                        </div>

                        @php
                            $selSuscrip = collect($suscripciones)->firstWhere('id', $selectedSuscripcionId);
                            $esMedido = $selSuscrip ? $selSuscrip['tiene_medidor'] : false;
                        @endphp
                        
                        @if($selectedSuscripcionId && !$esMedido)
                        <div class="mt-4 pt-4 border-t border-outline-variant/20">
                            <p class="text-xs font-bold text-on-surface-variant mb-3 uppercase tracking-wider">Selecciona los meses a pagar:</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                @foreach($mesesPendientesDisponibles as $mes)
                                <label class="cursor-pointer">
                                    <input type="checkbox" wire:model.live="mesesSeleccionados" value="{{ $mes['fecha'] }}" class="sr-only">
                                    <div class="px-3 py-2 rounded-lg border text-center transition-all
                                        {{ in_array($mes['fecha'], $mesesSeleccionados) 
                                            ? 'bg-primary text-on-primary border-primary shadow-sm' 
                                            : ($mes['vencido'] 
                                                ? 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/20 dark:text-red-300 dark:border-red-800' 
                                                : 'bg-surface-container text-on-surface-variant border-outline-variant/30 hover:border-primary/50 dark:bg-slate-700 dark:text-slate-300')
                                        }}">
                                        <p class="text-[10px] font-bold uppercase leading-tight">{{ $mes['label'] }}</p>
                                        @if($mes['vencido'])
                                            <span class="text-[8px] font-black uppercase">Vencido</span>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex flex-col sm:flex-row items-end gap-3 mt-4 pt-4 border-t border-outline-variant/20">
                            <button
                                wire:click="addServicio"
                                type="button"
                                {{ !$selectedSuscripcionId ? 'disabled' : '' }}
                                class="flex-1 w-full px-6 py-[10px] bg-primary text-on-primary rounded-lg font-bold uppercase tracking-widest hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2 border border-transparent"
                            >
                                @if($esMedido)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                    </svg>
                                    Ingresar Lectura
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Agregar a la Lista
                                @endif
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- ── PANEL: APORTACIONES ───────────────────────────────────────────── --}}
            @if($tipoCobroActual === 'aportaciones')
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl border border-outline-variant/10 overflow-hidden">
                <div class="p-5 border-b border-outline-variant/10 flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="font-bold text-on-surface dark:text-white">Aportaciones Pendientes</h3>
                    <span class="ml-auto text-xs text-on-surface-variant">
                        {{ $selectedPersona->nombre }} {{ $selectedPersona->apellido }}
                    </span>
                </div>

                <div class="p-6">
                    @if(count($aportacionesPendientes) === 0)
                        <div class="py-10 text-center">
                            <svg class="w-12 h-12 mx-auto text-outline-variant/30 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-bold text-on-surface dark:text-white mb-1">Sin aportaciones pendientes</p>
                            <p class="text-sm text-on-surface-variant">Este miembro no tiene aportaciones por cobrar</p>
                        </div>
                    @else
                        <div class="space-y-3 mb-5">
                            @foreach($aportacionesPendientes as $aportacion)
                            @php
                                $currentId = $aportacion['es_nuevo'] ? 'new-'.$aportacion['proyecto_id'] : $aportacion['id'];
                            @endphp
                            <label
                                wire:click="$set('aportacionSeleccionadaId', '{{ $currentId }}')"
                                class="flex items-center justify-between p-4 rounded-lg border cursor-pointer transition-all
                                    {{ $aportacionSeleccionadaId == $currentId
                                        ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20'
                                        : 'border-outline-variant/30 hover:border-emerald-300 hover:bg-emerald-50/40 dark:hover:bg-emerald-900/10' }}"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $aportacionSeleccionadaId == $currentId
                                            ? 'border-emerald-500'
                                            : 'border-outline-variant/50' }}"
                                    >
                                        @if($aportacionSeleccionadaId == $currentId)
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-sm text-on-surface dark:text-white">{{ $aportacion['proyecto_nombre'] }}</p>
                                            @if($aportacion['es_nuevo'])
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-[10px] font-bold uppercase rounded-full">Proyecto</span>
                                            @endif
                                        </div>
                                        @if($aportacion['fecha'])
                                            <p class="text-xs text-on-surface-variant">Registrada: {{ $aportacion['fecha'] }}</p>
                                        @endif
                                    </div>
                                </div>

                                <span class="font-bold text-emerald-700 dark:text-emerald-400 text-sm whitespace-nowrap ml-4">
                                    L. {{ number_format($aportacion['monto'], 2) }}
                                </span>
                            </label>
                            @endforeach
                        </div>

                        @if($aportacionSeleccionadaId)
                            <div class="mb-4">
                                <label class="block text-xs font-medium text-on-surface-variant mb-2">Monto a Abonar (L.) *</label>
                                <input
                                    wire:model.number="montoAportacion"
                                    type="number" step="0.01" min="0.01"
                                    class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-emerald-500"
                                >
                            </div>
                        @endif

                        <button
                            wire:click="addAportacion"
                            type="button"
                            {{ !$aportacionSeleccionadaId ? 'disabled' : '' }}
                            class="w-full py-3 bg-emerald-600 text-white rounded-lg font-bold text-sm uppercase tracking-wider hover:bg-emerald-700 disabled:opacity-40 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar Aportación Seleccionada
                        </button>
                    @endif
                </div>
            </div>
            @endif

            {{-- ── PANEL: OTRO PAGO ──────────────────────────────────────────────── --}}
            @if($tipoCobroActual === 'otro_pago')
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl border border-outline-variant/10 overflow-hidden">
                <div class="p-5 border-b border-outline-variant/10 flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <h3 class="font-bold text-on-surface dark:text-white">Agregar Otro Pago</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-2">Concepto *</label>
                            <input
                                wire:model="conceptoOtroPago"
                                type="text"
                                placeholder="Ej: Reparación, Multa, Material..."
                                class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-primary"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-2">Monto (L.) *</label>
                            <input
                                wire:model.number="montoOtroPago"
                                type="number" step="0.01" min="0" placeholder="0.00"
                                class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-primary"
                            >
                        </div>
                    </div>

                    <button
                        wire:click="addOtroPago"
                        type="button"
                        class="w-full py-3 bg-amber-500 text-white rounded-lg font-bold text-sm uppercase tracking-wider hover:bg-amber-600 transition-all flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Pago
                    </button>
                </div>
            </div>
            @endif

            {{-- ── PANEL: DONACIÓN MIEMBRO ────────────────────────────────────────── --}}
            @if($tipoCobroActual === 'donacion' && $tipoMovimiento === 'cobro')
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl border border-outline-variant/10 overflow-hidden">
                <div class="p-5 border-b border-outline-variant/10 flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <h3 class="font-bold text-on-surface dark:text-white">Registrar Donación de Miembro</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-2">Concepto / Motivo *</label>
                            <input
                                wire:model="conceptoDonacion"
                                type="text"
                                placeholder="Ej: Aporte voluntario, Donación pro-fiestas..."
                                class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-rose-500"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-2">Monto (L.) *</label>
                            <input
                                wire:model.number="montoDonacion"
                                type="number" step="0.01" min="0" placeholder="0.00"
                                class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-rose-500"
                            >
                        </div>
                    </div>

                    <button
                        wire:click="addDonacion"
                        type="button"
                        class="w-full py-3 bg-rose-500 text-white rounded-lg font-bold text-sm uppercase tracking-wider hover:bg-rose-600 transition-all flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Donación
                    </button>
                </div>
            </div>
            @endif
            @endif {{-- fin @if($selectedMiembro) --}}

            @endif {{-- fin @if($tipoMovimiento === 'cobro') --}}


            {{-- ══════════════════════════════════════════════════════════════════ --}}
            {{-- FLUJO: DONACIÓN DE COOPERANTE                                    --}}
            {{-- ══════════════════════════════════════════════════════════════════ --}}
            @if($tipoMovimiento === 'donacion')

            {{-- ── PANEL DONACIÓN ────────────────────────────────────────────────── --}}
            <div class="bg-surface-container-lowest dark:bg-slate-900 p-8 rounded-xl border border-amber-200/40 dark:border-amber-800/30">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-outline">Registrar Donación</h3>
                </div>

                {{-- Seleccionar Cooperante --}}
                <div class="mb-5">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider">Cooperante *</label>
                        <button type="button" wire:click="abrirModalCooperante" class="text-[10px] font-bold text-primary hover:text-primary-dim flex items-center gap-1 transition-all uppercase tracking-tighter">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar cooperante
                        </button>
                    </div>
                    @if(count($cooperantesDisponibles) === 0)
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800 rounded-lg text-center">
                            <p class="text-sm text-amber-800 dark:text-amber-300">No hay cooperantes registrados. Registra uno primero.</p>
                        </div>
                    @else
                        <select
                            wire:model.live="cooperanteSeleccionado"
                            class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-amber-500"
                        >
                            <option value="">-- Selecciona cooperante --</option>
                            @foreach($cooperantesDisponibles as $coop)
                            <option value="{{ $coop['id'] }}">{{ $coop['nombre'] }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                {{-- Concepto y Monto --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-xs font-medium text-on-surface-variant mb-2">Concepto *</label>
                        <input
                            wire:model="conceptoDonacion"
                            type="text"
                            placeholder="Ej: Donación equipos, Aporte proyecto..."
                            class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-amber-500"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-on-surface-variant mb-2">Monto (L.) *</label>
                        <input
                            wire:model.number="montoDonacion"
                            type="number" step="0.01" min="0" placeholder="0.00"
                            class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-amber-500"
                        >
                    </div>
                </div>

                <button
                    wire:click="addDonacion"
                    type="button"
                    class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-sm uppercase tracking-wider transition-all flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Agregar Donación
                </button>
            </div>

            {{-- Resumen y Generar Recibo (donación) --}}
            @if(count($agregadosServicios) > 0)
            <div class="bg-amber-500 text-white p-8 rounded-xl shadow-xl relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-400 to-amber-600 opacity-60"></div>
                <div class="relative z-10 space-y-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-amber-100">Resumen Donación</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-amber-100">
                            <span class="text-xs uppercase font-bold">Items</span>
                            <span class="font-bold">{{ count($agregadosServicios) }}</span>
                        </div>
                        <div class="pt-4 border-t border-white/20 flex justify-between items-end">
                            <span class="text-xs uppercase font-bold text-white">Total Donación</span>
                            <span class="text-4xl font-bold">L. {{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                    <button
                        type="button"
                        onclick="document.getElementById('confirmModalDonacion').classList.remove('hidden')"
                        class="w-full bg-white text-amber-600 py-3 rounded-lg font-bold text-sm uppercase tracking-wider shadow-lg hover:shadow-xl transition-all duration-200 active:scale-95 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Generar Recibo Donación
                    </button>
                </div>
            </div>
            @endif

            @endif {{-- fin @if($tipoMovimiento === 'donacion') --}}


            {{-- ── TABLA UNIFICADA DE ITEMS ──────────────────────────────────────── --}}
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl border border-outline-variant/10 overflow-hidden">
                <div class="p-5 border-b border-outline-variant/10 flex items-center gap-3">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="font-bold text-on-surface dark:text-white">
                        @if($tipoMovimiento === 'donacion')
                            Items de la Donación
                        @else
                            Items del Cobro
                        @endif
                    </h3>
                    @if(count($agregadosServicios) > 0)
                        <span class="ml-auto text-xs font-bold px-2 py-1 bg-primary-container/20 text-primary rounded-full">
                            {{ count($agregadosServicios) }} item(s)
                        </span>
                    @endif
                </div>

                @if(count($agregadosServicios) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-100 dark:bg-slate-700/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-200">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-200">Concepto</th>
                                <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-200">Monto Orig.</th>
                                <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-200">Ajuste</th>
                                <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-200">Total</th>
                                <th class="px-6 py-3 w-14"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            @foreach($agregadosServicios as $item)
                            <tr class="hover:bg-surface-container-highest/30 transition-colors group">
                                <td class="px-6 py-4">
                                    @if($item['tipo'] === 'suscripcion' || $item['tipo'] === 'servicio')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            Suscripción
                                        </span>
                                    @elseif($item['tipo'] === 'aportacion')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            Aportación
                                        </span>
                                    @elseif($item['tipo'] === 'donacion')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                            Donación
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Otro Pago
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-sm text-on-surface dark:text-white">{{ $item['nombre'] }}</p>
                                    @if(isset($item['tiene_medidor']) && $item['tiene_medidor'])
                                        <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 px-2 py-0.5 rounded-full">Con medidor</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xs text-on-surface-variant line-through">L. {{ number_format($item['monto_original'] ?? $item['monto'], 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if(isset($item['monto_ajuste']) && $item['monto_ajuste'] != 0)
                                        <span class="text-xs font-bold {{ $item['tipo_ajuste'] === 'adicional' ? 'text-indigo-600' : 'text-rose-600' }}">
                                            {{ $item['tipo_ajuste'] === 'adicional' ? '+' : '-' }} L. {{ number_format($item['monto_ajuste'], 2) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-outline-variant">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-sm text-on-surface dark:text-white">L. {{ number_format($item['monto'], 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end items-center gap-1">
                                    {{-- Botón Importe Adicional --}}
                                    <button
                                        wire:click="abrirModalAjuste('{{ $item['id'] }}', 'adicional')"
                                        type="button"
                                        title="Agregar importe adicional"
                                        class="text-indigo-600 hover:bg-indigo-100 dark:text-indigo-400 dark:hover:bg-indigo-900/30 p-1.5 rounded-full transition-all"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>

                                    {{-- Botón Descuento --}}
                                    <button
                                        wire:click="abrirModalAjuste('{{ $item['id'] }}', 'descuento')"
                                        type="button"
                                        title="Aplicar descuento"
                                        class="text-rose-600 hover:bg-rose-100 dark:text-rose-400 dark:hover:bg-rose-900/30 p-1.5 rounded-full transition-all"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>

                                    <button
                                        wire:click="removeItem('{{ $item['id'] }}')"
                                        type="button"
                                        title="Eliminar ítem"
                                        class="text-error hover:bg-error-container/20 p-1.5 rounded-full transition-all"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-100 dark:bg-slate-700/70">
                                <td colspan="3" class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-200">Total</td>
                                <td class="px-6 py-4 text-right text-lg font-bold text-blue-700 dark:text-blue-400">L. {{ number_format($total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="p-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-outline-variant/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h4 class="font-bold text-on-surface dark:text-white mb-1">Sin items agregados</h4>
                    <p class="text-sm text-on-surface-variant">
                        @if($tipoMovimiento === 'donacion')
                            Completa el formulario de arriba para agregar donaciones
                        @elseif(!$selectedMiembro)
                            Selecciona un miembro para empezar
                        @else
                            Usa las pestañas de arriba para agregar servicios, aportaciones u otros pagos
                        @endif
                    </p>
                </div>
                @endif
            </div>

        </div>
    </main>

    {{-- ══ MODALES ══════════════════════════════════════════════════════════════ --}}

    {{-- Modal: Lectura de Medidor --}}
    @if($showModalMedidor && $medidorActual)
    <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl p-8 max-w-md w-full border border-gray-200 dark:border-slate-700">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                Lectura de Medidor
            </h2>

            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">
                        Número de Medidor
                    </label>
                    <p class="px-4 py-3 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-lg text-gray-900 dark:text-white font-bold">
                        {{ $medidorActual->numero_medidor }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">
                        Lectura Anterior
                    </label>
                    <p class="px-4 py-3 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 rounded-lg text-gray-900 dark:text-white font-bold">
                        {{ number_format($lecturaAnterior ?? 0, 2) }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">
                        Lectura Actual *
                    </label>
                    <input
                        wire:model.live="lecturaActual"
                        type="number"
                        step="0.01"
                        placeholder="0.00"
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-400 focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                </div>

                @if($consumoCalculado !== null)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-slate-300 mb-1">
                        Consumo Calculado
                    </p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">
                        {{ number_format($consumoCalculado, 2) }}
                    </p>
                </div>
                @endif
            </div>

            <div class="flex gap-3">
                <button
                    wire:click="cancelarLecturaMedidor"
                    type="button"
                    class="flex-1 px-4 py-3 bg-slate-200 dark:bg-slate-700 text-gray-800 dark:text-slate-100 rounded-lg font-bold hover:bg-slate-300 dark:hover:bg-slate-600 transition-all"
                >
                    Cancelar
                </button>

                <button
                    wire:click="guardarLecturaMedidor"
                    type="button"
                    @if($lecturaActual < ($lecturaAnterior ?? 0)) disabled @endif
                    class="flex-1 px-4 py-3 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal: Confirmación Generar Recibo (COBRO) --}}
    <div id="confirmModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl p-8 max-w-md w-full border border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-primary-container/30 dark:bg-primary/20 rounded-full mb-4">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h2 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">
                ¿Generar Recibo?
            </h2>

            <p class="text-gray-600 dark:text-slate-300 text-center text-sm mb-6">
                Una vez generado, <strong class="text-gray-800 dark:text-white">no se puede deshacer</strong>.
                Verifica que todos los datos sean correctos.
            </p>

            <div class="bg-slate-50 dark:bg-slate-700/60 rounded-lg p-4 mb-6 space-y-3 text-sm border border-slate-200 dark:border-slate-600">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-slate-300">Cliente:</span>
                    <span class="font-bold text-gray-900 dark:text-white">
                        {{ $selectedPersona?->nombre }} {{ $selectedPersona?->apellido }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-slate-300">Items:</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ count($agregadosServicios) }}</span>
                </div>
                <div class="border-t border-slate-200 dark:border-slate-600 pt-3 flex justify-between items-center">
                    <span class="text-gray-700 dark:text-slate-200 font-semibold">Total:</span>
                    <span class="font-bold text-lg text-blue-700 dark:text-blue-400">L. {{ number_format($total, 2) }}</span>
                </div>
            </div>

            <div class="flex gap-3">
                <button
                    type="button"
                    onclick="document.getElementById('confirmModal').classList.add('hidden')"
                    class="flex-1 px-4 py-3 bg-slate-200 dark:bg-slate-700 text-gray-800 dark:text-slate-100 rounded-lg font-bold hover:bg-slate-300 dark:hover:bg-slate-600 transition-all"
                >
                    Cancelar
                </button>
                <button
                    wire:click="generarRecibo"
                    type="button"
                    onclick="document.getElementById('confirmModal').classList.add('hidden')"
                    class="flex-1 px-4 py-3 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-all"
                >
                    Generar
                </button>
            </div>
        </div>
    </div>

    {{-- Modal: Confirmación Generar Recibo (DONACIÓN) --}}
    <div id="confirmModalDonacion" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl p-8 max-w-md w-full border border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-amber-100 dark:bg-amber-900/20 rounded-full mb-4">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>

            <h2 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">
                ¿Generar Recibo de Donación?
            </h2>

            <p class="text-gray-600 dark:text-slate-300 text-center text-sm mb-6">
                Una vez generado, <strong class="text-gray-800 dark:text-white">no se puede deshacer</strong>.
                Verifica que todos los datos sean correctos.
            </p>

            <div class="bg-slate-50 dark:bg-slate-700/60 rounded-lg p-4 mb-6 space-y-3 text-sm border border-slate-200 dark:border-slate-600">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-slate-300">Tipo:</span>
                    <span class="font-bold text-amber-600 dark:text-amber-400">Donación</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-slate-300">Items:</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ count($agregadosServicios) }}</span>
                </div>
                <div class="border-t border-slate-200 dark:border-slate-600 pt-3 flex justify-between items-center">
                    <span class="text-gray-700 dark:text-slate-200 font-semibold">Total:</span>
                    <span class="font-bold text-lg text-amber-600 dark:text-amber-400">L. {{ number_format($total, 2) }}</span>
                </div>
            </div>

            <div class="flex gap-3">
                <button
                    type="button"
                    onclick="document.getElementById('confirmModalDonacion').classList.add('hidden')"
                    class="flex-1 px-4 py-3 bg-slate-200 dark:bg-slate-700 text-gray-800 dark:text-slate-100 rounded-lg font-bold hover:bg-slate-300 dark:hover:bg-slate-600 transition-all"
                >
                    Cancelar
                </button>
                <button
                    wire:click="generarRecibo"
                    type="button"
                    onclick="document.getElementById('confirmModalDonacion').classList.add('hidden')"
                    class="flex-1 px-4 py-3 bg-amber-500 text-white rounded-lg font-bold hover:bg-amber-600 transition-all"
                >
                    Generar
                </button>
            </div>
        </div>
    </div>

    {{-- Modal: Registro de Nuevo Cooperante --}}
    @if($showModalCooperante)
    <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-[60] p-4 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-slate-700 overflow-hidden transform transition-all">
            <div class="bg-primary px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nuevo Cooperante
                </h2>
                <button wire:click="cerrarModalCooperante" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="guardarCooperante" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-1">Nombre del Cooperante *</label>
                    <input wire:model="nombreCoop" type="text" 
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none text-gray-800 dark:text-white">
                    @error('nombreCoop') <span class="text-[10px] text-red-500 font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-1">Tipo *</label>
                        <input wire:model="tipoCoop" type="text" placeholder="Ej. ONG, Privado..."
                            class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none text-gray-800 dark:text-white">
                        @error('tipoCoop') <span class="text-[10px] text-red-500 font-bold uppercase">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-1">Teléfono *</label>
                        <input wire:model="telCoop" type="text"
                            class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none text-gray-800 dark:text-white">
                        @error('telCoop') <span class="text-[10px] text-red-500 font-bold uppercase">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-1">Dirección *</label>
                    <textarea wire:model="dirCoop" rows="2"
                        class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none text-gray-800 dark:text-white"></textarea>
                    @error('dirCoop') <span class="text-[10px] text-red-500 font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="cerrarModalCooperante"
                        class="flex-1 px-4 py-3 bg-slate-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-slate-200 transition-all">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-primary text-white rounded-xl font-bold text-sm uppercase tracking-wider shadow-lg hover:shadow-primary/30 transition-all">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Modal: Ajuste de Precio (Adicional / Descuento) --}}
    @if($showModalAjuste)
    <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-[70] p-4 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm border border-gray-200 dark:border-slate-700 overflow-hidden transform transition-all animate-fade-in-up">
            <div class="{{ $tipoAjuste === 'adicional' ? 'bg-indigo-600' : 'bg-rose-600' }} px-6 py-4 flex justify-between items-center text-white">
                <h2 class="text-sm font-bold uppercase tracking-widest flex items-center gap-2">
                    @if($tipoAjuste === 'adicional')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Importe Adicional
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        Aplicar Descuento
                    @endif
                </h2>
                <button wire:click="cerrarModalAjuste" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="aplicarAjuste" class="p-6 space-y-4">
                <div class="p-3 {{ $tipoAjuste === 'adicional' ? 'bg-indigo-50 dark:bg-indigo-900/10 text-indigo-700 dark:text-indigo-300' : 'bg-rose-50 dark:bg-rose-900/10 text-rose-700 dark:text-rose-300' }} rounded-lg text-xs leading-relaxed">
                    @if($tipoAjuste === 'adicional')
                        Ingresa el monto bruto (L.) que deseas <strong>sumar</strong> al concepto seleccionado.
                    @else
                        Ingresa el <strong>porcentaje (%)</strong> de descuento que deseas aplicar al monto actual del concepto.
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-1">
                        {{ $tipoAjuste === 'adicional' ? 'Monto a Sumar (L.)' : 'Porcentaje de Descuento (%)' }} *
                    </label>
                    <input wire:model="montoAjuste" type="number" step="any" min="0" autofocus
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-lg font-bold focus:ring-2 {{ $tipoAjuste === 'adicional' ? 'focus:ring-indigo-500' : 'focus:ring-rose-500' }} outline-none text-gray-800 dark:text-white">
                    @error('montoAjuste') <span class="text-[10px] text-red-500 font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" wire:click="cerrarModalAjuste"
                        class="flex-1 px-4 py-3 bg-slate-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 {{ $tipoAjuste === 'adicional' ? 'bg-indigo-600 shadow-indigo-200' : 'bg-rose-600 shadow-rose-200' }} text-white rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg hover:opacity-90 transition-all">
                        Aplicar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>