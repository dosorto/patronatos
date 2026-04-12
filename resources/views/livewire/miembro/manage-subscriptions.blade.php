<div class="mt-8 border-t border-gray-100 dark:border-gray-700 pt-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Gestión de Suscripciones</h2>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Servicios activos y disponibles para este miembro</p>
        </div>
        
        @if(!$mostrarFormulario)
            <button type="button" wire:click="mostrarNuevo" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase rounded-xl transition-all shadow-md active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nueva Suscripción
            </button>
        @else
            <button type="button" wire:click="mostrarNuevo" class="flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold uppercase rounded-xl transition-all">
                Cancelar
            </button>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-xl text-sm border border-green-100 dark:border-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulario para Agregar Nueva Suscripción --}}
    @if($mostrarFormulario)
        <div class="mb-8 p-6 bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800 rounded-2xl animate-in fade-in slide-in-from-top-4">
            <h3 class="text-sm font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-4">Suscribir a Nuevo Servicio</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Selector de Servicio --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Servicio *</label>
                    <select wire:model.live="servicio_id" class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-sm focus:ring-blue-500 shadow-sm transition-all @error('servicio_id') border-red-500 @enderror">
                        <option value="">-- Seleccionar --</option>
                        @foreach($serviciosDisponibles as $s)
                            <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                    @error('servicio_id') <span class="text-[10px] text-red-500 font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                {{-- Identificador --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Identificador (Opcional)</label>
                    <input type="text" wire:model="identificador" placeholder="Ej: Lote 15, Casa B" class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-sm focus:ring-blue-500 shadow-sm">
                </div>

                @if($servicio_id)
                    @php $servicioSeleccionado = $serviciosDisponibles->find($servicio_id); @endphp
                    @if($servicioSeleccionado && $servicioSeleccionado->tiene_medidor)
                        <div class="space-y-1 md:col-span-2 lg:col-span-1">
                            <label class="text-[10px] font-bold text-yellow-600 uppercase ml-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Medidor Requerido
                            </label>
                            <select wire:model.live="medidor_id" class="w-full px-4 py-3 rounded-xl border-yellow-200 dark:border-yellow-900/50 dark:bg-gray-900 text-sm focus:ring-yellow-500 shadow-sm transition-all border-l-4 border-l-yellow-500">
                                <option value="">-- Seleccionar medidor --</option>
                                @if(isset($medidoresLibres[$servicio_id]))
                                    @foreach($medidoresLibres[$servicio_id] as $ml)
                                        <option value="{{ $ml['id'] }}">Núm: {{ $ml['numero_medidor'] }}</option>
                                    @endforeach
                                @endif
                                <option value="nuevo" class="font-bold text-blue-600">+ Registrar nuevo aparato...</option>
                            </select>

                            @if($medidor_id === 'nuevo')
                                <input type="text" wire:model="nuevo_medidor_numero" placeholder="Escriba el número de serie..." class="mt-2 w-full px-4 py-3 rounded-xl border-yellow-300 dark:border-yellow-700 dark:bg-gray-900 text-sm focus:ring-blue-500 transition-all animate-in fade-in">
                                @error('nuevo_medidor_numero') <span class="text-[10px] text-red-500 font-bold uppercase">{{ $message }}</span> @enderror
                            @endif
                        </div>
                    @endif
                @endif
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" wire:click="guardarNuevaSuscripcion" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg active:scale-95">
                    Confirmar Suscripción
                </button>
            </div>
        </div>
    @endif

    {{-- Lista de Suscripciones Actuales --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-900/50">
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Servicio</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Identificador</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Detalles</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($miembro->suscripciones as $sub)
                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-colors">
                        <td class="px-6 py-4">
                            <span class="block font-bold text-gray-900 dark:text-white">{{ $sub->servicio->nombre }}</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-tighter">Desde: {{ $sub->fecha_inicio?->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $sub->identificador ?: '---' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($sub->medidor)
                                <div class="flex items-center gap-2 text-xs text-blue-600 dark:text-blue-400 font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Medidor: {{ $sub->medidor->numero_medidor }}
                                </div>
                            @else
                                <span class="text-[10px] text-gray-400 uppercase">Sin medidor</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button type="button" wire:click="toggleEstado({{ $sub->id }})" class="relative inline-flex items-center cursor-pointer transition-all">
                                <div class="w-10 h-5 bg-gray-200 dark:bg-gray-700 rounded-full transition-colors {{ $sub->estado ? 'bg-green-500 dark:bg-green-600' : '' }}"></div>
                                <div class="absolute left-1 w-3 h-3 bg-white rounded-full transition-all transform {{ $sub->estado ? 'translate-x-5' : '' }}"></div>
                                <span class="ml-3 text-[10px] font-black uppercase {{ $sub->estado ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $sub->estado ? 'Activo' : 'Pausado' }}
                                </span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button 
                                type="button"
                                onclick="confirm('¿Está seguro de eliminar esta suscripción? Esta acción no se puede deshacer.') || event.stopImmediatePropagation()"
                                wire:click="eliminarSuscripcion({{ $sub->id }})" 
                                class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all"
                                title="Eliminar suscripción"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.586a1 1 0 00-.707.293l-1.414 1.414a1 1 0 01-.707.293h-3.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 007.586 13H4"></path></svg>
                                <p class="text-gray-400 text-sm font-medium">No hay suscripciones activas.</p>
                                <button type="button" wire:click="mostrarNuevo" class="mt-4 text-blue-600 text-xs font-black uppercase tracking-widest hover:underline">Comenzar ahora</button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
