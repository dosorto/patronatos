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

        {{-- Contenedor de hidden inputs para detalles acumulados --}}
        <div id="detalles-hidden-container"></div>

        {{-- Steps Indicator --}}
        <div class="mb-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 right-0 top-4 h-0.5 bg-gray-200 dark:bg-gray-700 z-0"></div>
                <div class="absolute left-0 top-4 h-0.5 bg-blue-600 z-0 transition-all duration-500" id="progressBar" style="width: 0%"></div>

                @foreach([1 => 'Información General', 2 => 'Beneficiarios', 3 => 'Presupuesto Proyecto'] as $num => $label)
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
                        <label for="tipo_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Proyecto *</label>
                        <select name="tipo_proyecto" id="tipo_proyecto" required
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
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción *</label>
                        <textarea name="descripcion" id="descripcion" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Justificación --}}
                    <div class="md:col-span-2">
                        <label for="justificacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Justificación *</label>
                        <textarea name="justificacion" id="justificacion" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('justificacion') border-red-500 @enderror">{{ old('justificacion') }}</textarea>
                        @error('justificacion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('proyecto.index') }}"
                       class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                        Cancelar
                    </a>
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
                        <label for="descripcion_beneficiarios" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción de Beneficiarios *</label>
                        <textarea name="descripcion_beneficiarios" id="descripcion_beneficiarios" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descripcion_beneficiarios') border-red-500 @enderror">{{ old('descripcion_beneficiarios') }}</textarea>
                        @error('descripcion_beneficiarios')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hombres --}}
                    <div>
                        <label for="benef_hombres" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hombres *</label>
                        <input type="number" name="benef_hombres" id="benef_hombres" value="{{ old('benef_hombres', 0) }}" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_hombres') border-red-500 @enderror">
                        @error('benef_hombres')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mujeres --}}
                    <div>
                        <label for="benef_mujeres" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mujeres *</label>
                        <input type="number" name="benef_mujeres" id="benef_mujeres" value="{{ old('benef_mujeres', 0) }}" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_mujeres') border-red-500 @enderror">
                        @error('benef_mujeres')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Niños --}}
                    <div>
                        <label for="benef_ninos" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Niños *</label>
                        <input type="number" name="benef_ninos" id="benef_ninos" value="{{ old('benef_ninos', 0) }}" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_ninos') border-red-500 @enderror">
                        @error('benef_ninos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Familias --}}
                    <div>
                        <label for="benef_familias" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Familias *</label>
                        <input type="number" name="benef_familias" id="benef_familias" value="{{ old('benef_familias', 0) }}" min="0" required
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

            {{-- Step 3: Presupuesto Proyecto --}}
            <div id="step-3" class="hidden">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Presupuesto Proyecto
                </h2>

                {{-- Resumen de Presupuesto (Sólo Lectura) --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-1">Año</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ now()->year }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-1">Presupuesto Total</p>
                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">L. <span id="resumen-total">0.00</span></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-1">Monto Comunidad</p>
                        <p class="text-lg font-bold text-green-600 dark:text-green-400">L. <span id="resumen-comunidad">0.00</span> (<span id="pct-comunidad">0</span>%)</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-1">Monto Financiadores</p>
                        <p class="text-lg font-bold text-purple-600 dark:text-purple-400">L. <span id="resumen-financiador">0.00</span> (<span id="pct-financiador">0</span>%)</p>
                    </div>
                </div>

                {{-- Toolbar y Opciones --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <button type="button" onclick="mostrarFormularioDetalle()"
                            class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Guardar Proyecto
                    </button>
                </div>

                {{-- Tabla Central de Detalles --}}
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-3 py-2">N#</th>
                                <th class="px-3 py-2 w-1/3">Concepto</th>
                                <th class="px-3 py-2">Medida</th>
                                <th class="px-3 py-2">Cant</th>
                                <th class="px-3 py-2">Precio</th>
                                <th class="px-3 py-2">Total</th>
                                <th class="px-3 py-2">Cooperante</th>
                                <th class="px-3 py-2 text-center">X</th>
                            </tr>
                        </thead>
                        <tbody id="detalles-resumen-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                            {{-- Aquí se dibujarán los detalles insertados --}}
                        </tbody>
                    </table>
                    <div id="empty-state-detalles" class="p-6 text-center text-gray-500 dark:text-gray-400">
                        No hay detalles presupuestarios registrados.
                    </div>
                </div>

                {{-- Contenedor del Formulario Embebido (Pasado a Modal) --}}
                <div id="detalle-form-modal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl mx-4 relative border border-gray-200 dark:border-gray-700">
                        {{-- Header Modal --}}
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white" id="form-detalle-title">Nuevo Detalle de Presupuesto</h3>
                            <button type="button" onclick="cancelarFormularioDetalle()" class="text-gray-400 hover:text-red-500 rounded-lg p-1 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        {{-- Body Modal --}}
                        <div class="p-6">
                            {{-- Contenedor Errores JS --}}
                            <div id="js-error-container" class="hidden mb-4 p-3 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-sm rounded-lg border border-red-200 dark:border-red-800"></div>                   

                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4" id="form-detalle-title">Nuevo Detalle de Presupuesto</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        {{-- Nombre / Concepto --}}
                        <div class="md:col-span-2 lg:col-span-3">
                            <label for="det_nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Concepto *</label>
                            <input type="text" id="det_nombre" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        {{-- Unidad de Medida --}}
                        <div>
                            <label for="det_unidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medida *</label>
                            <select id="det_unidad" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">-- --</option>
                                @foreach($unidadesMedida as $unidad)
                                    <option value="{{ $unidad }}">{{ $unidad }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Cantidad --}}
                        <div>
                            <label for="det_cantidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cantidad *</label>
                            <input type="number" id="det_cantidad" step="0.01" min="0" oninput="calcularTotalDetalle()" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        {{-- Precio Unitario --}}
                        <div>
                            <label for="det_precio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Precio Unit. *</label>
                            <input type="number" id="det_precio" step="0.01" min="0" oninput="calcularTotalDetalle()" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        {{-- Total --}}
                        <div>
                            <label for="det_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total</label>
                            <input type="number" id="det_total" step="0.01" min="0" readonly class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold">
                        </div>

                        {{-- Switch Es Donación --}}
                        <div class="flex items-center pt-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" id="det_es_donacion" onchange="toggleCooperanteSelector()" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">¿Es donación?</span>
                            </label>
                        </div>

                        {{-- Selector de Cooperante --}}
                        <div id="cooperante-container" class="hidden">
                            <label for="det_cooperante" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cooperante</label>
                            <select id="det_cooperante" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">-- Seleccionar --</option>
                                @foreach($cooperantes as $cooperante)
                                    <option value="{{ $cooperante->id_cooperante }}">{{ $cooperante->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" onclick="cancelarFormularioDetalle()" class="px-5 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                                Cancelar
                            </button>
                            <button type="button" id="btn-guardar-detalle" onclick="procesarDetalleUI()" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 shadow-md transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Guardar Detalle
                            </button>
                        </div>
                        </div> {{-- End padding --}}
                    </div> {{-- End Modal Box --}}
                </div> {{-- End Modal Overlay --}}

                {{-- Modal Confirmación Eliminar --}}
                <div id="delete-confirm-modal" class="fixed inset-0 z-[60] hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm mx-4 transform transition-all">
                        <div class="p-6 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">¿Está seguro de quitar este detalle?</h3>
                            <button id="btn-confirm-delete" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                Sí, estoy seguro
                            </button>
                            <button onclick="closeDeleteModal()" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                No, cancelar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Navegación Inferior --}}
                <div class="mt-8 flex justify-start">
                    <button type="button" onclick="goToStep(2)"
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Anterior
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    const TOTAL_STEPS = 3;
    let currentStep = 1;
    let detalles = []; 
    let editingIndex = null;

    // ── Cooperantes data para referencia en labels ──
    const cooperantesMap = {
        @foreach($cooperantes as $c)
            '{{ $c->id_cooperante }}': '{{ $c->nombre }}',
        @endforeach
    };

    @if($errors->has('nombre_proyecto'))
        currentStep = 1;
    @endif

    // ══════════════════════════════════════════════
    // WIZARD NAVIGATION
    // ══════════════════════════════════════════════
    function goToStep(step) {
        if (step > currentStep) {
            let isValid = true;
            let firstInvalidField = null;
            const currentStepEl = document.getElementById(`step-${currentStep}`);
            // Evaluar los campos requeridos explícitamente y al nombre de proyecto por ID
            const requiredFields = currentStepEl.querySelectorAll('input[required], select[required], textarea[required], #nombre_proyecto');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = field;
                    
                    field.classList.add('border-red-500');
                    let msg = field.parentNode.querySelector('.js-error-msg');
                    if (!msg) {
                        msg = document.createElement('p');
                        msg.className = 'mt-1 text-sm text-red-600 dark:text-red-400 js-error-msg';
                        msg.textContent = 'Este campo es obligatorio.';
                        field.parentNode.appendChild(msg);
                    }
                } else {
                    field.classList.remove('border-red-500');
                    const msg = field.parentNode.querySelector('.js-error-msg');
                    if (msg) msg.remove();
                }
            });

            if (!isValid) {
                if (firstInvalidField) firstInvalidField.focus();
                return;
            }
        }

        document.getElementById(`step-${currentStep}`).classList.add('hidden');
        document.getElementById(`step-${step}`).classList.remove('hidden');

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
    // FLAT BUDGET (DETALLES) LOGIC
    // ══════════════════════════════════════════════
    function formatCurrency(val) {
        return parseFloat(val).toLocaleString('es-HN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function calcularTotalDetalle() {
        const cantidad = parseFloat(document.getElementById('det_cantidad').value) || 0;
        const precio   = parseFloat(document.getElementById('det_precio').value) || 0;
        document.getElementById('det_total').value = (cantidad * precio).toFixed(2);
    }

    function toggleCooperanteSelector() {
        const isDonation = document.getElementById('det_es_donacion').checked;
        const container = document.getElementById('cooperante-container');
        if (isDonation) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
            document.getElementById('det_cooperante').value = '';
        }
    }

    function mostrarFormularioDetalle(index = null) {
        const modal = document.getElementById('detalle-form-modal');
        const title = document.getElementById('form-detalle-title');
        const btn = document.getElementById('btn-guardar-detalle');
        const errorContainer = document.getElementById('js-error-container');
        
        errorContainer.classList.add('hidden');
        errorContainer.innerHTML = '';
        
        modal.classList.remove('hidden');

        if (index !== null) {
            // Editing state
            editingIndex = index;
            title.textContent = "Editar Detalle de Presupuesto";
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Guardar Cambios`;
            
            const d = detalles[index];
            document.getElementById('det_nombre').value = d.nombre || '';
            document.getElementById('det_unidad').value = d.unidad_medida || '';
            document.getElementById('det_cantidad').value = d.cantidad || '';
            document.getElementById('det_precio').value = d.precio_unitario || '';
            document.getElementById('det_total').value = d.total || '';
            document.getElementById('det_es_donacion').checked = d.es_donacion === '1' || d.es_donacion === true;
            document.getElementById('det_cooperante').value = d.id_cooperante || '';
            toggleCooperanteSelector();
        } else {
            // New state
            editingIndex = null;
            title.textContent = "Nuevo Detalle de Presupuesto";
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Agregar Detalle`;
            limpiarFormDetalle();
        }
    }

    function cancelarFormularioDetalle() {
        document.getElementById('detalle-form-modal').classList.add('hidden');
        limpiarFormDetalle();
        editingIndex = null;
    }

    function limpiarFormDetalle() {
        document.getElementById('det_nombre').value = '';
        document.getElementById('det_unidad').value = '';
        document.getElementById('det_cantidad').value = '';
        document.getElementById('det_precio').value = '';
        document.getElementById('det_total').value = '';
        document.getElementById('det_es_donacion').checked = false;
        document.getElementById('det_cooperante').value = '';
        document.getElementById('js-error-container').classList.add('hidden');
        
        // Remove error borders
        document.querySelectorAll('#detalle-form-modal input, #detalle-form-modal select').forEach(el => {
            el.classList.remove('border-red-500', 'ring-red-500');
        });
        
        toggleCooperanteSelector();
    }

    function procesarDetalleUI() {
        const errorContainer = document.getElementById('js-error-container');
        errorContainer.classList.add('hidden');
        errorContainer.innerHTML = '';
        
        // Remove old error borders
        document.querySelectorAll('#detalle-form-modal input, #detalle-form-modal select').forEach(el => {
            el.classList.remove('border-red-500', 'ring-red-500');
        });

        const nombreInput = document.getElementById('det_nombre');
        const cantidadInput = document.getElementById('det_cantidad');
        const precioInput = document.getElementById('det_precio');
        
        const nombre = nombreInput.value.trim();
        const cantidad = cantidadInput.value;
        const precio = precioInput.value;
        const total = document.getElementById('det_total').value;

        let errores = [];

        if(!nombre) {
            errores.push("El Concepto es obligatorio.");
            nombreInput.classList.add('border-red-500', 'ring-red-500');
        }
        if(!cantidad || cantidad <= 0) {
            errores.push("La Cantidad debe ser mayor a 0.");
            cantidadInput.classList.add('border-red-500', 'ring-red-500');
        }
        if(!precio || precio < 0) {
            errores.push("El Precio Unitario es obligatorio y no puede ser negativo.");
            precioInput.classList.add('border-red-500', 'ring-red-500');
        }

        const isDonation = document.getElementById('det_es_donacion').checked;
        const cooperanteInput = document.getElementById('det_cooperante');
        const cooperante = cooperanteInput.value;

        if (isDonation && !cooperante) {
            errores.push("Debe seleccionar un Cooperante si es una donación.");
            cooperanteInput.classList.add('border-red-500', 'ring-red-500');
        }

        if (errores.length > 0) {
            errorContainer.innerHTML = `<ul class="list-disc list-inside">${errores.map(e => `<li>${e}</li>`).join('')}</ul>`;
            errorContainer.classList.remove('hidden');
            return;
        }

        const detObj = {
            nombre: nombre,
            unidad_medida: document.getElementById('det_unidad').value,
            cantidad: cantidad,
            precio_unitario: precio,
            total: total,
            es_donacion: isDonation ? '1' : '0',
            id_cooperante: isDonation ? cooperante : null
        };

        if (editingIndex !== null) {
            detalles[editingIndex] = detObj;
        } else {
            detalles.push(detObj);
        }

        renderTablaDetalles();
        generarHiddenInputsDetalles();
        cancelarFormularioDetalle();
    }

    let deleteIndexTarget = null;

    function eliminarDetalle(index) {
        deleteIndexTarget = index;
        document.getElementById('delete-confirm-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-confirm-modal').classList.add('hidden');
        deleteIndexTarget = null;
    }

    document.getElementById('btn-confirm-delete').addEventListener('click', function() {
        if (deleteIndexTarget !== null) {
            detalles.splice(deleteIndexTarget, 1);
            renderTablaDetalles();
            generarHiddenInputsDetalles();
            closeDeleteModal();
        }
    });

    function actualizarResumen(total, comunidad, financiador) {
        document.getElementById('resumen-total').textContent = formatCurrency(total);
        document.getElementById('resumen-comunidad').textContent = formatCurrency(comunidad);
        document.getElementById('resumen-financiador').textContent = formatCurrency(financiador);
        
        const pctCom = total > 0 ? ((comunidad / total) * 100).toFixed(2) : 0;
        const pctFin = total > 0 ? ((financiador / total) * 100).toFixed(2) : 0;
        
        document.getElementById('pct-comunidad').textContent = pctCom;
        document.getElementById('pct-financiador').textContent = pctFin;
    }

    function renderTablaDetalles() {
        const body = document.getElementById('detalles-resumen-body');
        const emptyState = document.getElementById('empty-state-detalles');
        let granTotal = 0;
        let totalComunidad = 0;
        let totalFinanciador = 0;

        body.innerHTML = '';

        if (detalles.length === 0) {
            emptyState.classList.remove('hidden');
            actualizarResumen(0, 0, 0);
            return;
        }

        emptyState.classList.add('hidden');

        detalles.forEach((d, i) => {
            const lineaTotal = parseFloat(d.total || 0);
            granTotal += lineaTotal;
            if (d.es_donacion === '1' || d.es_donacion === true) {
                totalFinanciador += lineaTotal;
            } else {
                totalComunidad += lineaTotal;
            }

            const cooperanteStr = (d.es_donacion === '1' && d.id_cooperante) ? (cooperantesMap[d.id_cooperante] || 'S/N') : '-';

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer';
            tr.innerHTML = `
                <td class="px-3 py-2" onclick="mostrarFormularioDetalle(${i})">${i + 1}</td>
                <td class="px-3 py-2 w-1/3 truncate font-medium text-gray-800 dark:text-gray-200" onclick="mostrarFormularioDetalle(${i})">${d.nombre}</td>
                <td class="px-3 py-2 text-gray-600 dark:text-gray-400" onclick="mostrarFormularioDetalle(${i})">${d.unidad_medida || '-'}</td>
                <td class="px-3 py-2 text-gray-600 dark:text-gray-400" onclick="mostrarFormularioDetalle(${i})">${d.cantidad}</td>
                <td class="px-3 py-2 text-gray-600 dark:text-gray-400" onclick="mostrarFormularioDetalle(${i})">L. ${formatCurrency(d.precio_unitario)}</td>
                <td class="px-3 py-2 font-semibold text-gray-800 dark:text-gray-200" onclick="mostrarFormularioDetalle(${i})">L. ${formatCurrency(d.total)}</td>
                <td class="px-3 py-2" onclick="mostrarFormularioDetalle(${i})">
                    <span class="inline-block px-2 text-xs font-semibold rounded ${d.es_donacion === '1' ? 'bg-purple-100 text-purple-800' : 'text-gray-500'}">
                        ${cooperanteStr}
                    </span>
                </td>
                <td class="px-3 py-2 text-center">
                    <button type="button" onclick="eliminarDetalle(${i})" class="text-red-500 hover:text-red-700 p-1">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            `;
            body.appendChild(tr);
        });

        actualizarResumen(granTotal, totalComunidad, totalFinanciador);
    }

    function generarHiddenInputsDetalles() {
        const container = document.getElementById('detalles-hidden-container');
        container.innerHTML = '';

        detalles.forEach((d, i) => {
            const fields = ['nombre', 'cantidad', 'unidad_medida', 'precio_unitario', 'total', 'es_donacion', 'id_cooperante'];
            fields.forEach(field => {
                if (d[field] !== '' && d[field] !== null && d[field] !== undefined) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `detalles[${i}][${field}]`;
                    input.value = d[field];
                    container.appendChild(input);
                }
            });
        });
    }

    // ══════════════════════════════════════════════
    // ERROR HANDLING
    // ══════════════════════════════════════════════
    @if($errors->any())
        document.getElementById('step-1').classList.remove('hidden');
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-3').classList.add('hidden');
    @endif
</script>
@endsection