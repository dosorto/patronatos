@extends('layouts.app')

@section('title', 'Nuevo Mantenimiento')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Mantenimiento</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Registra un mantenimiento para la organización</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('mantenimiento.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Tipo de Mantenimiento --}}
                <div class="mb-4">
                    <label for="tipo_mantenimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tipo de Mantenimiento *
                    </label>
                    <select name="tipo_mantenimiento" id="tipo_mantenimiento" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('tipo_mantenimiento') border-red-500 @enderror transition-colors duration-200">
                        <option value="General" {{ old('tipo_mantenimiento') == 'General' ? 'selected' : '' }}>General</option>
                        <option value="Correctivo" {{ old('tipo_mantenimiento') == 'Correctivo' ? 'selected' : '' }}>Correctivo</option>
                        <option value="Preventivo" {{ old('tipo_mantenimiento') == 'Preventivo' ? 'selected' : '' }}>Preventivo</option>
                    </select>
                    @error('tipo_mantenimiento')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Activo Asociado (Opcional) --}}
                <div class="mb-4">
                    <label for="activo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Activo (Opcional)
                    </label>
                    <select name="activo_id" id="activo_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                        <option value="">Ninguno específico / Mantenimiento general</option>
                        @foreach($activos as $activo)
                            <option value="{{ $activo->id }}" {{ old('activo_id') == $activo->id ? 'selected' : '' }}>
                                {{ $activo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('activo_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Registro --}}
                <div class="mb-4">
                    <label for="fecha_registro" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha de Registro *
                    </label>
                    <input type="date" name="fecha_registro" id="fecha_registro" 
                        value="{{ old('fecha_registro', date('Y-m-d')) }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_registro') border-red-500 @enderror transition-colors duration-200">
                    @error('fecha_registro')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Prioridad --}}
                <div class="mb-4">
                    <label for="prioridad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Prioridad *
                    </label>
                    <select name="prioridad" id="prioridad" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('prioridad') border-red-500 @enderror transition-colors duration-200">
                        <option value="Baja" {{ old('prioridad') == 'Baja' ? 'selected' : '' }}>Baja</option>
                        <option value="Media" {{ old('prioridad') == 'Media' ? 'selected' : '' }}>Media</option>
                        <option value="Alta" {{ old('prioridad') == 'Alta' ? 'selected' : '' }}>Alta</option>
                    </select>
                    @error('prioridad')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Costo Estimado --}}
                <div class="mb-4">
                    <label for="costo_estimado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Costo Estimado (Opcional)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">L</span>
                        </div>
                        <input type="number" step="0.01" name="costo_estimado" id="costo_estimado" 
                            value="{{ old('costo_estimado') }}"
                            class="w-full pl-7 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('costo_estimado') border-red-500 @enderror transition-colors duration-200"
                            placeholder="0.00">
                    </div>
                    @error('costo_estimado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="mb-4 md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descripción *
                    </label>
                    <textarea name="descripcion" id="descripcion" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descripcion') border-red-500 @enderror transition-colors duration-200"
                        placeholder="Describe el trabajo realizado o por realizar">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('mantenimiento.index') }}"
                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200 font-medium">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium shadow-sm">
                    Guardar Mantenimiento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
