@extends('layouts.app')

@section('title', 'Editar Proyecto')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Proyecto</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Modifica los datos del proyecto</p>
    </div>

    <form action="{{ route('proyecto.update', $proyecto) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">

            {{-- Información General --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">Información General</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nombre del Proyecto --}}
                    <div class="md:col-span-2">
                        <label for="nombre_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre del Proyecto *</label>
                        <input type="text" name="nombre_proyecto" id="nombre_proyecto" value="{{ old('nombre_proyecto', $proyecto->nombre_proyecto) }}" required
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
                                <option value="{{ $tipo }}" {{ old('tipo_proyecto', $proyecto->tipo_proyecto) == $tipo ? 'selected' : '' }}>
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
                        <input type="text" name="numero_acta" id="numero_acta" value="{{ old('numero_acta', $proyecto->numero_acta) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('numero_acta') border-red-500 @enderror">
                        @error('numero_acta')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha Aprobación Asamblea --}}
                    <div>
                        <label for="fecha_aprobacion_asamblea" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Aprobación Asamblea</label>
                        <input type="date" name="fecha_aprobacion_asamblea" id="fecha_aprobacion_asamblea"
                               value="{{ old('fecha_aprobacion_asamblea', $proyecto->fecha_aprobacion_asamblea?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_aprobacion_asamblea') border-red-500 @enderror">
                        @error('fecha_aprobacion_asamblea')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha Inicio --}}
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio"
                               value="{{ old('fecha_inicio', $proyecto->fecha_inicio?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_inicio') border-red-500 @enderror">
                        @error('fecha_inicio')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha Fin --}}
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin"
                               value="{{ old('fecha_fin', $proyecto->fecha_fin?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_fin') border-red-500 @enderror">
                        @error('fecha_fin')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado *</label>
                        <select name="estado" id="estado" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('estado') border-red-500 @enderror">
                            <option value="1" {{ old('estado', $proyecto->getRawOriginal('estado')) == "1" ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estado', $proyecto->getRawOriginal('estado')) == "0" ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Responsable (solo lectura) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Responsable del Proyecto</label>
                        <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            {{ $proyecto->miembroResponsable?->miembro->persona->nombre_completo ?? 'N/A' }}
                            @if($proyecto->miembroResponsable?->cargo)
                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $proyecto->miembroResponsable->cargo }})</span>
                            @endif
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Justificación --}}
                    <div class="md:col-span-2">
                        <label for="justificacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Justificación</label>
                        <textarea name="justificacion" id="justificacion" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('justificacion') border-red-500 @enderror">{{ old('justificacion', $proyecto->justificacion) }}</textarea>
                        @error('justificacion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Beneficiarios --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">Beneficiarios</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Descripción Beneficiarios --}}
                    <div class="md:col-span-2">
                        <label for="descripcion_beneficiarios" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción de Beneficiarios</label>
                        <textarea name="descripcion_beneficiarios" id="descripcion_beneficiarios" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descripcion_beneficiarios') border-red-500 @enderror">{{ old('descripcion_beneficiarios', $proyecto->descripcion_beneficiarios) }}</textarea>
                        @error('descripcion_beneficiarios')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hombres --}}
                    <div>
                        <label for="benef_hombres" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hombres</label>
                        <input type="number" name="benef_hombres" id="benef_hombres" value="{{ old('benef_hombres', $proyecto->benef_hombres) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_hombres') border-red-500 @enderror">
                        @error('benef_hombres')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mujeres --}}
                    <div>
                        <label for="benef_mujeres" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mujeres</label>
                        <input type="number" name="benef_mujeres" id="benef_mujeres" value="{{ old('benef_mujeres', $proyecto->benef_mujeres) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_mujeres') border-red-500 @enderror">
                        @error('benef_mujeres')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Niños --}}
                    <div>
                        <label for="benef_ninos" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Niños</label>
                        <input type="number" name="benef_ninos" id="benef_ninos" value="{{ old('benef_ninos', $proyecto->benef_ninos) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_ninos') border-red-500 @enderror">
                        @error('benef_ninos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Familias --}}
                    <div>
                        <label for="benef_familias" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Familias</label>
                        <input type="number" name="benef_familias" id="benef_familias" value="{{ old('benef_familias', $proyecto->benef_familias) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_familias') border-red-500 @enderror">
                        @error('benef_familias')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Presupuestos --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden relative" id="presupuestos-container">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">Presupuestos Anuales / Por Cooperante</h2>
                    <button type="button" onclick="agregarPresupuesto()" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-xs font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Agregar Presupuesto
                    </button>
                </div>
                
                <div class="p-6">
                    <div id="lista-presupuestos" class="space-y-8">
                        {{-- Presupuestos se inyectarán/renderizarán aquí --}}
                    </div>
                </div>
                
                {{-- Template HTML oculto para un NUEVO presupuesto --}}
                <template id="template-presupuesto">
                    <div class="presupuesto-item border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-800 relative shadow-sm" data-index="INDEX_TEMP">
                        <input type="hidden" name="presupuestos[INDEX_TEMP][id]" value="">
                        
                        {{-- Cabecera del presupuesto --}}
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 text-lg flex items-center gap-2">
                                <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold presupuesto-number">#</span>
                                Presupuesto <span class="anio-display ml-1 text-gray-500 dark:text-gray-400 font-normal"></span>
                            </h3>
                            <button type="button" onclick="promptEliminarPresupuesto(this)" class="text-red-500 hover:text-red-700 p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>

                        {{-- Datos Generales del Presupuesto --}}
                        <div class="p-5 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-4 flex items-center gap-4 py-2 border-b border-gray-100 dark:border-gray-700 mb-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="presupuestos[INDEX_TEMP][es_donacion]" value="1" onchange="toggleDonacion(this, 'INDEX_TEMP')" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 checkbox-donacion">
                                    <span class="text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600 dark:from-purple-400 dark:to-blue-400 uppercase tracking-wide">
                                        Es donación externa
                                    </span>
                                </label>
                            </div>

                            <div class="cooperante-field hidden md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cooperante *</label>
                                <select name="presupuestos[INDEX_TEMP][id_cooperante]" class="w-full text-sm px-3 py-2 border border-purple-300 dark:border-purple-800 rounded-lg focus:ring-purple-500 focus:border-purple-500 bg-purple-50 dark:bg-purple-900/10 dark:text-white select-cooperante">
                                    <option value="">-- Seleccionar Cooperante --</option>
                                    @foreach($cooperantes as $cooperante)
                                        <option value="{{ $cooperante->id_cooperante }}">{{ $cooperante->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Año</label>
                                <input type="number" name="presupuestos[INDEX_TEMP][anio_presupuesto]" placeholder="Ej. 2024" oninput="actualizarDisplayAnio(this)" class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Total Calculado</label>
                                <input type="text" readonly class="presupuesto-total-input w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-800 font-bold dark:text-white text-gray-600" value="L. 0.00">
                                <input type="hidden" name="presupuestos[INDEX_TEMP][presupuesto_total]" class="presupuesto-total-hidden" value="0">
                            </div>

                            <div class="financiador-monto-field hidden">
                                <label class="block text-xs font-medium text-purple-700 dark:text-purple-400 mb-1">Monto Financiador (L.)</label>
                                <input type="number" step="0.01" min="0" name="presupuestos[INDEX_TEMP][monto_financiador]" value="0" oninput="validarTotales(this, 'INDEX_TEMP')" class="w-full text-sm px-3 py-2 border border-purple-300 dark:border-purple-600 rounded-lg focus:ring-purple-500 dark:bg-gray-700 dark:text-white monto-financiador hidden-arrows">
                            </div>
                            
                            <div class="comunidad-monto-field">
                                <label class="block text-xs font-medium text-green-700 dark:text-green-400 mb-1">Monto Comunidad (L.)</label>
                                <input type="number" step="0.01" min="0" name="presupuestos[INDEX_TEMP][monto_comunidad]" value="0" oninput="validarTotales(this, 'INDEX_TEMP')" class="w-full text-sm px-3 py-2 border border-green-300 dark:border-green-600 rounded-lg focus:ring-green-500 dark:bg-gray-700 dark:text-white monto-comunidad hidden-arrows">
                            </div>

                            <div class="md:col-span-4 mt-1 text-xs text-red-500 font-semibold text-right hidden warning-totales">
                                ⚠ La suma de [Monto Financiador + Comunidad] no concuerda con la suma del detalle de rubros.
                            </div>
                        </div>

                        {{-- Tabla de Detalles --}}
                        <div class="bg-gray-50/50 dark:bg-gray-800/50 p-5 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Desglose de Rubros</h4>
                                <button type="button" onclick="agregarDetalle(this, 'INDEX_TEMP')" class="px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs rounded hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Fila
                                </button>
                            </div>
                            
                            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                        <tr>
                                            <th scope="col" class="px-3 py-2 w-1/3">Rubro / Descripción</th>
                                            <th scope="col" class="px-3 py-2 w-24">Cantidad</th>
                                            <th scope="col" class="px-3 py-2 w-24">Medida</th>
                                            <th scope="col" class="px-3 py-2 w-32">P. Unitario</th>
                                            <th scope="col" class="px-3 py-2 text-right">Total</th>
                                            <th scope="col" class="px-3 py-2 w-10 text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="detalles-tbody divide-y divide-gray-200 dark:divide-gray-700">
                                        {{-- Detalles se inyectan acá --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Template HTML oculto para un NUEVO Detalle (Fila) --}}
                <template id="template-detalle">
                    <tr class="bg-white dark:bg-gray-800 detalle-row" data-detalle-index="DETALLE_TEMP">
                        <input type="hidden" name="presupuestos[INDEX_TEMP][detalles][DETALLE_TEMP][id]" value="">
                        <td class="px-2 py-2">
                            <input type="text" name="presupuestos[INDEX_TEMP][detalles][DETALLE_TEMP][nombre]" placeholder="Ej. Ladrillos" class="w-full text-xs px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded bg-transparent dark:text-white" required>
                        </td>
                        <td class="px-2 py-2">
                            <input type="number" step="0.01" min="0" name="presupuestos[INDEX_TEMP][detalles][DETALLE_TEMP][cantidad]" value="1" oninput="calcularTotalFila(this)" class="input-cantidad w-full text-xs px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded bg-transparent dark:text-white hidden-arrows">
                        </td>
                        <td class="px-2 py-2">
                            <select name="presupuestos[INDEX_TEMP][detalles][DETALLE_TEMP][unidad_medida]" class="w-full text-xs px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded bg-transparent dark:text-white dark:bg-gray-800">
                                <option value="">--</option>
                                @foreach($unidadesMedida as $unidad)
                                    <option value="{{ $unidad }}">{{ $unidad }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-2 py-2">
                            <input type="number" step="0.01" min="0" name="presupuestos[INDEX_TEMP][detalles][DETALLE_TEMP][precio_unitario]" value="0" oninput="calcularTotalFila(this)" class="input-precio w-full text-xs px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded bg-transparent dark:text-white hidden-arrows">
                        </td>
                        <td class="px-2 py-2 text-right">
                            <span class="font-bold text-gray-900 dark:text-white text-xs total-fila-display">L. 0.00</span>
                            <input type="hidden" name="presupuestos[INDEX_TEMP][detalles][DETALLE_TEMP][total]" class="input-total" value="0">
                        </td>
                        <td class="px-2 py-2 text-center">
                            <button type="button" onclick="eliminarDetalle(this)" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/50 p-1 rounded transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </td>
                    </tr>
                </template>
            </div>

            {{-- Modal de Confirmación para borrar presupuesto entero --}}
            <div id="deletePresupuestoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80" aria-hidden="true" onclick="cerrarPromptEliminar()"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 anim-scale-in">
                        <div class="sm:flex sm:items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-title">
                                    Remover Presupuesto
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-300">
                                        ¿Estás seguro de que deseas quitar este presupuesto? Si guardas el proyecto con este cambio, se eliminará permanentemente de la base de datos perdiendo todos sus detalles anotados.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="button" onclick="confirmarEliminarPresupuesto()" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Sí, eliminar
                            </button>
                            <button type="button" onclick="cerrarPromptEliminar()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Botones --}}
        <div class="mt-6 flex justify-end gap-3 sticky bottom-4">
            <a href="{{ route('proyecto.index') }}"
               class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 shadow-lg">
                Cancelar
            </a>
            <button type="submit"
                    class="px-8 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-lg ring-4 ring-blue-600/30">
                Guardar Proyecto Completamente
            </button>
        </div>

    </form>
</div>

{{-- JSON DATA INJECTION PARA JAVASCRIPT --}}
<script>
    const presupuestosExistentes = @json($proyecto->presupuestos);
</script>

<style>
    /* Ocultar flechitas de input number */
    .hidden-arrows::-webkit-outer-spin-button,
    .hidden-arrows::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .hidden-arrows {
        -moz-appearance: textfield;
    }
    .anim-scale-in {
        animation: scaleIn 0.2s ease-out;
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>

<script>
    let presIdxCount = 0;
    let presupuestoPendienteEliminar = null;

    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar datos existentes
        if (presupuestosExistentes && presupuestosExistentes.length > 0) {
            presupuestosExistentes.forEach(presupuesto => {
                construirPresupuestoExistente(presupuesto);
            });
        }
        renumerarPresupuestos();
    });

    // ─────────────────────────────────────────────────────────────────
    // 1. MANEJO DE PRESUPUESTOS (BLOQUES)
    // ─────────────────────────────────────────────────────────────────
    
    function agregarPresupuesto() {
        const presCont = document.getElementById('lista-presupuestos');
        const template = document.getElementById('template-presupuesto').innerHTML;
        
        const indexStr = 'IDX_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
        let nuevoHtml = template.replace(/INDEX_TEMP/g, indexStr);
        
        const div = document.createElement('div');
        div.innerHTML = nuevoHtml;
        const newBlock = div.firstElementChild;
        // Animacion entrada
        newBlock.classList.add('anim-scale-in');
        
        presCont.appendChild(newBlock);
        
        // Agregarle una fila por defecto vacía para invitar a llenarla
        agregarDetalle(newBlock.querySelector('.detalles-tbody'), indexStr);
        
        renumerarPresupuestos();
        
        // Scroll suave al nuevo
        setTimeout(() => newBlock.scrollIntoView({ behavior: 'smooth', block: 'center' }), 50);
    }

    function construirPresupuestoExistente(data) {
        const presCont = document.getElementById('lista-presupuestos');
        const template = document.getElementById('template-presupuesto').innerHTML;
        const idx = data.id; // Usar el ID real como índice en formulario

        let html = template.replace(/INDEX_TEMP/g, idx);
        const div = document.createElement('div');
        div.innerHTML = html;
        const block = div.firstElementChild;
        
        // Rellenar cabecera e IDs
        block.querySelector(`input[name="presupuestos[${idx}][id]"]`).value = data.id;
        
        // Rellenar check (Es Donación)
        const checkDonacion = block.querySelector('.checkbox-donacion');
        if (data.es_donacion || data.es_donacion === 1) {
            checkDonacion.checked = true;
            toggleDonacion(checkDonacion, idx);
            // Settear cooperante solo despues que es visible
            const selectCoop = block.querySelector('.select-cooperante');
            if(data.id_cooperante) selectCoop.value = data.id_cooperante;
        }

        block.querySelector(`input[name="presupuestos[${idx}][anio_presupuesto]"]`).value = data.anio_presupuesto || '';
        block.querySelector(`input[name="presupuestos[${idx}][monto_financiador]"]`).value = data.monto_financiador || 0;
        block.querySelector(`input[name="presupuestos[${idx}][monto_comunidad]"]`).value = data.monto_comunidad || 0;
        block.querySelector('.anio-display').textContent = data.anio_presupuesto ? `(${data.anio_presupuesto})` : '';

        // Agregar los detalles
        const tbody = block.querySelector('.detalles-tbody');
        if (data.detalles && data.detalles.length > 0) {
            data.detalles.forEach(detalle => {
                construirDetalleExistente(tbody, idx, detalle);
            });
        }
        
        presCont.appendChild(block);
        
        // Recalcular el bloque
        setTimeout(() => calcularSumaDetallesPresupuesto(block), 10);
    }

    function toggleDonacion(checkbox, presIdx) {
        const block = checkbox.closest('.presupuesto-item');
        const coopSection = block.querySelector('.cooperante-field');
        const selectCoop = block.querySelector('.select-cooperante');
        const comunidadField = block.querySelector('.comunidad-monto-field');
        const financiadorField = block.querySelector('.financiador-monto-field');
        const comunidadInput = block.querySelector('.monto-comunidad');
        const financiadorInput = block.querySelector('.monto-financiador');

        if (checkbox.checked) {
            // Traspasar monto comunidad → financiador
            const montoActual = parseFloat(comunidadInput.value) || 0;
            financiadorInput.value = montoActual.toFixed(2);
            comunidadInput.value = 0;

            coopSection.classList.remove('hidden');
            selectCoop.setAttribute('required', 'required');
            financiadorField.classList.remove('hidden');
            comunidadField.classList.add('hidden');
        } else {
            // Traspasar monto financiador → comunidad
            const montoActual = parseFloat(financiadorInput.value) || 0;
            comunidadInput.value = montoActual.toFixed(2);
            financiadorInput.value = 0;

            coopSection.classList.add('hidden');
            selectCoop.removeAttribute('required');
            selectCoop.value = "";
            financiadorField.classList.add('hidden');
            comunidadField.classList.remove('hidden');
        }

        validarTotales(checkbox, presIdx);
    }

    function actualizarDisplayAnio(input) {
        const display = input.closest('.presupuesto-item').querySelector('.anio-display');
        display.textContent = input.value ? `(${input.value})` : '';
    }

    function renumerarPresupuestos() {
        const badges = document.querySelectorAll('.presupuesto-number');
        badges.forEach((badge, index) => {
            badge.textContent = index + 1;
        });
    }

    function promptEliminarPresupuesto(btn) {
        presupuestoPendienteEliminar = btn.closest('.presupuesto-item');
        document.getElementById('deletePresupuestoModal').classList.remove('hidden');
    }

    function cerrarPromptEliminar() {
        document.getElementById('deletePresupuestoModal').classList.add('hidden');
        presupuestoPendienteEliminar = null;
    }

    function confirmarEliminarPresupuesto() {
        if (presupuestoPendienteEliminar) {
            presupuestoPendienteEliminar.remove();
            renumerarPresupuestos();
        }
        cerrarPromptEliminar();
    }

    // ─────────────────────────────────────────────────────────────────
    // 2. MANEJO DE DETALLES (FILAS DE TABLA)
    // ─────────────────────────────────────────────────────────────────

    function agregarDetalle(elementOrTbody, presIdx) {
        // Element puede ser el botón (this) o el tbody si lo llamamos programaticamente
        let tbody = elementOrTbody.tagName === 'TBODY' ? elementOrTbody : elementOrTbody.closest('.presupuesto-item').querySelector('.detalles-tbody');
        
        const template = document.getElementById('template-detalle').innerHTML;
        const detIdxStr = 'DET_' + Date.now() + '_' + Math.random().toString(36).substr(2, 4);
        
        let html = template
            .replace(/INDEX_TEMP/g, presIdx)
            .replace(/DETALLE_TEMP/g, detIdxStr);
            
        const tempTable = document.createElement('table');
        tempTable.innerHTML = `<tbody>${html}</tbody>`;
        const newRow = tempTable.querySelector('tr');
        
        tbody.appendChild(newRow);
    }

    function construirDetalleExistente(tbody, presIdx, data) {
        const template = document.getElementById('template-detalle').innerHTML;
        const detIdxStr = data.id; // Su propio ID
        
        let html = template
            .replace(/INDEX_TEMP/g, presIdx)
            .replace(/DETALLE_TEMP/g, detIdxStr);
            
        const tempTable = document.createElement('table');
        tempTable.innerHTML = `<tbody>${html}</tbody>`;
        const row = tempTable.querySelector('tr');
        
        row.querySelector(`input[name="presupuestos[${presIdx}][detalles][${detIdxStr}][id]"]`).value = data.id;
        row.querySelector(`input[name="presupuestos[${presIdx}][detalles][${detIdxStr}][nombre]"]`).value = data.nombre || '';
        row.querySelector(`input[name="presupuestos[${presIdx}][detalles][${detIdxStr}][cantidad]"]`).value = data.cantidad || 0;
        const selectUnidad = row.querySelector(`select[name="presupuestos[${presIdx}][detalles][${detIdxStr}][unidad_medida]"]`);
        if (selectUnidad) selectUnidad.value = data.unidad_medida || '';
        row.querySelector(`input[name="presupuestos[${presIdx}][detalles][${detIdxStr}][precio_unitario]"]`).value = data.precio_unitario || 0;
        
        tbody.appendChild(row);
        
        // Recalcular sub-fila (precio x cant)
        calcularTotalFila(row.querySelector('.input-cantidad'));
    }

    function eliminarDetalle(btn) {
        const row = btn.closest('tr');
        const block = row.closest('.presupuesto-item');
        row.remove();
        calcularSumaDetallesPresupuesto(block); // recalcular total presupuesto
    }

    // ─────────────────────────────────────────────────────────────────
    // 3. CALCULOS Y VALIDACION DE MONTOS REACTIVOS
    // ─────────────────────────────────────────────────────────────────

    function calcularTotalFila(inputElement) {
        const row = inputElement.closest('tr');
        const cant = parseFloat(row.querySelector('.input-cantidad').value) || 0;
        const precio = parseFloat(row.querySelector('.input-precio').value) || 0;
        
        const total = cant * precio;
        
        // Formatear a HTML y setting hidden input
        row.querySelector('.total-fila-display').textContent = 'L. ' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        row.querySelector('.input-total').value = total.toFixed(2);

        // Desencadenar suma de todo el presupuesto
        const block = row.closest('.presupuesto-item');
        calcularSumaDetallesPresupuesto(block);
    }
    
    function calcularSumaDetallesPresupuesto(block) {
        const inputsTotal = block.querySelectorAll('.input-total');
        let sumaTotal = 0;
        inputsTotal.forEach(input => {
            sumaTotal += parseFloat(input.value) || 0;
        });

        block.querySelector('.presupuesto-total-input').value = 'L. ' + sumaTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        block.querySelector('.presupuesto-total-hidden').value = sumaTotal.toFixed(2);

        const esDonacion = block.querySelector('.checkbox-donacion').checked;

        if (esDonacion) {
            // Actualizar financiador
            block.querySelector('.monto-financiador').value = sumaTotal.toFixed(2);
        } else {
            // Actualizar comunidad
            block.querySelector('.monto-comunidad').value = sumaTotal.toFixed(2);
        }

        validarTotales(block.querySelector('.monto-financiador'), null);
    }

    function validarTotales(elementHtmlOrigen, optIndex) {
        const block = elementHtmlOrigen.closest('.presupuesto-item');
        
        const cFin = parseFloat(block.querySelector('.monto-financiador').value) || 0;
        const cCom = parseFloat(block.querySelector('.monto-comunidad').value) || 0;
        const subTotalCabecera = cFin + cCom;

        const totalCalculadoDetalle = parseFloat(block.querySelector('.presupuesto-total-hidden').value) || 0;
        const warningSign = block.querySelector('.warning-totales');

        // Umbral de 0.05 Lempiras (centavos) para diferencias decimales flotantes invisibles
        if (Math.abs(subTotalCabecera - totalCalculadoDetalle) > 0.05) {
            warningSign.classList.remove('hidden');
        } else {
            warningSign.classList.add('hidden');
        }
    }
</script>
@endsection