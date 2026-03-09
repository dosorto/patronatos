<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4">
    {{-- Mensajes de éxito y error --}}
    @if(session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    {{-- MODAL DE MEDIDOR --}}
    @if($showModalMedidor && $medidorActual)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Lectura de Medidor</h2>
            
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Número de Medidor
                    </label>
                    <p class="px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-xl text-gray-900 dark:text-white font-bold">
                        {{ $medidorActual->numero_medidor }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Lectura Anterior
                    </label>
                    <p class="px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-xl text-gray-900 dark:text-white font-bold">
                        {{ number_format($lecturaAnterior ?? 0, 2) }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Lectura Actual *
                    </label>
                    <input 
                        wire:model.live="lecturaActual"
                        type="number"
                        step="0.01"
                        placeholder="0.00"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all"
                    >
                </div>

                @if($consumoCalculado !== null)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Consumo Calculado</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ number_format($consumoCalculado, 2) }}
                    </p>
                </div>
                @endif
            </div>

            <div class="flex gap-3">
                <button 
                    wire:click="cancelarLecturaMedidor"
                    type="button"
                    class="flex-1 px-4 py-3 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-bold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all"
                >
                    Cancelar
                </button>
                <button 
                    wire:click="guardarLecturaMedidor"
                    type="button"
                    class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all"
                >
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-6xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">Nuevo Cobro</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Busca un miembro y agrega servicios para generar un recibo</p>
        </div>

        {{-- Tarjeta Principal --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
            
            {{-- PASO 1: Búsqueda de Persona --}}
            <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    Buscar Miembro
                </h2>

                <div class="relative">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">Búsqueda por DNI o Nombre</label>
                    <input 
                        wire:model.live="searchQuery" 
                        type="text"
                        placeholder="Ingresa DNI, nombre o apellido..."
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all"
                    >

                    {{-- Resultados de búsqueda --}}
                    @if($showSearchResults && count($searchResults) > 0)
                    <div class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-lg z-10">
                        <div class="max-h-64 overflow-y-auto">
                            @foreach($searchResults as $result)
                            <button 
                                wire:click="selectMiembro({{ $result['id'] }})"
                                type="button"
                                class="w-full text-left px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-600 border-b border-gray-200 dark:border-gray-600 last:border-b-0 transition-colors"
                            >
                                <p class="font-bold text-gray-900 dark:text-white">{{ $result['nombre'] }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">DNI: {{ $result['dni'] }}</p>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Persona Seleccionada --}}
                @if($selectedMiembro && $selectedPersona)
                <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-xl p-6 border border-blue-200 dark:border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Nombre</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedPersona->nombre }} {{ $selectedPersona->apellido }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">DNI</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedPersona->dni }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Dirección</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedMiembro->direccion ?? 'No registrada' }}</p>
                        </div>
                    </div>
                    <button 
                        wire:click="limpiar"
                        type="button"
                        class="mt-4 text-sm text-red-600 hover:text-red-700 font-bold"
                    >
                        Cambiar miembro
                    </button>
                </div>
                @endif
            </div>

            {{-- PASO 2: Agregar Servicios --}}
            @if($selectedMiembro)
            <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                    Agregar Servicios
                </h2>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Servicio</label>
                        <select 
                            wire:model="selectedServicioId"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all"
                        >
                            <option value="">-- Selecciona un servicio --</option>
                            @foreach($servicios as $servicio)
                            <option value="{{ $servicio['id'] }}">
                                {{ $servicio['nombre'] }}
                                @if($servicio['tiene_medidor'])
                                    (Con Medidor) - L. {{ number_format($servicio['precio_por_unidad_de_medida'], 2) }}/unidad
                                @else
                                    - L. {{ number_format($servicio['precio'], 2) }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-6">
                        <button 
                            wire:click="addServicio"
                            type="button"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar
                        </button>
                    </div>
                </div>
            </div>

            {{-- PASO 3: Tabla de Servicios Agregados --}}
            @if($showServiciosAñadidos && count($agregadosServicios) > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</span>
                    Servicios Agregados
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-900 dark:text-white">Servicio</th>
                                <th class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">Consumo</th>
                                <th class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">Monto</th>
                                <th class="px-6 py-3 text-center text-sm font-bold text-gray-900 dark:text-white">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($agregadosServicios as $servicio)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">
                                    {{ $servicio['nombre'] }}
                                    @if($servicio['tiene_medidor'])
                                        <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 px-2 py-1 rounded-full ml-2">Medidor</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-gray-900 dark:text-white font-bold">
                                    @if($servicio['consumo'] !== null)
                                        {{ number_format($servicio['consumo'], 2) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-gray-900 dark:text-white font-bold">L. {{ number_format($servicio['monto'], 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button 
                                        wire:click="removeServicio('{{ $servicio['id'] }}')"
                                        type="button"
                                        class="text-red-600 hover:text-red-700 font-bold text-sm"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Total y Botón Generar Recibo --}}
                <div class="mt-6 flex items-center justify-between bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-xl p-6 border border-green-200 dark:border-gray-600">
                    <div>
                        <p class="text-sm font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Total a Cobrar</p>
                        <p class="text-4xl font-bold text-green-600 dark:text-green-400">L. {{ number_format($total, 2) }}</p>
                    </div>
                    <button 
                        wire:click="generarRecibo"
                        type="button"
                        class="px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all shadow-lg flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Generar Recibo
                    </button>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>