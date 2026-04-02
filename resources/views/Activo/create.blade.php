@extends(request()->boolean('wizard') ? 'layouts.app' : 'layouts.app')

@section('title', 'Nuevo Activo')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Activo</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Agrega un activo al sistema</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('activo.store') }}" method="POST">
            @csrf
            @if(request()->boolean('wizard'))
                <input type="hidden" name="wizard" value="1">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nombre --}}
                <div class="mb-4 md:col-span-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre del Activo *
                    </label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white 
                               @error('nombre') border-red-500 @enderror">

                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de Activo --}}
                <div class="mb-4">
                    <label for="tipo_activo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tipo de Activo *
                    </label>
                    <select name="tipo_activo_id" id="tipo_activo_id" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white 
                               @error('tipo_activo_id') border-red-500 @enderror">

                        <option value="">Seleccione un tipo</option>
                        @foreach($tiposActivos as $tipo)
                            <option value="{{ $tipo->id }}" 
                                {{ old('tipo_activo_id') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>

                    @error('tipo_activo_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ubicación --}}
                <div class="mb-4">
                    <label for="ubicacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ubicación
                    </label>
                    <input type="text" name="ubicacion" id="ubicacion" value="{{ old('ubicacion') }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white 
                               @error('ubicacion') border-red-500 @enderror">

                    @error('ubicacion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Adquisición --}}
                <div class="mb-4">
                    <label for="fecha_adquisicion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha de Adquisición
                    </label>
                    <input type="date" name="fecha_adquisicion" id="fecha_adquisicion" 
                        value="{{ old('fecha_adquisicion') }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white 
                               @error('fecha_adquisicion') border-red-500 @enderror">

                    @error('fecha_adquisicion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Valor Estimado --}}
                <div class="mb-4">
                    <label for="valor_estimado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Valor Estimado
                    </label>
                    <input type="number" step="0.01" name="valor_estimado" id="valor_estimado" 
                        value="{{ old('valor_estimado') }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white 
                               @error('valor_estimado') border-red-500 @enderror">

                    @error('valor_estimado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="mb-4 md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descripción
                    </label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white 
                               @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>

                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('activo.index') }}{{ request()->boolean('wizard') ? '?wizard=1' : '' }}"
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