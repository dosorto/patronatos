@extends('layouts.app')

@section('title', 'Nuevo Cooperante')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Cooperante</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Agrega un nuevo cooperante al sistema</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('cooperantes.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Selección de Organización --}}
                <div class="mb-4">
                    <label for="id_organizacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Organización *</label>
                    <select name="id_organizacion" id="id_organizacion" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('id_organizacion') border-red-500 @enderror">
                        <option value="">Seleccione una organización</option>
                        @foreach($organizaciones as $organizacion)
                            {{-- Se usa id_organizacion según tu esquema de tabla --}}
                            <option value="{{ $organizacion->id_organizacion }}" {{ old('id_organizacion') == $organizacion->id_organizacion ? 'selected' : '' }}>
                                {{ $organizacion->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_organizacion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nombre del Cooperante --}}
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre Completo *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nombre') border-red-500 @enderror"
                           placeholder="Ej. Juan Pérez">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de Cooperante (Campo de texto normal) --}}
                <div class="mb-4">
                    <label for="tipo_cooperante" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Cooperante</label>
                    <input type="text" name="tipo_cooperante" id="tipo_cooperante" value="{{ old('tipo_cooperante') }}" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('tipo_cooperante') border-red-500 @enderror"
                        placeholder="Ej. Internacional, Voluntario, etc.">
                    @error('tipo_cooperante')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div class="mb-4">
                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('telefono') border-red-500 @enderror"
                           placeholder="Ej. +504 9999-9999">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dirección (Ocupa las 2 columnas en pantallas medianas) --}}
                <div class="mb-4 md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('direccion') border-red-500 @enderror"
                           placeholder="Dirección completa del cooperante">
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('cooperantes.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Guardar Cooperante
                </button>
            </div>
        </form>
    </div>
</div>
@endsection