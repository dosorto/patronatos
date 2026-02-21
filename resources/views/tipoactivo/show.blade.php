@extends('layouts.app')

@section('title', 'Detalle de Tipo de Activo')

@section('content')
<div class="container-fluid max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Detalle de Tipo de Activo</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Viendo la información detallada del tipo de activo.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tipoactivo.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 text-sm font-medium">
                Volver
            </a>
            @can('tipoactivo.edit')
                <a href="{{ route('tipoactivo.edit', $tipoactivo) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                    Editar
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Información General -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">Información General</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">ID</label>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $tipoactivo->id }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Nombre del tipo de activo</label>
                    <p class="text-md font-medium text-gray-900 dark:text-white">{{ $tipoactivo->nombre }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Descripción</label>
                    <p class="text-md font-medium text-gray-900 dark:text-white">{{ $tipoactivo->descripcion ?? 'Sin descripción' }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Fecha de Registro</label>
                    <p class="text-md font-medium text-gray-900 dark:text-white">{{ $tipoactivo->created_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Última Actualización</label>
                    <p class="text-md font-medium text-gray-900 dark:text-white">{{ $tipoactivo->updated_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}</p>
                </div>
            </div>
        </div>

        <!-- Historial de Cambios -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="font-bold text-lg mb-4">HISTORIAL DE CAMBIOS</h2>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
                        ✓
                    </div>
                    <div class="flex-1">
                        <p class="font-medium">CREADO por Sistema</p>
                        <p class="text-sm text-gray-500">{{ $tipoactivo->created_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection