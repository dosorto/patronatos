@extends(request()->boolean('wizard') ? 'layouts.app' : 'layouts.app')

@section('title', 'Editar Miembro')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Miembro</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Actualiza la información del miembro</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('miembro.update', $miembro) }}" method="POST">
            @csrf
            @method('PUT')
            @if($isWizard)
                <input type="hidden" name="wizard" value="1">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Sección de Datos de Persona --}}
                <div class="mb-4 md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Datos de Persona</h2>
                </div>

                {{-- Nombre --}}
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $miembro->persona->nombre ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nombre') border-red-500 @enderror"
                           required>
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Apellido --}}
                <div class="mb-4">
                    <label for="apellido" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Apellido *</label>
                    <input type="text" name="apellido" id="apellido" value="{{ old('apellido', $miembro->persona->apellido ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('apellido') border-red-500 @enderror"
                           required>
                    @error('apellido')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DNI --}}
                <div class="mb-4">
                    <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">DNI *</label>
                    <input type="text" name="dni" id="dni" value="{{ old('dni', $miembro->persona->dni ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('dni') border-red-500 @enderror"
                           required>
                    @error('dni')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sexo --}}
                <div class="mb-4">
                    <label for="sexo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sexo</label>
                    <select name="sexo" id="sexo"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('sexo') border-red-500 @enderror">
                        <option value="">Seleccione...</option>
                        <option value="M" {{ old('sexo', $miembro->persona->sexo ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo', $miembro->persona->sexo ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                    @error('sexo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Nacimiento --}}
                <div class="mb-4">
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento', $miembro->persona->fecha_nacimiento?->format('Y-m-d') ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_nacimiento') border-red-500 @enderror">
                    @error('fecha_nacimiento')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $miembro->persona->email ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div class="mb-4">
                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $miembro->persona->telefono ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sección de Datos de Miembro --}}
                <div class="mb-4 md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Datos de Miembro</h2>
                </div>

                {{-- Dirección --}}
                <div class="mb-4 md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $miembro->direccion) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('direccion') border-red-500 @enderror">
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div class="mb-4">
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado *</label>
                    <select name="estado" id="estado" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('estado') border-red-500 @enderror">
                        <option value="1" {{ old('estado', $miembro->getRawOriginal('estado')) == "1" ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', $miembro->getRawOriginal('estado')) == "0" ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('miembro.index') }}{{ request()->boolean('wizard') ? '?wizard=1' : '' }}" 
                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
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