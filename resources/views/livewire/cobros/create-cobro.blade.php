<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4">
    {{-- Mensajes de éxito y error --}}
    @if(session()->has('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- MODAL DE MEDIDOR --}}
    @if($showModalMedidor && $medidorActual)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl p-8 max-w-md w-full">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Lectura de Medidor</h2>
            
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Número de Medidor
                    </label>
                    <p class="px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-900 dark:text-white font-bold">
                        {{ $medidorActual->numero_medidor }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lectura Anterior
                    </label>
                    <p class="px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-900 dark:text-white font-bold">
                        {{ number_format($lecturaAnterior ?? 0, 2) }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lectura Actual *
                    </label>
                    <input 
                        wire:model.live="lecturaActual"
                        type="number"
                        step="0.01"
                        placeholder="0.00"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all"
                    >
                </div>

                @if($consumoCalculado !== null)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
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
                    class="flex-1 px-4 py-3 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-all"
                >
                    Cancelar
                </button>
                <button 
                    wire:click="guardarLecturaMedidor"
                    type="button"
                    class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all"
                >
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-4xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Cobro</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Busca un miembro y agrega servicios para generar un recibo</p>
        </div>

        <form id="cobroForm">
            {{-- Steps Indicator --}}
            <div class="mb-6">
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-0 right-0 top-4 h-0.5 bg-gray-200 dark:bg-gray-700 z-0"></div>

                    <div 
                        class="absolute left-0 top-4 h-0.5 bg-blue-600 z-0 transition-all duration-500"
                        style="width: {{ $currentStep == 1 ? '0%' : ($currentStep == 2 ? '50%' : '100%') }}">
                    </div>

                    @foreach([1 => 'Buscar Miembro', 2 => 'Agregar Servicios', 3 => 'Confirmar Cobro'] as $num => $label)
                        <div class="relative z-10 flex flex-col items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300
                                {{ $num < $currentStep
                                    ? 'bg-green-500 border-green-500 text-white'
                                    : ($num == $currentStep
                                        ? 'bg-blue-600 border-blue-600 text-white'
                                        : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-400 dark:text-gray-500') }}"
                            >
                                @if($num < $currentStep)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    {{ $num }}
                                @endif
                            </div>

                            <span
                                class="text-xs font-medium hidden sm:block transition-colors duration-300
                                {{ $num < $currentStep
                                    ? 'text-green-500'
                                    : ($num == $currentStep
                                        ? 'text-blue-600 dark:text-blue-400'
                                        : 'text-gray-400 dark:text-gray-500') }}"
                            >
                                {{ $label }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">

                {{-- PASO 1: Búsqueda de Miembro --}}
                <div id="step-1" class="{{ $currentStep !== 1 ? 'hidden' : '' }}">
                    <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Buscar Miembro
                    </h2>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Búsqueda por DNI o Nombre</label>
                        <div class="relative group">
                            <input 
                                wire:model.live="searchQuery" 
                                type="text"
                                placeholder="Ingresa DNI, nombre o apellido..."
                                class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all shadow-sm text-base text-gray-900 dark:text-white"
                            >
                            <button 
                                type="button"
                                wire:click="$refresh"
                                class="absolute right-2 top-2 bottom-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all shadow-md active:scale-95"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Resultados de búsqueda --}}
                        @if($showSearchResults && count($searchResults) > 0)
                        <div class="mt-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg overflow-hidden">
                            <div class="max-h-64 overflow-y-auto">
                                @foreach($searchResults as $result)
                                <button 
                                    wire:click="selectMiembro({{ $result['id'] }})"
                                    type="button"
                                    class="w-full text-left px-6 py-4 hover:bg-blue-50 dark:hover:bg-gray-600 border-b border-gray-200 dark:border-gray-700 last:border-b-0 transition-colors"
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
                    <div class="mb-8 p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Nombre</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedPersona->nombre }} {{ $selectedPersona->apellido }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">DNI</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedPersona->dni }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Dirección</p>
                                <p class="text-gray-900 dark:text-white">{{ $selectedMiembro->direccion ?? 'No registrada' }}</p>
                            </div>
                        </div>
                        <button 
                            wire:click="limpiar"
                            type="button"
                            class="text-sm text-red-600 hover:text-red-700 font-bold"
                        >
                            Cambiar miembro
                        </button>
                    </div>

                    <div class="flex justify-end">
                        <button 
                            type="button"
                            wire:click="goToStep(2)"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2"
                        >
                            Siguiente
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    @endif
                </div>

                {{-- PASO 2: Agregar Servicios --}}
                <div id="step-2" class="{{ $currentStep !== 2 ? 'hidden' : '' }}">
                    <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Agregar Servicios
                    </h2>

                    {{-- Resumen de Miembro --}}
                    <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">Cobrando a:</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $selectedPersona?->nombre }} {{ $selectedPersona?->apellido }}
                        </p>
                    </div>

                    <div class="mb-8 flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Servicio</label>
                            <select 
                                wire:model="selectedServicioId"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all"
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
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all shadow-md flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Agregar
                            </button>
                        </div>
                    </div>

                    @if($showServiciosAñadidos && count($agregadosServicios) > 0)
                    <div class="mb-8 overflow-x-auto">
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

                    <div class="flex justify-between">
                        <button 
                            type="button"
                            wire:click="goToStep(1)"
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Anterior
                        </button>
                        <button 
                            type="button"
                            wire:click="goToStep(3)"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2"
                        >
                            Siguiente
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <p class="text-sm">Agrega servicios para continuar</p>
                    </div>
                    @endif
                </div>

                {{-- PASO 3: Confirmar Cobro --}}
                <div id="step-3" class="{{ $currentStep !== 3 ? 'hidden' : '' }}">
                    <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Confirmar Cobro
                    </h2>

                    <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">Cobrando a:</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $selectedPersona?->nombre }} {{ $selectedPersona?->apellido }}
                        </p>
                    </div>

                    @if(count($agregadosServicios) > 0)
                    <div class="mb-8 overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-900 dark:text-white">Servicio</th>
                                    <th class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">Consumo</th>
                                    <th class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($agregadosServicios as $servicio)
                                <tr>
                                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">{{ $servicio['nombre'] }}</td>
                                    <td class="px-6 py-4 text-right text-gray-900 dark:text-white font-bold">
                                        @if($servicio['consumo'] !== null)
                                            {{ number_format($servicio['consumo'], 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-900 dark:text-white font-bold">L. {{ number_format($servicio['monto'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg mb-8">
                        <p class="text-sm font-bold text-gray-600 dark:text-gray-400 uppercase mb-2">Total a Cobrar</p>
                        <p class="text-4xl font-bold text-green-600 dark:text-green-400">L. {{ number_format($total, 2) }}</p>
                    </div>

                    <div class="flex justify-between">
                        <button 
                            type="button"
                            wire:click="goToStep(2)"
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Anterior
                        </button>
                        <button 
                            wire:click="generarRecibo"
                            type="button"
                            class="px-8 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all shadow-lg flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Generar Recibo
                        </button>
                    </div>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>