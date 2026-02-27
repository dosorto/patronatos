@extends('layouts.app')

@section('title', 'Nuevo Municipio')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Municipio</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Agrega un municipio al sistema</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('municipio.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Selección de País --}}
                <div>
                    <label for="pais_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">País *</label>
                    <select name="pais_id" id="pais_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Seleccione un país</option>
                        @foreach($paises as $pais)
                            <option value="{{ $pais->id }}" {{ old('pais_id') == $pais->id ? 'selected' : '' }}>
                                {{ $pais->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Selección de Departamento --}}
                <div>
                    <label for="departamento_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Departamento *</label>
                    <select name="departamento_id" id="departamento_id" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('departamento_id') border-red-500 @enderror">
                        <option value="">Seleccione primero un país</option>
                    </select>
                    @error('departamento_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nombre del Municipio --}}
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('municipio.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
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
    document.getElementById('pais_id').addEventListener('change', function () {
        const paisId = this.value;
        const deptoSelect = document.getElementById('departamento_id');

        deptoSelect.innerHTML = '<option value="">Seleccione un departamento</option>';

        if (!paisId) return;

        fetch(`{{ url('municipio/departamentos') }}/${paisId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(d => {
                    deptoSelect.innerHTML += `<option value="${d.id}">${d.nombre}</option>`;
                });
            });
    });
</script>
@endsection