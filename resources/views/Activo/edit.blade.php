@extends(request()->boolean('wizard') ? 'layouts.app' : 'layouts.app')

@section('title', 'Editar Activo')

@section('content')
<div class="container-fluid max-w-4xl space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Activo</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Actualiza la información del activo</p>
        </div>
    </div>

    {{-- Form Container --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('activo.update', $activo) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nombre del activo --}}
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $activo->nombre) }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de activo --}}
                <div>
                    <label for="tipo_activo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Activo *</label>
                    <select name="tipo_activo_id" id="tipo_activo_id" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('tipo_activo_id') border-red-500 @enderror">
                        <option value="">Seleccione un tipo</option>
                        @foreach($tiposActivos as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipo_activo_id', $activo->tipo_activo_id) == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_activo_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado *</label>
                    <select name="estado" id="estado" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('estado') border-red-500 @enderror">
                        <option value="">Seleccione un estado</option>
                        <option value="1" {{ old('estado', $activo->estado) == "1" ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', $activo->estado) == "0" ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Valor Estimado --}}
                <div>
                    <label for="valor_estimado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Valor Estimado (Lempiras) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">L</span>
                        <input type="number" name="valor_estimado" id="valor_estimado" value="{{ old('valor_estimado', $activo->valor_estimado) }}" step="0.01" required
                            class="w-full pl-6 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('valor_estimado') border-red-500 @enderror">
                    </div>
                    @error('valor_estimado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ubicación --}}
                <div>
                    <label for="ubicacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ubicación</label>
                    <input type="text" name="ubicacion" id="ubicacion" value="{{ old('ubicacion', $activo->ubicacion) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('ubicacion') border-red-500 @enderror">
                    @error('ubicacion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $activo->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Adquisición --}}
                <div>
                    <label for="fecha_adquisicion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha de Adquisición</label>
                    <input type="date" id="fecha_adquisicion" value="{{ $activo->fecha_adquisicion }}" readonly
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed">
                </div>

            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('activo.index') }}{{ request()->boolean('wizard') ? '?wizard=1' : '' }}"
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