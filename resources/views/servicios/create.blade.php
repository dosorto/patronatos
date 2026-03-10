@extends('layouts.app')

@section('title', 'Nuevo Servicio')

@section('content')
<div class="container-fluid max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Servicio</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('servicios.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nombre --}}
               <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-2">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                        placeholder="Nombre del servicio"
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white placeholder:text-gray-800 placeholder:opacity-50 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-2">Estado</label>
                    <select name="estado" id="estado"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('estado') border-red-500 @enderror">
                        <option value="">-- Seleccionar --</option>
                        <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-2">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                              placeholder="Descripción del servicio"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white placeholder:text-gray-800 placeholder:opacity-50 @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Precio --}}
                <div>
                    <label for="precio" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-2">Precio</label>
                    <input type="number" name="precio" id="precio" value="{{ old('precio') }}"
                           placeholder="0.00" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white placeholder:text-gray-800 placeholder:opacity-50 @error('precio') border-red-500 @enderror">
                    @error('precio')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Proyecto --}}
                <div>
                    <label for="proyecto_id" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-2">Proyecto</label>
                    <select name="proyecto_id" id="proyecto_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('proyecto_id') border-red-500 @enderror">
                        <option value="">-- Sin proyecto --</option>
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                                {{ $proyecto->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('proyecto_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tiene Medidor --}}
                <div class="md:col-span-2">
                    <div class="flex items-center gap-8">
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="tiene_medidor" value="0">
                            <input type="checkbox" name="tiene_medidor" id="tiene_medidor" value="1"
                                   {{ old('tiene_medidor') ? 'checked' : '' }}
                                   onchange="toggleMedidor(this.checked)"
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="tiene_medidor" class="text-sm font-medium text-gray-900 dark:text-gray-300">¿Tiene medidor?</label>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="hidden" name="es_aportacion" value="0">
                            <input type="checkbox" name="es_aportacion" id="es_aportacion" value="1"
                                   {{ old('es_aportacion') ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="es_aportacion" class="text-sm font-medium text-gray-900 dark:text-gray-300">¿Es aportación?</label>
                        </div>
                    </div>
                </div>

                {{-- Campos de medidor (condicional) --}}
                <div id="campos-medidor" class="{{ old('tiene_medidor') ? '' : 'hidden' }} md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="unidad_medida" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unidad de Medida</label>
                        <input type="text" name="unidad_medida" id="unidad_medida" value="{{ old('unidad_medida') }}"
                               placeholder="Ej. m³, kWh, litros"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('unidad_medida') border-red-500 @enderror">
                        @error('unidad_medida')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="precio_por_unidad_de_medida" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-2">Precio por Unidad de Medida</label>
                        <input type="number" name="precio_por_unidad_de_medida" id="precio_por_unidad_de_medida"
                               value="{{ old('precio_por_unidad_de_medida') }}"
                               placeholder="0.00" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('precio_por_unidad_de_medida') border-red-500 @enderror">
                        @error('precio_por_unidad_de_medida')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('servicios.index') }}"
                   class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleMedidor(checked) {
        const campos = document.getElementById('campos-medidor');
        campos.classList.toggle('hidden', !checked);
    }
</script>
@endsection