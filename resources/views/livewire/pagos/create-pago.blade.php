<div class="min-h-screen bg-background dark:bg-slate-900">
    <main class="overflow-auto">

        {{-- Top Bar --}}
        <header class="bg-white dark:bg-slate-950 border-b border-outline-variant/10 px-8 py-4 sticky top-0 z-40 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-on-surface dark:text-white">Nuevo Pago</h2>
                    <p class="text-sm text-on-surface-variant dark:text-slate-400">Terminal de Egresos</p>
                </div>
                <span class="px-3 py-1 bg-primary-container text-on-primary-container rounded-full text-xs font-bold uppercase">
                    Efectivo
                </span>
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

            {{-- Resumen superior --}}
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 lg:col-span-7 bg-surface-container-lowest dark:bg-slate-900 p-8 rounded-xl border border-outline-variant/10">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-outline">Tipo de Pago</h3>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <button type="button" wire:click="cambiarTipoPago('salarios')"
                            class="px-4 py-3 rounded-lg font-bold text-sm uppercase tracking-wider transition-all flex items-center justify-center gap-2
                            {{ $tipoPagoActual === 'salarios' ? 'bg-primary text-on-primary shadow-lg' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-highest dark:bg-slate-700 dark:text-slate-300' }}">
                            Salarios
                        </button>

                        <button type="button" wire:click="cambiarTipoPago('mantenimientos')"
                            class="px-4 py-3 rounded-lg font-bold text-sm uppercase tracking-wider transition-all flex items-center justify-center gap-2
                            {{ $tipoPagoActual === 'mantenimientos' ? 'bg-emerald-600 text-white shadow-lg' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-highest dark:bg-slate-700 dark:text-slate-300' }}">
                            Mantenimiento
                        </button>

                        <button type="button" wire:click="cambiarTipoPago('otro_pago')"
                            class="px-4 py-3 rounded-lg font-bold text-sm uppercase tracking-wider transition-all flex items-center justify-center gap-2
                            {{ $tipoPagoActual === 'otro_pago' ? 'bg-amber-500 text-white shadow-lg' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-highest dark:bg-slate-700 dark:text-slate-300' }}">
                            Otro Pago
                        </button>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-5 bg-primary text-on-primary p-8 rounded-xl shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dim opacity-50"></div>
                    <div class="relative z-10 space-y-6">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-primary-fixed-dim">Resumen Total</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-primary-fixed-dim/80">
                                <span class="text-xs uppercase font-bold">Items</span>
                                <span class="font-bold">{{ count($itemsAgregados) }}</span>
                            </div>
                            <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                                <span class="text-xs uppercase font-bold text-white">Total a Pagar</span>
                                <span class="text-4xl font-bold">L. {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <button
                            type="button"
                            onclick="document.getElementById('confirmModal').classList.remove('hidden')"
                            {{ count($itemsAgregados) == 0 ? 'disabled' : '' }}
                            class="w-full bg-white text-primary dark:bg-slate-100 dark:text-slate-900 py-3 rounded-lg font-bold text-sm uppercase tracking-wider shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 active:scale-95 flex items-center justify-center gap-2"
                        >
                            @if(count($itemsAgregados) == 0)
                                Agrega items
                            @else
                                Generar Recibo
                            @endif
                        </button>
                    </div>
                </div>
            </div>

            {{-- Panel salarios --}}
            @if($tipoPagoActual === 'salarios')
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl border border-outline-variant/10 overflow-hidden">
                <div class="p-5 border-b border-outline-variant/10 flex items-center gap-3">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="font-bold text-on-surface dark:text-white">Buscar Empleado</h3>
                </div>

                <div class="p-6">
                    @php
                        $yaTieneSalario = collect($itemsAgregados)->contains('tipo', 'salario');
                    @endphp

                    @if($yaTieneSalario)
                        <div class="p-8 text-center border-2 border-dashed border-primary/20 rounded-xl bg-primary-container/5">
                            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-on-surface dark:text-white mb-1">Salario agregado</h4>
                            <p class="text-sm text-on-surface-variant max-w-xs mx-auto">Ya has seleccionado un empleado para este proceso. Genera el recibo o elimina el item actual para seleccionar otro.</p>
                        </div>
                    @else
                        @if(!$empleadoSeleccionado)
                            <label class="block text-xs font-medium text-on-surface-variant mb-2">Nombre del empleado</label>
                            <div class="relative mb-4">
                                <input
                                    wire:model.live="searchEmpleado"
                                    type="text"
                                    placeholder="Escribe nombre o apellido..."
                                    class="w-full px-4 py-3 bg-surface-container border border-outline-variant/30 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-700 dark:text-white"
                                >
                                <button
                                    type="button"
                                    wire:click="$refresh"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 px-3 py-1 bg-primary hover:bg-primary-dim text-white rounded-lg text-sm font-bold transition-all"
                                >
                                    Buscar
                                </button>
                            </div>

                            @if($showSearchEmpleadoResults && count($searchEmpleadoResults) > 0)
                            <div class="bg-white dark:bg-slate-800 border border-outline-variant/20 dark:border-slate-700 rounded-lg shadow-lg overflow-hidden">
                                <div class="max-h-64 overflow-y-auto">
                                    @foreach($searchEmpleadoResults as $emp)
                                    <button
                                        wire:click="selectEmpleado({{ $emp['id'] }})"
                                        type="button"
                                        class="w-full text-left px-6 py-4 hover:bg-primary-container/10 dark:hover:bg-slate-700 border-b border-outline-variant/10 dark:border-slate-700 last:border-b-0 transition-colors"
                                    >
                                        <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $emp['nombre'] }}</p>
                                        <p class="text-xs text-gray-600 dark:text-slate-300">
                                            {{ $emp['cargo'] }} - L. {{ number_format($emp['sueldo_mensual'], 2) }}
                                        </p>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @else
                            <div class="p-6 bg-primary-container/10 border border-primary/30 rounded-lg space-y-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-outline mb-1">Empleado</p>
                                    <p class="font-bold text-lg text-on-surface dark:text-white">{{ $empleadoSeleccionado['nombre'] }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-outline mb-1">Cargo</p>
                                        <p class="font-medium text-on-surface dark:text-white">{{ $empleadoSeleccionado['cargo'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-outline mb-1">Último Pago</p>
                                        <p class="font-medium text-on-surface dark:text-white">
                                            {{ $empleadoSeleccionado['ultimo_mes_pagado'] 
                                                ? ucfirst(\Carbon\Carbon::parse($empleadoSeleccionado['ultimo_mes_pagado'])->translatedFormat('F Y')) 
                                                : 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="col-span-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg flex justify-between items-center">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-0.5">Mes a Cubrir</p>
                                            <p class="font-bold text-sm text-blue-800 dark:text-blue-200">
                                                @php
                                                    $ultimo = $empleadoSeleccionado['ultimo_mes_pagado'] 
                                                        ? \Carbon\Carbon::parse($empleadoSeleccionado['ultimo_mes_pagado']) 
                                                        : now()->subMonth();
                                                    $proximo = $ultimo->addMonth();
                                                @endphp
                                                {{ ucfirst($proximo->translatedFormat('F Y')) }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-0.5">Monto</p>
                                            <p class="font-bold text-sm text-blue-800 dark:text-blue-200">L. {{ number_format($empleadoSeleccionado['sueldo_mensual'], 2) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <button
                                        wire:click="addSalario"
                                        type="button"
                                        class="flex-1 px-4 py-3 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-all"
                                    >
                                        Agregar Salario
                                    </button>

                                    <button
                                        wire:click="$set('empleadoSeleccionado', null)"
                                        type="button"
                                        class="flex-1 px-4 py-3 bg-surface-container text-on-surface rounded-lg font-bold hover:bg-surface-container-highest transition-all dark:bg-slate-700"
                                    >
                                        Cambiar
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            @endif

            {{-- Panel mantenimiento --}}
            @if($tipoPagoActual === 'mantenimientos')
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl border border-outline-variant/10 overflow-hidden">
                <div class="p-5 border-b border-outline-variant/10 flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.364 2.444a1 1 0 00-.364 1.118l1.286 3.955c.3.921-.755 1.688-1.54 1.118l-3.364-2.444a1 1 0 00-1.176 0l-3.364 2.444c-.784.57-1.838-.197-1.539-1.118l1.285-3.955a1 1 0 00-.363-1.118L2.98 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.372-3.955z"/>
                    </svg>
                    <h3 class="font-bold text-on-surface dark:text-white">Agregar Mantenimiento</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select
                            wire:model="mantenimientoSeleccionadoId"
                            class="flex-1 px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-emerald-500"
                        >
                            <option value="">-- Selecciona mantenimiento --</option>
                            @foreach($mantenimientosPendientes as $m)
                                <option value="{{ $m['id'] }}">
                                    {{ $m['tipo_mantenimiento'] }} - {{ $m['descripcion'] }} - L. {{ number_format($m['costo_estimado'], 2) }}
                                </option>
                            @endforeach
                        </select>

                        <button
                            wire:click="addMantenimiento"
                            type="button"
                            class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-emerald-700 transition-all flex items-center gap-2 whitespace-nowrap"
                        >
                            Agregar
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- Panel otro pago --}}
            @if($tipoPagoActual === 'otro_pago')
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl border border-outline-variant/10 overflow-hidden">
                <div class="p-5 border-b border-outline-variant/10 flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <h3 class="font-bold text-on-surface dark:text-white">Agregar Otro Pago</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-2">Concepto *</label>
                            <input
                                wire:model="conceptoOtroPago"
                                type="text"
                                placeholder="Ej: Material, viáticos..."
                                class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-amber-500"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-2">Descripción</label>
                            <input
                                wire:model="descripcionOtroPago"
                                type="text"
                                placeholder="Detalle opcional"
                                class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-amber-500"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-2">Monto (L.) *</label>
                            <input
                                wire:model.number="montoOtroPago"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                                class="w-full px-4 py-2.5 border border-outline-variant/30 rounded-lg bg-surface-container dark:bg-slate-700 text-on-surface dark:text-white text-sm focus:ring-2 focus:ring-amber-500"
                            >
                        </div>
                    </div>

                    <button
                        wire:click="addOtroPago"
                        type="button"
                        class="w-full py-3 bg-amber-500 text-white rounded-lg font-bold text-sm uppercase tracking-wider hover:bg-amber-600 transition-all flex items-center justify-center gap-2"
                    >
                        Agregar Pago
                    </button>
                </div>
            </div>
            @endif

            {{-- Tabla unificada --}}
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl border border-outline-variant/10 overflow-hidden">
                <div class="p-5 border-b border-outline-variant/10 flex items-center gap-3">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    </svg>
                    <h3 class="font-bold text-on-surface dark:text-white">Items del Pago</h3>
                    @if(count($itemsAgregados) > 0)
                        <span class="ml-auto text-xs font-bold px-2 py-1 bg-primary-container/20 text-primary rounded-full">
                            {{ count($itemsAgregados) }} item(s)
                        </span>
                    @endif
                </div>

                @if(count($itemsAgregados) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-100 dark:bg-slate-700/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-200">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-200">Concepto</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-200">Descripción</th>
                                <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-200">Monto</th>
                                <th class="px-6 py-3 w-14"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            @foreach($itemsAgregados as $item)
                            <tr class="hover:bg-surface-container-highest/30 transition-colors group">
                                <td class="px-6 py-4">
                                    @if($item['tipo'] === 'salario')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                            Salario
                                        </span>
                                    @elseif($item['tipo'] === 'mantenimiento')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Mantenimiento
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            Otro Pago
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-sm text-on-surface dark:text-white">{{ $item['concepto'] }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-on-surface dark:text-white">
                                    {{ $item['descripcion'] ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-sm text-on-surface dark:text-white">L. {{ number_format($item['monto'], 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button
                                        wire:click="removeItem('{{ $item['id'] }}')"
                                        type="button"
                                        class="text-error hover:bg-error-container/20 p-1.5 rounded-full"
                                    >
                                        ✕
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    </svg>
                    <h4 class="font-bold text-on-surface dark:text-white mb-1">Sin items agregados</h4>
                    <p class="text-sm text-on-surface-variant">
                        Usa las pestañas de arriba para agregar salarios, mantenimientos u otros pagos
                    </p>
                </div>
                @endif
            </div>

        </div>
    </main>

    {{-- Modal confirmación --}}
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
                    <span class="text-gray-600 dark:text-slate-300">Método:</span>
                    <span class="font-bold text-gray-900 dark:text-white">Efectivo</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-slate-300">Items:</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ count($itemsAgregados) }}</span>
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
</div>