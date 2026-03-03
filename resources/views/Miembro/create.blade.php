@extends('layouts.app')

@section('title', 'Nuevo Miembro')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-wrapper.single .ts-control { background-color: transparent !important; border: none !important; padding: 0 !important; box-shadow: none !important; }
        .dark .ts-wrapper.single .ts-control { color: #ffffff !important; }
        .dark .ts-control input { color: #ffffff !important; }
        .dark .ts-control input::placeholder { color: #9ca3af !important; }
        .dark .ts-dropdown { background-color: #374151 !important; border-color: #4b5563 !important; color: #ffffff !important; }
        .dark .ts-dropdown .option { color: #ffffff !important; }
        .dark .ts-dropdown .option:hover, .dark .ts-dropdown .option.active { background-color: #1f2937 !important; color: #ffffff !important; }
        .ts-control { padding: 0.5rem 0.75rem !important; border-radius: 0.5rem !important; border: 1px solid #e2e8f0 !important; }
        .dark .ts-control { border-color: #4b5563 !important; }
    </style>
@endpush

@section('content')
<div class="container-fluid max-w-2xl">
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Miembro</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Completa 2 pasos para agregar un miembro al sistema</p>
    </div>

    {{-- Progress Steps --}}
    <div class="flex items-center gap-3 mb-8">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">1</span>
            <span class="text-sm {{ $step >= 1 ? 'text-gray-900 dark:text-white font-medium' : 'text-gray-500 dark:text-gray-400' }}">Persona</span>
        </div>
        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">2</span>
            <span class="text-sm {{ $step >= 2 ? 'text-gray-900 dark:text-white font-medium' : 'text-gray-500 dark:text-gray-400' }}">Datos del Miembro</span>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('miembro.store') }}" method="POST" id="formMiembro">
            @csrf
            <input type="hidden" name="step" value="{{ $step }}">

            {{-- CAMPOS OCULTOS - Se mantienen en ambos pasos --}}
            <input type="hidden" name="persona_id" id="persona_id_hidden" value="{{ old('persona_id', '') }}">
            <input type="hidden" name="nueva_nombre" id="nueva_nombre_hidden" value="{{ old('nueva_nombre', '') }}">
            <input type="hidden" name="nueva_apellido" id="nueva_apellido_hidden" value="{{ old('nueva_apellido', '') }}">
            <input type="hidden" name="nueva_dni" id="nueva_dni_hidden" value="{{ old('nueva_dni', '') }}">
            <input type="hidden" name="nueva_fecha_nacimiento" id="nueva_fecha_nacimiento_hidden" value="{{ old('nueva_fecha_nacimiento', '') }}">
            <input type="hidden" name="nueva_sexo" id="nueva_sexo_hidden" value="{{ old('nueva_sexo', '') }}">
            <input type="hidden" name="nueva_telefono" id="nueva_telefono_hidden" value="{{ old('nueva_telefono', '') }}">
            <input type="hidden" name="nueva_email" id="nueva_email_hidden" value="{{ old('nueva_email', '') }}">
            <input type="hidden" name="crear_persona" id="crear_persona" value="{{ old('crear_persona', '0') }}">

            {{-- PASO 1: PERSONA --}}
            @if ($step == 1)
                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Selecciona o crea una persona</h2>
                        
                        {{-- Buscador de Persona Existente --}}
                        <div class="mb-6">
                            <label for="persona_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar Persona Existente</label>
                            <select id="persona_id" name="persona_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white @error('persona_id') border-red-500 @enderror">
                                <option value="">-- Buscar por nombre, apellido o DNI --</option>
                                @foreach($personas as $persona)
                                    <option value="{{ $persona->id }}" 
                                            data-nombre="{{ $persona->nombre }}" 
                                            data-apellido="{{ $persona->apellido }}" 
                                            data-dni="{{ $persona->dni }}" 
                                            data-fechanacimiento="{{ $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('Y-m-d') : '' }}" 
                                            data-sexo="{{ $persona->sexo }}" 
                                            data-telefono="{{ $persona->telefono }}" 
                                            data-email="{{ $persona->email }}"
                                            {{ old('persona_id') == $persona->id ? 'selected' : '' }}>
                                        {{ $persona->nombre }} {{ $persona->apellido }} ({{ $persona->formatted_dni }})
                                    </option>
                                @endforeach
                            </select>
                            @error('persona_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Separador --}}
                        <div class="relative mb-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">O crear nueva</span>
                            </div>
                        </div>

                        {{-- Formulario para Crear Nueva Persona --}}
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre *</label>
                                    <input type="text" name="nueva_nombre" id="nueva_nombre"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                        value="{{ old('nueva_nombre') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellido *</label>
                                    <input type="text" name="nueva_apellido" id="nueva_apellido"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                        value="{{ old('nueva_apellido') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">DNI *</label>
                                    <input type="text" name="nueva_dni" id="nueva_dni" placeholder="00000000000000"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                        value="{{ old('nueva_dni') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha de Nacimiento</label>
                                    <input type="date" name="nueva_fecha_nacimiento" id="nueva_fecha_nacimiento"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                        value="{{ old('nueva_fecha_nacimiento') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sexo</label>
                                    <select name="nueva_sexo" id="nueva_sexo"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                        <option value="">Seleccione</option>
                                        <option value="M" {{ old('nueva_sexo') === 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('nueva_sexo') === 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                                    <input type="text" name="nueva_telefono" id="nueva_telefono"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                        value="{{ old('nueva_telefono') }}">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                    <input type="email" name="nueva_email" id="nueva_email"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                        value="{{ old('nueva_email') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de navegación --}}
                    <div class="flex items-center justify-between mt-8">
                        <a href="{{ route('miembro.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                            Cancelar
                        </a>
                        <button type="submit" name="step" value="1" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                            Siguiente
                        </button>
                    </div>
                </div>
            @endif

            {{-- PASO 2: DATOS DEL MIEMBRO --}}
            @if ($step == 2)
                <div class="space-y-6">
                    <div>
                        {{-- Resumen de Persona Seleccionada --}}
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-3">Persona Seleccionada</h3>
                            <div class="space-y-2 text-sm">
                                @php
                                    $nombre = $miembroDatos['nueva_nombre'] ?? '';
                                    $apellido = $miembroDatos['nueva_apellido'] ?? '';
                                    $dni = $miembroDatos['nueva_dni'] ?? '';
                                    $email = $miembroDatos['nueva_email'] ?? '';
                                    
                                    // Si se seleccionó una persona existente, buscar sus datos
                                    if (!empty($miembroDatos['persona_id'])) {
                                        $personaSeleccionada = \App\Models\Persona::find($miembroDatos['persona_id']);
                                        if ($personaSeleccionada) {
                                            $nombre = $personaSeleccionada->nombre;
                                            $apellido = $personaSeleccionada->apellido;
                                            $dni = $personaSeleccionada->dni;
                                            $email = $personaSeleccionada->email ?? 'No especificado';
                                        }
                                    }
                                @endphp
                                
                                <p><span class="font-medium text-gray-700 dark:text-gray-300">Nombre:</span> 
                                    <span class="text-gray-900 dark:text-white">{{ $nombre }} {{ $apellido }}</span>
                                </p>
                                <p><span class="font-medium text-gray-700 dark:text-gray-300">DNI:</span> 
                                    <span class="text-gray-900 dark:text-white">{{ $dni }}</span>
                                </p>
                                <p><span class="font-medium text-gray-700 dark:text-gray-300">Email:</span> 
                                    <span class="text-gray-900 dark:text-white">{{ $email ?: 'No especificado' }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- Datos del Miembro --}}
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información del Miembro</h2>
                        
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dirección *</label>
                            <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('direccion') border-red-500 @enderror"
                                placeholder="Calle, número y referencias">
                            @error('direccion')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Campos ocultos necesarios para el paso 2 --}}
                    <input type="hidden" name="persona_id" value="{{ $miembroDatos['persona_id'] ?? '' }}">
                    <input type="hidden" name="nueva_nombre" value="{{ $miembroDatos['nueva_nombre'] ?? '' }}">
                    <input type="hidden" name="nueva_apellido" value="{{ $miembroDatos['nueva_apellido'] ?? '' }}">
                    <input type="hidden" name="nueva_dni" value="{{ $miembroDatos['nueva_dni'] ?? '' }}">
                    <input type="hidden" name="crear_persona" value="{{ $miembroDatos['crear_persona'] ?? '0' }}">

                    {{-- Botones de navegación --}}
                    <div class="flex items-center justify-between mt-8">
                        <a href="{{ route('miembro.create', ['step' => 1]) }}" 
                        class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 font-medium">
                            Volver
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                            Guardar Miembro
                        </button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const step = {{ $step }};
    const personaSelect = document.getElementById('persona_id');
    const btnNextStep = document.getElementById('btnNextStep');
    const btnPrevStep = document.getElementById('btnPrevStep');

    // SOLO EN PASO 1
    if (personaSelect) {
        const tsPersona = new TomSelect("#persona_id", {
            create: false,
            searchField: ['text'],
            sortField: { field: "text", direction: "asc" },
            placeholder: "Buscar por nombre, apellido o DNI...",
            render: {
                no_results: function(data, escape) {
                    return '<div class="no-results px-4 py-2 text-gray-500">No se encontraron resultados para "' + escape(data.input) + '"</div>';
                }
            }
        });

        function llenarCamposPersona() {
            const selectedOption = personaSelect.options[personaSelect.selectedIndex];
            
            if (personaSelect.value) {
                // Si selecciona una persona existente, llena los campos
                document.getElementById('nueva_nombre').value = selectedOption.dataset.nombre || '';
                document.getElementById('nueva_apellido').value = selectedOption.dataset.apellido || '';
                document.getElementById('nueva_dni').value = selectedOption.dataset.dni || '';
                document.getElementById('nueva_fecha_nacimiento').value = selectedOption.dataset.fechanacimiento || '';
                document.getElementById('nueva_sexo').value = selectedOption.dataset.sexo || '';
                document.getElementById('nueva_telefono').value = selectedOption.dataset.telefono || '';
                document.getElementById('nueva_email').value = selectedOption.dataset.email || '';
                document.getElementById('crear_persona').value = '0';
            } else {
                // Si deselecciona, limpia los campos
                document.getElementById('nueva_nombre').value = '';
                document.getElementById('nueva_apellido').value = '';
                document.getElementById('nueva_dni').value = '';
                document.getElementById('nueva_fecha_nacimiento').value = '';
                document.getElementById('nueva_sexo').value = '';
                document.getElementById('nueva_telefono').value = '';
                document.getElementById('nueva_email').value = '';
            }
        }

        personaSelect.addEventListener('change', llenarCamposPersona);

        // Botón Siguiente
        if (btnNextStep) {
            btnNextStep.addEventListener('click', function(e) {
                e.preventDefault();
                
                const nombre = document.getElementById('nueva_nombre').value.trim();
                const apellido = document.getElementById('nueva_apellido').value.trim();
                const dni = document.getElementById('nueva_dni').value.trim();

                // Validar que haya datos
                if (!personaSelect.value && (!nombre || !apellido || !dni)) {
                    alert('Por favor, selecciona una persona o completa los datos (Nombre, Apellido y DNI).');
                    return;
                }

                // Determinar si se crea persona nueva
                if (!personaSelect.value && nombre && apellido && dni) {
                    document.getElementById('crear_persona').value = '1';
                } else {
                    document.getElementById('crear_persona').value = '0';
                }

                // Ir al paso 2
                const url = new URL(window.location);
            });
        }
    }

    // BOTÓN VOLVER
    if (btnPrevStep) {
        btnPrevStep.addEventListener('click', function(e) {
            e.preventDefault();
            const url = new URL(window.location);
        });
    }
});
</script>
@endpush