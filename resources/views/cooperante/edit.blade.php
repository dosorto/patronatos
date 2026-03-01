@extends('layouts.app')

@section('title', 'Editar Cooperante')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Cooperante</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('cooperantes.update', $cooperante) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Selección de Organización --}}
                <div class="mb-4">
                    <label for="id_organizacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Organización *</label>
                    <select name="id_organizacion" id="id_organizacion" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('id_organizacion') border-red-500 @enderror">
                        @foreach($organizaciones as $organizacion)
                            <option value="{{ $organizacion->id_organizacion }}" {{ old('id_organizacion', $cooperante->id_organizacion) == $organizacion->id_organizacion ? 'selected' : '' }}>
                                {{ $organizacion->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_organizacion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nombre del Cooperante --}}
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $cooperante->nombre) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de Cooperante --}}
                <div>
                    <label for="tipo_cooperante" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Cooperante *</label>
                    <select name="tipo_cooperante" id="tipo_cooperante" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('tipo_cooperante') border-red-500 @enderror">
                        @foreach(['Nacional','Internacional','ONG','Empresa privada','Institución pública','Otro'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_cooperante', $cooperante->tipo_cooperante) == $tipo ? 'selected' : '' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_cooperante')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teléfono *</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $cooperante->telefono) }}" required
                           placeholder="+504 0000-0000"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dirección *</label>
                    <textarea name="direccion" id="direccion" rows="3" required
                              placeholder="Dirección completa del cooperante"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('direccion') border-red-500 @enderror">{{ old('direccion', $cooperante->direccion) }}</textarea>
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
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection