@extends('layouts.app')

@section('title', 'Nuevo Tipo de Activo')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Tipo de Activo</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Agrega un tipo de activo al sistema</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('tipoactivo.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre del Tipo de Activo *
                    </label>
                    <input type="text" 
                           name="nombre" 
                           id="nombre" 
                           value="{{ old('nombre') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                           required>
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descripción
                    </label>
                    <textarea name="descripcion" 
                              id="descripcion" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('tipoactivo.index') }}" 
                   class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection