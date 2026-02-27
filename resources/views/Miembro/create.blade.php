@extends('layouts.app')

@section('title', 'Nuevo Miembro')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Miembro</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Agrega un miembro al sistema</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('miembro.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Selección de Persona --}}
                <div class="mb-4">
                    <label for="persona_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Persona *</label>
                    <select name="persona_id" id="persona_id" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('persona_id') border-red-500 @enderror">
                        <option value="">Seleccione una persona</option>
                        @foreach($personas as $persona)
                            <option value="{{ $persona->id }}" {{ old('persona_id') == $persona->id ? 'selected' : '' }}>
                                {{ $persona->nombre }} {{ $persona->apellido }} ({{ $persona->dni }})
                            </option>
                        @endforeach
                    </select>
                    @error('persona_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Selección de Organización --}}
                <div class="mb-4">
                    <label for="organizacion_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Organización *</label>
                    <select name="organizacion_id" id="organizacion_id" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('organizacion_id') border-red-500 @enderror">
                        <option value="" {{ old('organizacion_id') ? '' : 'selected' }}>Seleccione una organización</option>
                        @foreach($organizaciones as $org)
                            <option value="{{ $org->id_organizacion }}" {{ old('organizacion_id') == $org->id_organizacion ? 'selected' : '' }}>
                                {{ $org->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('organizacion_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Selección de País --}}
                <div class="mb-4">
                    <label for="pais_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">País *</label>
                    <select name="pais_id" id="pais_id" required
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">Seleccione un país</option>
                        @foreach($paises as $pais)
                            <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Selección de Departamento --}}
                <div class="mb-4">
                    <label for="departamento_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Departamento *</label>
                    <select name="departamento_id" id="departamento_id" required
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">Seleccione un departamento</option>
                    </select>
                </div>

                {{-- Selección de Municipio --}}
                <div class="mb-4">
                    <label for="municipio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Municipio *</label>
                    <select name="municipio_id" id="municipio_id" required
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">Seleccione un municipio</option>
                    </select>
                </div>

                {{-- Dirección --}}
                <div class="mb-4">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dirección *</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('direccion') border-red-500 @enderror">
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div class="mb-4">
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado *</label>
                    <select name="estado" id="estado" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('estado') border-red-500 @enderror">
                        <option value="">Seleccione un estado</option>
                        <option value="1" {{ old('estado') == "1" ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado') == "0" ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
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
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const paisSelect = document.getElementById('pais_id');
    const departamentoSelect = document.getElementById('departamento_id');
    const municipioSelect = document.getElementById('municipio_id');

    paisSelect.addEventListener('change', function () {
        const paisId = this.value;
        departamentoSelect.innerHTML = '<option value="">Cargando...</option>';
        municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';

        if(paisId) {
            fetch(`/departamentos-por-pais/${paisId}`)
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="">Seleccione un departamento</option>';
                    data.forEach(dept => options += `<option value="${dept.id}">${dept.nombre}</option>`);
                    departamentoSelect.innerHTML = options;
                });
        } else {
            departamentoSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
        }
    });

    departamentoSelect.addEventListener('change', function () {
        const deptoId = this.value;
        municipioSelect.innerHTML = '<option value="">Cargando...</option>';

        if(deptoId) {
            fetch(`/municipios-por-departamento/${deptoId}`)
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="">Seleccione un municipio</option>';
                    data.forEach(mun => options += `<option value="${mun.id}">${mun.nombre}</option>`);
                    municipioSelect.innerHTML = options;
                });
        } else {
            municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
        }
    });
});
</script>
@endsection