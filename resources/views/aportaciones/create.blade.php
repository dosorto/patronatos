@extends(request()->boolean('wizard') ? 'layouts.app' : 'layouts.app')

@section('title', 'Nueva Aportación')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nueva Aportación</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Registra una nueva aportación al sistema</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('aportacion.store') }}" method="POST">
            @csrf
            @if(request()->boolean('wizard'))
                <input type="hidden" name="wizard" value="1">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Miembro --}}
                <div class="mb-4">
                    <label for="id_miembro" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Miembro *
                    </label>
                    <select name="id_miembro" id="id_miembro" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                            focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white 
                            @error('id_miembro') border-red-500 @enderror">

                        <option value="">Seleccione un miembro</option>
                       @foreach($miembros as $miembro)
                            <option value="{{ $miembro->id }}"
                                {{ old('id_miembro') == $miembro->id ? 'selected' : '' }}>
                                {{ $miembro->persona->nombre_completo ?? 'Miembro #' . $miembro->id }}
                            </option>
                        @endforeach
                    </select>

                    @error('id_miembro')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Proyecto --}}
                <div class="mb-4">
                    <label for="id_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Proyecto *
                    </label>
                    <select name="id_proyecto" id="id_proyecto" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                            focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white 
                            @error('id_proyecto') border-red-500 @enderror">

                        <option value="">Seleccione un proyecto</option>
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}"
                                {{ old('id_proyecto') == $proyecto->id ? 'selected' : '' }}>
                                {{ $proyecto->nombre ?? $proyecto->nombre_proyecto ?? 'Proyecto #' . $proyecto->id }}
                            </option>
                        @endforeach
                    </select>

                    @error('id_proyecto')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Monto --}}
                <div class="mb-4">
                    <label for="monto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Monto (L) *
                    </label>
                    <input type="number" step="0.01" min="0.01" name="monto" id="monto"
                        value="{{ old('monto') }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white 
                               @error('monto') border-red-500 @enderror"
                        placeholder="0.00">

                    @error('monto')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Aportación --}}
                <div class="mb-4">
                    <label for="fecha_aportacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha de Aportación *
                    </label>
                    <input type="date" name="fecha_aportacion" id="fecha_aportacion"
                        value="{{ old('fecha_aportacion') }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white 
                               @error('fecha_aportacion') border-red-500 @enderror">

                    @error('fecha_aportacion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Estado
                    </label>
                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="hidden" name="estado" value="0">
                            <input type="checkbox" name="estado" id="estado" value="1"
                                {{ old('estado', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Activo</span>
                        </label>
                    </div>

                    @error('estado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('aportacion.index') }}{{ request()->boolean('wizard') ? '?wizard=1' : '' }}"
                   class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 
                          rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg 
                               hover:bg-blue-700 transition-colors duration-200">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection