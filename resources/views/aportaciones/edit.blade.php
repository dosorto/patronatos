@extends(request()->boolean('wizard') ? 'layouts.app' : 'layouts.app')

@section('title', 'Editar Aportación')

@section('content')
<div class="container-fluid max-w-4xl space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Aportación</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Actualiza la información de la aportación</p>
        </div>
    </div>

    {{-- Form Container --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('aportacion.update', $aportacion) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Miembro --}}
                <div>
                    <label for="id_miembro" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Miembro *
                    </label>
                    <select name="id_miembro" id="id_miembro" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('id_miembro') border-red-500 @enderror">
                        <option value="">Seleccione un miembro</option>
                        @foreach($miembros as $miembro)
                            <option value="{{ $miembro->id }}"
                                {{ old('id_miembro', $aportacion->id_miembro) == $miembro->id ? 'selected' : '' }}>
                                {{ $miembro->persona->nombre_completo ?? 'Miembro #' . $miembro->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_miembro')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Proyecto --}}
                <div>
                    <label for="id_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Proyecto *
                    </label>
                    <select name="id_proyecto" id="id_proyecto" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('id_proyecto') border-red-500 @enderror">
                        <option value="">Seleccione un proyecto</option>
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}"
                                {{ old('id_proyecto', $aportacion->id_proyecto) == $proyecto->id ? 'selected' : '' }}>
                                {{ $proyecto->nombre_proyecto ?? 'Proyecto #' . $proyecto->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_proyecto')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Monto --}}
                <div>
                    <label for="monto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Monto (Lempiras) *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">L</span>
                        <input type="number" step="0.01" min="0.01" name="monto" id="monto"
                            value="{{ old('monto', $aportacion->monto) }}" required
                            class="w-full pl-6 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('monto') border-red-500 @enderror">
                    </div>
                    @error('monto')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Estado *
                    </label>
                    <select name="estado" id="estado" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('estado') border-red-500 @enderror">
                        <option value="">Seleccione un estado</option>
                        <option value="1" {{ old('estado', $aportacion->estado) == "1" ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', $aportacion->estado) == "0" ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Aportación (solo lectura) --}}
                <div>
                    <label for="fecha_aportacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha de Aportación
                    </label>
                   <input type="date" name="fecha_aportacion" id="fecha_aportacion"
                        value="{{ $aportacion->fecha_aportacion->format('Y-m-d') }}" readonly
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed">
                </div>

            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('aportacion.index') }}{{ request()->boolean('wizard') ? '?wizard=1' : '' }}"
                   class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection