@extends('layouts.app')

@section('title', 'Nuevo Proyecto')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Proyecto</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Registra un nuevo proyecto en el sistema</p>
    </div>

    {{-- Errores de validación del servidor --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <p class="text-red-800 dark:text-red-200 font-medium">Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('proyecto.store') }}" method="POST" id="proyectoForm">
        @csrf

        {{-- Input hidden para el responsable --}}
        <input type="hidden" name="directiva_id" value="{{ $directivas->first()?->id }}">

        {{-- Contenedor de hidden inputs para presupuestos acumulados --}}
        <div id="presupuestos-hidden-container"></div>

        {{-- Steps Indicator --}}
        <div class="mb-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 right-0 top-4 h-0.5 bg-gray-200 dark:bg-gray-700 z-0"></div>
                <div class="absolute left-0 top-4 h-0.5 bg-blue-600 z-0 transition-all duration-500" id="progressBar" style="width: 0%"></div>

                @foreach([1 => 'Información General', 2 => 'Beneficiarios', 3 => 'Presupuesto', 4 => 'Detalle Presupuesto'] as $num => $label)
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div id="step-circle-{{ $num }}"
                             class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300
                             {{ $num === 1 ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-400 dark:text-gray-500' }}">
                            <span id="step-icon-{{ $num }}">{{ $num }}</span>
                        </div>
                        <span id="step-label-{{ $num }}"
                              class="text-xs font-medium hidden sm:block transition-colors duration-300
                              {{ $num === 1 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500' }}">
                            {{ $label }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">

            {{-- Step 1: Información General --}}
            <div id="step-1">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Información General
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nombre del Proyecto --}}
                    <div class="md:col-span-2">
                        <label for="nombre_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre del Proyecto *</label>
                        <input type="text" name="nombre_proyecto" id="nombre_proyecto" value="{{ old('nombre_proyecto') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nombre_proyecto') border-red-500 @enderror">
                        @error('nombre_proyecto')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tipo de Proyecto --}}
                    <div>
                        <label for="tipo_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Proyecto</label>
                        <select name="tipo_proyecto" id="tipo_proyecto"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('tipo_proyecto') border-red-500 @enderror">
                            <option value="">-- Seleccionar tipo --</option>
                            @foreach($tiposProyecto as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_proyecto') == $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_proyecto')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Número de Acta --}}
                    <div>
                        <label for="numero_acta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Número de Acta</label>
                        <input type="text" name="numero_acta" id="numero_acta" value="{{ old('numero_acta') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('numero_acta') border-red-500 @enderror">
                        @error('numero_acta')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha Aprobación Asamblea --}}
                    <div>
                        <label for="fecha_aprobacion_asamblea" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Aprobación Asamblea</label>
                        <input type="date" name="fecha_aprobacion_asamblea" id="fecha_aprobacion_asamblea" value="{{ old('fecha_aprobacion_asamblea') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_aprobacion_asamblea') border-red-500 @enderror">
                        @error('fecha_aprobacion_asamblea')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha Inicio --}}
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_inicio') border-red-500 @enderror">
                        @error('fecha_inicio')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha Fin --}}
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_fin') border-red-500 @enderror">
                        @error('fecha_fin')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Responsable (solo lectura) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Responsable del Proyecto</label>
                        <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            {{ $directivas->first()?->miembro->persona->nombre_completo ?? 'N/A' }}
                            @if($directivas->first()?->cargo)
                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $directivas->first()->cargo }})</span>
                            @endif
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Justificación --}}
                    <div class="md:col-span-2">
                        <label for="justificacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Justificación</label>
                        <textarea name="justificacion" id="justificacion" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('justificacion') border-red-500 @enderror">{{ old('justificacion') }}</textarea>
                        @error('justificacion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="goToStep(2)"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                        Siguiente
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Step 2: Beneficiarios --}}
            <div id="step-2" class="hidden">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Beneficiarios
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Descripción Beneficiarios --}}
                    <div class="md:col-span-2">
                        <label for="descripcion_beneficiarios" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción de Beneficiarios</label>
                        <textarea name="descripcion_beneficiarios" id="descripcion_beneficiarios" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descripcion_beneficiarios') border-red-500 @enderror">{{ old('descripcion_beneficiarios') }}</textarea>
                        @error('descripcion_beneficiarios')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hombres --}}
                    <div>
                        <label for="benef_hombres" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hombres</label>
                        <input type="number" name="benef_hombres" id="benef_hombres" value="{{ old('benef_hombres', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_hombres') border-red-500 @enderror">
                        @error('benef_hombres')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mujeres --}}
                    <div>
                        <label for="benef_mujeres" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mujeres</label>
                        <input type="number" name="benef_mujeres" id="benef_mujeres" value="{{ old('benef_mujeres', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_mujeres') border-red-500 @enderror">
                        @error('benef_mujeres')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Niños --}}
                    <div>
                        <label for="benef_ninos" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Niños</label>
                        <input type="number" name="benef_ninos" id="benef_ninos" value="{{ old('benef_ninos', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_ninos') border-red-500 @enderror">
                        @error('benef_ninos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Familias --}}
                    <div>
                        <label for="benef_familias" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Familias</label>
                        <input type="number" name="benef_familias" id="benef_familias" value="{{ old('benef_familias', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_familias') border-red-500 @enderror">
                        @error('benef_familias')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="goToStep(1)"
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Anterior
                    </button>
                    <button type="button" onclick="goToStep(3)"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                        Siguiente
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Step 3: Presupuesto --}}
            <div id="step-3" class="hidden">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Presupuesto
                </h2>

                {{-- Resumen de presupuestos agregados --}}
                <div id="presupuestos-resumen" class="hidden mb-6">
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-3">Presupuestos agregados:</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                <tr>
                                    <th class="px-4 py-2">#</th>
                                    <th class="px-4 py-2">Tipo</th>
                                    <th class="px-4 py-2">Año</th>
                                    <th class="px-4 py-2">Monto</th>
                                    <th class="px-4 py-2">Cooperante</th>
                                    <th class="px-4 py-2">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="presupuestos-resumen-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Formulario de presupuesto --}}
                <div id="presupuesto-form-container">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- ¿Es Donación? (ARRIBA) --}}
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <input type="checkbox" id="pres_es_donacion" onchange="toggleTipoPresupuesto()"
                                       class="w-5 h-5 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:bg-gray-700">
                                <div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">¿Es donación de un cooperante?</span>
                                    <p id="pres_tipo_hint" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Actualmente: Financiado por el patronato (comunidad)</p>
                                </div>
                            </label>
                        </div>

                        {{-- Cooperante (visible solo si es donación) --}}
                        <div id="cooperante-container" class="md:col-span-2 hidden">
                            <label for="pres_cooperante" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cooperante</label>
                            <select id="pres_cooperante"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">-- Seleccionar Cooperante --</option>
                                @foreach($cooperantes as $cooperante)
                                    <option value="{{ $cooperante->id_cooperante }}">{{ $cooperante->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Año del Presupuesto --}}
                        <div>
                            <label for="pres_anio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Año del Presupuesto</label>
                            <input type="number" id="pres_anio" min="2000" max="2100"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        {{-- Monto (calculado automáticamente desde detalles) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Monto Total</label>
                            <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm italic">
                                Se calculará automáticamente al agregar los detalles del presupuesto
                            </div>
                        </div>                        

                        {{-- Fecha Aprobación --}}
                        <div>
                            <label for="pres_fecha_aprobacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Aprobación</label>
                            <input type="date" id="pres_fecha_aprobacion"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                    </div>
                </div>

                {{-- Pregunta: ¿Agregar otro? --}}
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-3">¿Desea agregar otra parte del presupuesto a este proyecto?</p>
                    <div class="flex gap-3">
                        <button type="button" onclick="agregarPresupuesto(true)"
                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Sí, agregar otro
                        </button>
                        <button type="button" onclick="agregarPresupuesto(false)"
                                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                            No, continuar
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="goToStep(2)"
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Anterior
                    </button>
                </div>
            </div>

            {{-- Step 4: Detalle de Presupuesto --}}
            <div id="step-4" class="hidden">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Detalle de Presupuesto
                </h2>

                {{-- Selector de presupuesto --}}
                <div class="mb-6">
                    <label for="detalle_presupuesto_selector" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seleccionar Presupuesto</label>
                    <select id="detalle_presupuesto_selector" onchange="cargarDetallesPresupuesto()"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </select>
                </div>

                {{-- Resumen de detalles agregados --}}
                <div id="detalles-resumen" class="hidden mb-6">
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-3">Detalles agregados al presupuesto seleccionado:</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                <tr>
                                    <th class="px-4 py-2">#</th>
                                    <th class="px-4 py-2">Nombre</th>
                                    <th class="px-4 py-2">Cantidad</th>
                                    <th class="px-4 py-2">Unidad</th>
                                    <th class="px-4 py-2">P. Unitario</th>
                                    <th class="px-4 py-2">Total</th>
                                    <th class="px-4 py-2">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="detalles-resumen-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Formulario de detalle --}}
                <div id="detalle-form-container">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nombre --}}
                        <div class="md:col-span-2">
                            <label for="det_nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre / Descripción del rubro</label>
                            <input type="text" id="det_nombre"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        {{-- Cantidad --}}
                        <div>
                            <label for="det_cantidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cantidad</label>
                            <input type="number" id="det_cantidad" step="0.01" min="0" oninput="calcularTotalDetalle()"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        {{-- Unidad de Medida --}}
                        <div>
                            <label for="det_unidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unidad de Medida</label>
                            <select id="det_unidad"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">-- Seleccionar --</option>
                                @foreach($unidadesMedida as $unidad)
                                    <option value="{{ $unidad }}">{{ $unidad }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Precio Unitario --}}
                        <div>
                            <label for="det_precio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Precio Unitario</label>
                            <input type="number" id="det_precio" step="0.01" min="0" oninput="calcularTotalDetalle()"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        {{-- Total (calculado) --}}
                        <div>
                            <label for="det_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total</label>
                            <input type="number" id="det_total" step="0.01" min="0" readonly
                                   class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        </div>

                        {{-- Observaciones --}}
                        <div class="md:col-span-2">
                            <label for="det_observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observaciones</label>
                            <textarea id="det_observaciones" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Pregunta: ¿Agregar otro detalle? --}}
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-3">¿Desea agregar otro detalle a este presupuesto?</p>
                    <div class="flex gap-3">
                        <button type="button" onclick="agregarDetalle(true)"
                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Sí, agregar otro
                        </button>
                        <button type="button" onclick="agregarDetalle(false)"
                                class="px-4 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            No, terminé con este presupuesto
                        </button>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="goToStep(3)"
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Anterior
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Guardar Proyecto
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    const TOTAL_STEPS = 4;
    let currentStep = 1;
    let presupuestos = []; // Array acumulado de presupuestos

    // ── Cooperantes data para referencia en labels ──
    const cooperantesMap = {
        @foreach($cooperantes as $c)
            '{{ $c->id_cooperante }}': '{{ $c->nombre }}',
        @endforeach
    };

    // Si hay errores del servidor, volver al step 1
    @if($errors->has('nombre_proyecto'))
        currentStep = 1;
    @endif

    // ══════════════════════════════════════════════
    // WIZARD NAVIGATION
    // ══════════════════════════════════════════════
    function goToStep(step) {
        // Validación Step 1 → 2
        if (step === 2 && currentStep === 1) {
            const nombre = document.getElementById('nombre_proyecto').value.trim();
            if (!nombre) {
                const input = document.getElementById('nombre_proyecto');
                input.classList.add('border-red-500');
                input.focus();
                let msg = document.getElementById('nombre-error-msg');
                if (!msg) {
                    msg = document.createElement('p');
                    msg.id = 'nombre-error-msg';
                    msg.className = 'mt-1 text-sm text-red-600 dark:text-red-400';
                    msg.textContent = 'El nombre del proyecto es obligatorio.';
                    input.parentNode.appendChild(msg);
                }
                return;
            }
            document.getElementById('nombre_proyecto').classList.remove('border-red-500');
            const msg = document.getElementById('nombre-error-msg');
            if (msg) msg.remove();
        }

        // Al entrar al Step 4, popular selector de presupuestos
        if (step === 4) {
            popularSelectorPresupuestos();
        }

        // Ocultar step actual, mostrar nuevo
        document.getElementById(`step-${currentStep}`).classList.add('hidden');
        document.getElementById(`step-${step}`).classList.remove('hidden');

        // Actualizar indicadores visuales
        for (let n = 1; n <= TOTAL_STEPS; n++) {
            const circle = document.getElementById(`step-circle-${n}`);
            const label  = document.getElementById(`step-label-${n}`);
            const icon   = document.getElementById(`step-icon-${n}`);

            if (n < step) {
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-green-500 border-green-500 text-white';
                icon.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                label.className = 'text-xs font-medium hidden sm:block transition-colors duration-300 text-green-500';
            } else if (n === step) {
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-blue-600 border-blue-600 text-white';
                icon.innerHTML = n;
                label.className = 'text-xs font-medium hidden sm:block transition-colors duration-300 text-blue-600 dark:text-blue-400';
            } else {
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-400 dark:text-gray-500';
                icon.innerHTML = n;
                label.className = 'text-xs font-medium hidden sm:block transition-colors duration-300 text-gray-400 dark:text-gray-500';
            }
        }

        const progress = ((step - 1) / (TOTAL_STEPS - 1)) * 100;
        document.getElementById('progressBar').style.width = `${progress}%`;

        currentStep = step;
    }

    // ══════════════════════════════════════════════
    // TOGGLE TIPO PRESUPUESTO (Donación / Comunidad)
    // ══════════════════════════════════════════════
    function toggleTipoPresupuesto() {
        const checked = document.getElementById('pres_es_donacion').checked;
        const container = document.getElementById('cooperante-container');
        const label = document.getElementById('pres_monto_label');
        const hint = document.getElementById('pres_tipo_hint');

        if (checked) {
            container.classList.remove('hidden');
            label.textContent = 'Monto del Financiador';
            hint.textContent = 'Actualmente: Donación de cooperante';
        } else {
            container.classList.add('hidden');
            document.getElementById('pres_cooperante').value = '';
            label.textContent = 'Monto de la Comunidad';
            hint.textContent = 'Actualmente: Financiado por el patronato (comunidad)';
        }
    }

    // ══════════════════════════════════════════════
    // PRESUPUESTO ACCUMULATION (Step 3)
    // ══════════════════════════════════════════════
    function capturarPresupuestoActual() {
        const esDonacion = document.getElementById('pres_es_donacion').checked;

        return {
            anio_presupuesto:       document.getElementById('pres_anio').value,
            presupuesto_total:      '0', // Se recalcula al agregar detalles
            monto_financiador:      '0',
            monto_comunidad:        '0',
            porcentaje_financiador: esDonacion ? '100' : '0',
            porcentaje_comunidad:   esDonacion ? '0' : '100',
            estado:                 'Activo',
            fecha_aprobacion:       document.getElementById('pres_fecha_aprobacion').value,
            es_donacion:            esDonacion ? '1' : '0',
            id_cooperante:          document.getElementById('pres_cooperante').value,
            detalles:               []
        };
    }

    function limpiarFormPresupuesto() {
        document.getElementById('pres_anio').value = '';
        document.getElementById('pres_monto').value = '';
        document.getElementById('pres_estado').value = '';
        document.getElementById('pres_fecha_aprobacion').value = '';
        document.getElementById('pres_es_donacion').checked = false;
        document.getElementById('pres_cooperante').value = '';
        document.getElementById('cooperante-container').classList.add('hidden');
        document.getElementById('pres_monto_label').textContent = 'Monto de la Comunidad';
        document.getElementById('pres_tipo_hint').textContent = 'Actualmente: Financiado por el patronato (comunidad)';
    }

    function agregarPresupuesto(agregarOtro) {
        const data = capturarPresupuestoActual();
        presupuestos.push(data);
        renderResumenPresupuestos();
        generarHiddenInputsPresupuestos();

        if (agregarOtro) {
            limpiarFormPresupuesto();
        } else {
            goToStep(4);
        }
    }

    function eliminarPresupuesto(index) {
        presupuestos.splice(index, 1);
        renderResumenPresupuestos();
        generarHiddenInputsPresupuestos();
    }

    function renderResumenPresupuestos() {
        const container = document.getElementById('presupuestos-resumen');
        const body = document.getElementById('presupuestos-resumen-body');
        body.innerHTML = '';

        if (presupuestos.length === 0) {
            container.classList.add('hidden');
            return;
        }
        container.classList.remove('hidden');

        presupuestos.forEach((p, i) => {
            const tipo = p.es_donacion === '1' ? 'Donación' : 'Comunidad';
            const cooperanteLabel = p.es_donacion === '1' && p.id_cooperante ? (cooperantesMap[p.id_cooperante] || '-') : '-';
            const tr = document.createElement('tr');
            tr.className = 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300';
            tr.innerHTML = `
                <td class="px-4 py-2 font-medium">${i + 1}</td>
                <td class="px-4 py-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${p.es_donacion === '1' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'}">
                        ${tipo}
                    </span>
                </td>
                <td class="px-4 py-2">${p.anio_presupuesto || '-'}</td>
                <td class="px-4 py-2">${p.presupuesto_total ? parseFloat(p.presupuesto_total).toLocaleString('es-HN', {minimumFractionDigits:2}) : '-'}</td>
                <td class="px-4 py-2">${cooperanteLabel}</td>
                <td class="px-4 py-2">
                    <button type="button" onclick="eliminarPresupuesto(${i})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs font-medium">
                        Eliminar
                    </button>
                </td>
            `;
            body.appendChild(tr);
        });
    }

    function recalcularTotalesPresupuesto(presIdx) {
        const p = presupuestos[presIdx];
        const total = p.detalles.reduce((sum, d) => sum + (parseFloat(d.total) || 0), 0);
        
        p.presupuesto_total = total.toFixed(2);

        if (p.es_donacion === '1') {
            p.monto_financiador = total.toFixed(2);
            p.monto_comunidad   = '0';
        } else {
            p.monto_comunidad   = total.toFixed(2);
            p.monto_financiador = '0';
        }

        // Actualizar el resumen de presupuestos si está visible
        renderResumenPresupuestos();
    }

    // ══════════════════════════════════════════════
    // HIDDEN INPUTS GENERATION
    // ══════════════════════════════════════════════
    function generarHiddenInputsPresupuestos() {
        const container = document.getElementById('presupuestos-hidden-container');
        container.innerHTML = '';

        presupuestos.forEach((p, i) => {
            const fields = ['anio_presupuesto','presupuesto_total','monto_financiador','monto_comunidad','porcentaje_financiador','porcentaje_comunidad','estado','fecha_aprobacion','es_donacion','id_cooperante'];
            fields.forEach(field => {
                if (p[field] !== '' && p[field] !== null && p[field] !== undefined) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `presupuestos[${i}][${field}]`;
                    input.value = p[field];
                    container.appendChild(input);
                }
            });

            // Detalles
            if (p.detalles && p.detalles.length > 0) {
                p.detalles.forEach((d, j) => {
                    const detFields = ['nombre','cantidad','unidad_medida','precio_unitario','total','observaciones'];
                    detFields.forEach(field => {
                        if (d[field] !== '' && d[field] !== null && d[field] !== undefined) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `presupuestos[${i}][detalles][${j}][${field}]`;
                            input.value = d[field];
                            container.appendChild(input);
                        }
                    });
                });
            }
        });
    }

    // ══════════════════════════════════════════════
    // DETALLE DE PRESUPUESTO (Step 4)
    // ══════════════════════════════════════════════
    function popularSelectorPresupuestos() {
        const select = document.getElementById('detalle_presupuesto_selector');
        select.innerHTML = '';
        presupuestos.forEach((p, i) => {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = `Presupuesto #${i + 1} — Año: ${p.anio_presupuesto || 'N/A'} — Total: ${p.presupuesto_total || 'N/A'}`;
            select.appendChild(opt);
        });
        cargarDetallesPresupuesto();
    }

    function cargarDetallesPresupuesto() {
        const idx = document.getElementById('detalle_presupuesto_selector').value;
        if (idx === '' || !presupuestos[idx]) return;
        renderResumenDetalles(parseInt(idx));
    }

    function calcularTotalDetalle() {
        const cantidad = parseFloat(document.getElementById('det_cantidad').value) || 0;
        const precio   = parseFloat(document.getElementById('det_precio').value) || 0;
        document.getElementById('det_total').value = (cantidad * precio).toFixed(2);
    }

    function capturarDetalleActual() {
        return {
            nombre:          document.getElementById('det_nombre').value,
            cantidad:        document.getElementById('det_cantidad').value,
            unidad_medida:   document.getElementById('det_unidad').value,
            precio_unitario: document.getElementById('det_precio').value,
            total:           document.getElementById('det_total').value,
            observaciones:   document.getElementById('det_observaciones').value,
        };
    }

    function limpiarFormDetalle() {
        document.getElementById('det_nombre').value = '';
        document.getElementById('det_cantidad').value = '';
        document.getElementById('det_unidad').value = '';
        document.getElementById('det_precio').value = '';
        document.getElementById('det_total').value = '';
        document.getElementById('det_observaciones').value = '';
    }

    function agregarDetalle(agregarOtro) {
        const presIdx = parseInt(document.getElementById('detalle_presupuesto_selector').value);
        if (isNaN(presIdx) || !presupuestos[presIdx]) return;

        const detalle = capturarDetalleActual();
        presupuestos[presIdx].detalles.push(detalle);

        // Recalcular totales del presupuesto
        recalcularTotalesPresupuesto(presIdx);

        generarHiddenInputsPresupuestos();
        renderResumenDetalles(presIdx);

        if (agregarOtro) {
            limpiarFormDetalle();
        }
    }

    function eliminarDetalle(presIdx, detIdx) {
        presupuestos[presIdx].detalles.splice(detIdx, 1);
        recalcularTotalesPresupuesto(presIdx);
        generarHiddenInputsPresupuestos();
        renderResumenDetalles(presIdx);
    }

    function renderResumenDetalles(presIdx) {
        const container = document.getElementById('detalles-resumen');
        const body = document.getElementById('detalles-resumen-body');
        body.innerHTML = '';

        const detalles = presupuestos[presIdx]?.detalles || [];
        if (detalles.length === 0) {
            container.classList.add('hidden');
            return;
        }
        container.classList.remove('hidden');

        detalles.forEach((d, j) => {
            const tr = document.createElement('tr');
            tr.className = 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300';
            tr.innerHTML = `
                <td class="px-4 py-2 font-medium">${j + 1}</td>
                <td class="px-4 py-2">${d.nombre || '-'}</td>
                <td class="px-4 py-2">${d.cantidad || '-'}</td>
                <td class="px-4 py-2">${d.unidad_medida || '-'}</td>
                <td class="px-4 py-2">${d.precio_unitario ? parseFloat(d.precio_unitario).toLocaleString('es-HN', {minimumFractionDigits:2}) : '-'}</td>
                <td class="px-4 py-2">${d.total ? parseFloat(d.total).toLocaleString('es-HN', {minimumFractionDigits:2}) : '-'}</td>
                <td class="px-4 py-2">
                    <button type="button" onclick="eliminarDetalle(${presIdx}, ${j})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs font-medium">
                        Eliminar
                    </button>
                </td>
            `;
            body.appendChild(tr);
        });
    }

    // ══════════════════════════════════════════════
    // ERROR HANDLING
    // ══════════════════════════════════════════════
    @if($errors->any())
        document.getElementById('step-1').classList.remove('hidden');
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-3').classList.add('hidden');
        document.getElementById('step-4').classList.add('hidden');
    @endif
</script>
@endsection