@extends('layouts.app')

@section('title', 'Editar Empleado')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Empleado</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Actualiza la información del empleado</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('empleado.update', $empleado) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Persona (solo lectura) --}}
                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Persona</label>
                    <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        {{ $empleado->persona->nombre_completo ?? 'N/A' }} ({{ $empleado->persona->dni ?? 'N/A' }})
                    </div>
                </div>

                {{-- Cargo --}}
                <div class="mb-4">
                    <label for="cargo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cargo *</label>
                    <input type="text" name="cargo" id="cargo" value="{{ old('cargo', $empleado->cargo) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('cargo') border-red-500 @enderror">
                    @error('cargo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Frecuencia de Pago --}}
                <div class="mb-4">
                    <label for="frecuencia_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Frecuencia de Pago *</label>
                    <select name="frecuencia_pago" id="frecuencia_pago" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('frecuencia_pago') border-red-500 @enderror">
                        <option value="Mensual" {{ old('frecuencia_pago', $empleado->frecuencia_pago) == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                        <option value="Quincenal" {{ old('frecuencia_pago', $empleado->frecuencia_pago) == 'Quincenal' ? 'selected' : '' }}>Quincenal</option>
                        <option value="Semanal" {{ old('frecuencia_pago', $empleado->frecuencia_pago) == 'Semanal' ? 'selected' : '' }}>Semanal</option>
                    </select>
                    @error('frecuencia_pago')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sueldo --}}
                <div class="mb-4">
                    <label for="sueldo_mensual" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Monto de Sueldo *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400 text-sm font-medium">L.</span>
                        <input type="number" name="sueldo_mensual" id="sueldo_mensual" value="{{ old('sueldo_mensual', $empleado->sueldo_mensual) }}" required step="0.01" min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('sueldo_mensual') border-red-500 @enderror">
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