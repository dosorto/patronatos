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
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Miembro</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Agrega un miembro al sistema</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('miembro.store') }}" method="POST" id="formMiembro">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Buscador de Persona --}}
                <div class="mb-2 md:col-span-2" id="seccionBuscador">
                    <label for="persona_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Persona *</label>
                    <select id="persona_id" name="persona_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white @error('persona_id') border-red-500 @enderror">
                        <option value="">Buscar persona por nombre, apellido o DNI...</option>
                        @foreach($personas as $persona)
                            <option value="{{ $persona->id }}" {{ old('persona_id') == $persona->id ? 'selected' : '' }}>
                                {{ $persona->nombre }} {{ $persona->apellido }} ({{ $persona->formatted_dni }})
                            </option>
                        @endforeach
                    </select>
                    @error('persona_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Checkbox para crear nueva persona --}}
                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="checkCrearPersona" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="text-sm text-gray-600 dark:text-gray-400">¿No encuentras a la persona? Crear nueva persona</span>
                    </label>
                </div>

                {{-- Sección crear persona (oculta por defecto) --}}
                <div id="seccionCrearPersona" class="md:col-span-2 hidden">
                    <div class="border border-blue-200 dark:border-blue-700 rounded-lg p-4 bg-blue-50 dark:bg-blue-900/20">
                        <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-4">Datos de la nueva persona</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre *</label>
                                <input type="text" name="nueva_nombre" id="nueva_nombre"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellido *</label>
                                <input type="text" name="nueva_apellido" id="nueva_apellido"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">DNI *</label>
                                <input type="text" name="nueva_dni" id="nueva_dni" placeholder="00000000000000"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha de Nacimiento</label>
                                <input type="date" name="nueva_fecha_nacimiento" id="nueva_fecha_nacimiento"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sexo</label>
                                <select name="nueva_sexo" id="nueva_sexo"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                    <option value="">Seleccione</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                                <input type="text" name="nueva_telefono" id="nueva_telefono"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" name="nueva_email" id="nueva_email"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Dirección del miembro --}}
                <div class="mb-4 md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dirección *</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('direccion') border-red-500 @enderror">
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('miembro.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Guardar
                </button>
            </div>

            {{-- Campo oculto para indicar si se crea persona nueva --}}
            <input type="hidden" name="crear_persona" id="crear_persona" value="0">

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const tsPersona = new TomSelect("#persona_id", {
        create: false,
        searchField: ['text'],
        sortField: { field: "text", direction: "asc" },
        placeholder: "Buscar persona por nombre, apellido o DNI...",
        render: {
            no_results: function(data, escape) {
                return '<div class="no-results px-4 py-2 text-gray-500">No se encontraron resultados para "' + escape(data.input) + '"</div>';
            }
        }
    });

    const check = document.getElementById('checkCrearPersona');
    const seccionCrear = document.getElementById('seccionCrearPersona');
    const inputCrearPersona = document.getElementById('crear_persona');
    const personaSelect = document.getElementById('persona_id');

    check.addEventListener('change', function() {
        if (this.checked) {
            seccionCrear.classList.remove('hidden');
            inputCrearPersona.value = '1';
            // Deshabilitar el select de persona existente
            tsPersona.disable();
            tsPersona.clear();
        } else {
            seccionCrear.classList.add('hidden');
            inputCrearPersona.value = '0';
            tsPersona.enable();
        }
    });

});
</script>
@endpush