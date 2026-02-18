@extends('layouts.app')

@section('title', 'Detalle de Municipio')

@section('content')
<div class="container-fluid max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Detalle de Municipio</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Viendo la información detallada del municipio.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('municipio.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 text-sm font-medium">
                Volver
            </a>
            @can('municipio.edit')
                <a href="{{ route('municipio.edit', $municipio) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                    Editar
                </a>
            @endcan
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">Información General</h2>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">ID</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $municipio->id }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Departamento</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $municipio->departamento->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Nombre del Municipio</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $municipio->nombre }}</p>
            </div>

            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Fecha de Creación</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $municipio->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Última Actualización</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $municipio->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
