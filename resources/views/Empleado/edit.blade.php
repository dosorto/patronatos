@extends('layouts.app')

@section('title', 'Editar Empleado')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Empleado</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Modifica los datos del empleado</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('empleado.update', $empleado) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Selección de Persona --}}
                <div>
                    <label for="persona_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Persona *</label>
                    <select name="persona_id" id="persona_id" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('persona_id') border-red-500 @enderror">
                        <option value="">Seleccione una persona</option>
                        @foreach($personas as $persona)
                            <option value="{{ $persona->id }}" {{ old('persona_id', $empleado->persona_id) == $persona->id ? 'selected' : '' }}>
                                {{ $persona->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                    @error('persona_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Selección de Organización --}}
                <div>
                    <label for="organizacion_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Organización *</label>
                    <select name="organizacion_id" id="organizacion_id" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('organizacion_id') border-red-500 @enderror">
                        <option value="">Seleccione una organización</option>
                        @foreach($organizaciones as $organizacion)
                            <option value="{{ $organizacion->id_organizacion }}" {{ old('organizacion_id', $empleado->organizacion_id) == $organizacion->id_organizacion ? 'selected' : '' }}>
                                {{ $organizacion->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('organizacion_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cargo --}}
                <div>
                    <label for="cargo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cargo *</label>
                    <input type="text" name="cargo" id="cargo" value="{{ old('cargo', $empleado->cargo) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('cargo') border-red-500 @enderror">
                    @error('cargo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sueldo Mensual --}}
                <div>
                    <label for="sueldo_mensual" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sueldo Mensual *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400 text-sm font-medium">L.</span>
                        <input type="number" name="sueldo_mensual" id="sueldo_mensual" value="{{ old('sueldo_mensual', $empleado->sueldo_mensual) }}" required step="0.01" min="0"
                            class="w-full pl-7 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('sueldo_mensual') border-red-500 @enderror">
                    </div>
                    @error('sueldo_mensual')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('empleado.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
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