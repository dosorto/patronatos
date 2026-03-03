@extends('layouts.app')

@section('title', 'Nuevo Miembro Directiva')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Miembro Directiva</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Asigna un cargo directivo a un miembro</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('directiva.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Selección de Miembro --}}
                <div>
                    <label for="miembro_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Miembro *</label>
                    <select name="miembro_id" id="miembro_id" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('miembro_id') border-red-500 @enderror">
                        <option value="">Seleccione un miembro</option>
                        @foreach($miembros as $miembro)
                            <option value="{{ $miembro->id }}" data-dni="{{ $miembro->persona->dni }}" {{ old('miembro_id') == $miembro->id ? 'selected' : '' }}>
                                {{ $miembro->persona->nombre }} {{ $miembro->persona->apellido }} ({{ $miembro->persona->formatted_dni }})
                            </option>
                        @endforeach
                    </select>
                    @error('miembro_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>



                {{-- Cargo --}}
                <div class="md:col-span-2">
                    <label for="cargo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cargo *</label>
                    <select name="cargo" id="cargo" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('cargo') border-red-500 @enderror">
                        <option value="">Seleccione un cargo</option>
                        <option value="Presidente(a)" {{ old('cargo') == 'Presidente(a)' ? 'selected' : '' }}>Presidente(a)</option>
                        <option value="Vicepresidente(a)" {{ old('cargo') == 'Vicepresidente(a)' ? 'selected' : '' }}>Vicepresidente(a)</option>
                        <option value="Secretario(a)" {{ old('cargo') == 'Secretario(a)' ? 'selected' : '' }}>Secretario(a)</option>
                        <option value="Tesorero(a)" {{ old('cargo') == 'Tesorero(a)' ? 'selected' : '' }}>Tesorero(a)</option>
                        <option value="Vocal 1" {{ old('cargo') == 'Vocal 1' ? 'selected' : '' }}>Vocal 1</option>
                        <option value="Vocal 2" {{ old('cargo') == 'Vocal 2' ? 'selected' : '' }}>Vocal 2</option>
                        <option value="Vocal 3" {{ old('cargo') == 'Vocal 3' ? 'selected' : '' }}>Vocal 3</option>
                    </select>
                    @error('cargo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('directiva.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Adaptación de TomSelect para Modo Oscuro y Claro en Tailwind */
        .ts-wrapper.single .ts-control {
            background-color: transparent !important;
            border: none !important;
            padding: 0 !important;
            box-shadow: none !important;
        }
        
        .dark .ts-wrapper.single .ts-control {
            color: #ffffff !important;
        }
        .dark .ts-control input {
            color: #ffffff !important;
        }
        .dark .ts-control input::placeholder {
            color: #9ca3af !important;
        }
        .dark .ts-dropdown {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
            color: #ffffff !important;
        }
        .dark .ts-dropdown .option {
            color: #ffffff !important;
        }
        .dark .ts-dropdown .option:hover,
        .dark .ts-dropdown .option.active {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }
        /* Ajustar bordes y padding para que encaje con el resto de inputs */
        .ts-control {
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#miembro_id", {
                create: false,
                searchField: ['text', 'dni'],
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "Buscar por nombre o DNI (ej: 0601200301407)...",
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results">No se encontraron resultados para "'+escape(data.input)+'"</div>';
                    },
                    option: function(data, escape) {
                        return '<div>' + escape(data.text) + '</div>';
                    }
                }
            });
        });
    </script>
@endpush
@endsection
