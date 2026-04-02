@extends(request()->boolean('wizard') ? 'layouts.app' : 'layouts.app')

@section('title', 'Asignación de Directiva')

@section('content')
<div class="container-fluid max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Gestión de Directiva</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">Asigna los cargos de la junta directiva en una sola vista.</p>
        </div>
        <a href="{{ route('directiva.index') }}{{ request()->boolean('wizard') ? '?wizard=1' : '' }}"
        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm active:scale-95">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver
        </a>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl flex items-center shadow-lg animate-pulse">
        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-bold">{{ session('error') }}</span>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl flex items-center shadow-lg">
        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('directiva.store') }}" method="POST" id="directivaForm" class="space-y-8">
        @csrf
        @if(request()->boolean('wizard'))
            <input type="hidden" name="wizard" value="1">
        @endif

        {{-- Periodo Global --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Periodo de la Directiva
                </h2>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="relative group">
                        <label for="fecha_inicio" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors group-focus-within:text-blue-600">Fecha de Inicio *</label>
                        <div class="relative">
                            <input type="date" name="fecha_inicio" id="fecha_inicio" required
                                   value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}"
                                   class="block w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 dark:text-white @error('fecha_inicio') border-red-500 @enderror">
                        </div>
                        @error('fecha_inicio') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="relative group">
                        <label for="fecha_fin" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors group-focus-within:text-blue-600">Fecha de Finalización *</label>
                        <div class="relative">
                            <input type="date" name="fecha_fin" id="fecha_fin" required
                                   value="{{ old('fecha_fin', now()->addYear()->format('Y-m-d')) }}"
                                   class="block w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 dark:text-white @error('fecha_fin') border-red-500 @enderror">
                        </div>
                        @error('fecha_fin') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Asignación de Cargos --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Asignación de Miembros
                </h2>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-full uppercase tracking-wider">Tablero de Cargos</span>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50">
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700 w-1/3">Cargo</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Miembro Seleccionado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($cargos as $index => $cargo)
                        <tr class="group hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors duration-150">
                            <td class="px-8 py-6">
                                <span class="text-md font-bold text-gray-900 dark:text-white block">
                                    {{ $cargo }}
                                    @if(in_array($cargo, ['Prosecretario', 'Vocal 4', 'Vocal 5']))
                                        <span class="text-xs font-normal text-gray-500 ml-1">(Opcional)</span>
                                    @else
                                        <span class="text-red-500 ml-1" title="Requerido">*</span>
                                    @endif
                                </span>
                                <input type="hidden" name="cargos[{{ $index }}][cargo_name]" value="{{ $cargo }}">
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="flex-1 min-w-[300px]">
                                        <select name="cargos[{{ $index }}][persona_id]" 
                                                class="persona-select block w-full @error('cargos.'.$index.'.persona_id') border-red-500 @enderror"
                                                id="select-{{ $index }}"
                                                data-cargo="{{ $cargo }}">
                                            @php
                                                $actual = $directivaActual->where('cargo', $cargo)->first();
                                            @endphp
                                            @if($actual)
                                                <option value="{{ $actual->miembro->persona_id }}" selected>
                                                    {{ $actual->miembro->persona->nombre }} {{ $actual->miembro->persona->apellido }} ({{ $actual->miembro->persona->dni }})
                                                </option>
                                            @else
                                                <option value=""></option>
                                            @endif
                                        </select>
                                        @error("cargos.$index.persona_id")
                                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="button" 
                                            onclick="openNewPersonaModal('{{ $index }}')"
                                            class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm flex-shrink-0 active:scale-90"
                                            title="Nueva Persona">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer - Auto Save Indicator --}}
        <div class="flex justify-end pt-4 items-center gap-3 text-gray-500" id="autoSaveStatus">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-sm font-medium">Todos los cambios se guardan automáticamente</span>
        </div>
    </form>
</div>

{{-- MODAL NUEVA PERSONA --}}
<div id="newPersonaModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-md hidden z-[100] p-4 flex items-center justify-center transition-opacity duration-300 opacity-0">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-300 border border-white/20">
        <div class="sticky top-0 bg-white dark:bg-gray-800 px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center z-10">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Nueva Persona</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Registro rápido para asignación directa.</p>
            </div>
            <button type="button" onclick="closeNewPersonaModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"/></svg>
            </button>
        </div>
        
        <form id="quickPersonaForm" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <input type="hidden" name="estado" value="Activo">
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">DNI (Identidad) *</label>
                <div class="flex gap-2">
                    <input type="text" name="dni" id="modalDni" required class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-white" placeholder="Escribe el DNI para buscar...">
                    <button type="button" id="btnBuscarDni" class="px-4 py-3 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-bold rounded-xl hover:bg-blue-200 dark:hover:bg-blue-800/50 transition-colors flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Buscar
                    </button>
                </div>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nombres *</label>
                <input type="text" name="nombre" required class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-white">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Apellidos *</label>
                <input type="text" name="apellido" required class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-white">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Fecha de Nacimiento *</label>
                <input type="date" name="fecha_nacimiento" required class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-white">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Sexo *</label>
                <select name="sexo" required class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-white">
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Teléfono *</label>
                <input type="text" name="telefono" required class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-white" placeholder="Ej: 3344-5566">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Correo Electrónico *</label>
                <input type="email" name="email" required class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-white" placeholder="usuario@ejemplo.com">
            </div>
            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Dirección (Miembro) *</label>
                <textarea name="direccion" required rows="2" class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-white shadow-inner" placeholder="Especifique la dirección completa del miembro..."></textarea>
            </div>
            <div class="md:col-span-2 pt-4">
                <div id="modalErrors" class="hidden mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-2xl text-red-600 dark:text-red-400 text-sm"></div>
                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg transition-all active:scale-95 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Confirmar y Asignar
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-wrapper.single .ts-control {
            background-color: transparent !important;
            border: none !important;
            padding: 0 !important;
            box-shadow: none !important;
        }
        .ts-control {
            padding: 0.8rem 1rem !important;
            border-radius: 1rem !important;
            border: 1px solid #e5e7eb !important;
            background-color: #f9fafb !important;
        }
        .dark .ts-control {
            background-color: rgba(17, 24, 39, 0.5) !important;
            border-color: #374151 !important;
            color: #fff !important;
        }
        .dark .ts-dropdown {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #fff !important;
        }
        .ts-dropdown .option {
            padding: 10px 15px !important;
        }
        .dark .ts-dropdown .active {
            background-color: #3b82f6 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        let selectInstances = {};
        let currentActiveSelectorId = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar todos los TomSelects
            document.querySelectorAll('.persona-select').forEach(el => {
                const id = el.id;
                selectInstances[id] = new TomSelect(el, {
                    valueField: 'id',
                    labelField: 'text',
                    searchField: ['text', 'dni'],
                    placeholder: 'Buscar por nombre o DNI...',
                    preload: true,
                    allowEmptyOption: true,
                    load: function(query, callback) {
                        if (!query.length) return callback();
                        fetch(`{{ route('directiva.search') }}?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                callback(data);
                            }).catch(() => callback());
                    },
                    render: {
                        option: function(data, escape) {
                            const badgeColor = data.type === 'miembro' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
                            return `
                                <div class="px-5 py-3 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-900 dark:text-white">${escape(data.text)}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">ID: ${escape(data.dni)}</span>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider ${badgeColor}">
                                        ${escape(data.badge || 'MIEMBRO')}
                                    </span>
                                </div>`;
                        },
                        item: function(data, escape) {
                            const badgeColor = data.type === 'miembro' ? 'text-green-600' : 'text-blue-600';
                            return `<div class="font-bold flex items-center">
                                <span class="mr-2 ${badgeColor}">●</span>
                                ${escape(data.text)}
                            </div>`;
                        }
                    }
                });

                // Validación de duplicados y Guardado Automático
                selectInstances[id].on('change', function(value) {
                    let duplicates = false;
                    
                    if (value) {
                        Object.entries(selectInstances).forEach(([otherId, instance]) => {
                            if (otherId !== id && instance.getValue() === value) {
                                duplicates = true;
                            }
                        });

                        if (duplicates) {
                            alert('⚠️ Atención: Esta persona ya ha sido asignada a otro cargo en esta directiva.');
                            this.setValue('', true); // Limpiar la selección duplicada
                            return;
                        }
                    }

                    // Auto-Save request
                    const cargo = el.dataset.cargo;
                    const fechaInicio = document.getElementById('fecha_inicio').value;
                    const fechaFin = document.getElementById('fecha_fin').value;
                    const statusDiv = document.getElementById('autoSaveStatus');
                    
                    if (statusDiv) {
                        statusDiv.innerHTML = '<span class="animate-spin mr-2">⏳</span> <span class="text-sm font-medium text-blue-600">Guardando...</span>';
                    }

                    fetch('{{ route('directiva.assign-cargo') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            cargo: cargo,
                            persona_id: value,
                            fecha_inicio: fechaInicio,
                            fecha_fin: fechaFin
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (statusDiv) {
                            if (data.success) {
                                statusDiv.innerHTML = '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> <span class="text-sm font-medium text-green-600">Guardado automáticamente</span>';
                            } else {
                                statusDiv.innerHTML = '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"/></svg> <span class="text-sm font-medium text-red-600">Error al guardar</span>';
                                console.error(data.message);
                            }
                        }
                    })
                    .catch(error => {
                        if (statusDiv) {
                            statusDiv.innerHTML = '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"/></svg> <span class="text-sm font-medium text-red-600">Error de conexión</span>';
                        }
                        console.error('Error:', error);
                    });
                });
            });

            // Manejo del formulario del modal
            const quickPersonaForm = document.getElementById('quickPersonaForm');
            const modalDni = document.getElementById('modalDni');
            const btnBuscarDni = document.getElementById('btnBuscarDni');
            
            btnBuscarDni.addEventListener('click', function() {
                const dni = modalDni.value.trim();
                if (dni.length < 5) return;
                
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="animate-spin mr-1">⏳</span> Buscando...';
                this.disabled = true;

                fetch(`/personas/dni/${dni}`)
                    .then(response => {
                        if (response.ok) return response.json();
                        throw new Error('No encontrado');
                    })
                    .then(data => {
                        // Poblar campos si se encuentra la persona
                        document.querySelector('#quickPersonaForm [name="nombre"]').value = data.nombre || '';
                        document.querySelector('#quickPersonaForm [name="apellido"]').value = data.apellido || '';
                        document.querySelector('#quickPersonaForm [name="fecha_nacimiento"]').value = data.fecha_nacimiento || '';
                        document.querySelector('#quickPersonaForm [name="sexo"]').value = data.sexo || 'M';
                        document.querySelector('#quickPersonaForm [name="telefono"]').value = data.telefono || '';
                        document.querySelector('#quickPersonaForm [name="email"]').value = data.email || '';
                        
                        // Si ya es miembro, cargar su dirección registrada
                        if (data.direccion && data.direccion !== 'Registro automático desde Directiva') {
                            document.querySelector('#quickPersonaForm [name="direccion"]').value = data.direccion;
                        }
                        
                        // Opcional: Notificación visual
                        const feedback = document.createElement('div');
                        feedback.id = 'tempFeedback';
                        feedback.className = 'col-span-1 md:col-span-2 text-green-600 text-sm font-bold mb-2';
                        feedback.innerText = '✨ ¡Persona encontrada! Datos cargados automáticamente.';
                        
                        const oldFeedback = document.getElementById('tempFeedback');
                        if (oldFeedback) oldFeedback.remove();
                        quickPersonaForm.prepend(feedback);
                        setTimeout(() => feedback.remove(), 4000);
                    })
                    .catch(() => {
                        // Opcional: Notificación visual de no encontrado
                        const feedback = document.createElement('div');
                        feedback.id = 'tempFeedback';
                        feedback.className = 'col-span-1 md:col-span-2 text-blue-600 text-sm font-bold mb-2';
                        feedback.innerText = 'ℹ️ No se encontró una persona con este DNI. Complete los datos para registrarla.';
                        
                        const oldFeedback = document.getElementById('tempFeedback');
                        if (oldFeedback) oldFeedback.remove();
                        quickPersonaForm.prepend(feedback);
                        setTimeout(() => feedback.remove(), 4000);
                    })
                    .finally(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    });
            });

            quickPersonaForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const errorDiv = document.getElementById('modalErrors');
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="animate-spin mr-2">⏳</span> Procesando...';
                errorDiv.classList.add('hidden');

                // Usaremos la nueva ruta de registro rápido de miembro
                fetch('{{ route('directiva.quick-member') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw data;
                    return data;
                })
                .then(data => {
                    // Agregar la nueva persona al selector activo
                    const select = selectInstances[`select-${currentActiveSelectorId}`];
                    select.addOption({
                        id: data.id,
                        dni: data.dni,
                        text: `${data.nombre} ${data.apellido} (${data.dni})`
                    });
                    select.setValue(data.id);
                    
                    closeNewPersonaModal();
                    this.reset();
                })
                .catch(err => {
                    errorDiv.innerText = err.message || "Error al registrar persona. Revise el DNI.";
                    errorDiv.classList.remove('hidden');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Confirmar y Asignar';
                });
            });
        });

        function openNewPersonaModal(index) {
            currentActiveSelectorId = index;
            const modal = document.getElementById('newPersonaModal');
            const content = modal.querySelector('div');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeNewPersonaModal() {
            const modal = document.getElementById('newPersonaModal');
            const content = modal.querySelector('div');
            modal.classList.remove('opacity-100');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
@endpush
@endsection
