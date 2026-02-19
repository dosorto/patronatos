@extends('layouts.app')

@section('title', 'Detalle de Tipo de Activo')

@section('content')
<div class="container-fluid max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Detalle de Tipo de Activo</h1>
        <p class="text-gray-600 dark:text-gray-300">Viendo la información detallada del tipo de activo</p>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('tipoactivo.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Volver
        </a>
        @can('tipoactivo.edit')
            <a href="{{ route('tipoactivo.edit', $tipoactivo) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Editar
            </a>
        @endcan
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Información General -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="font-bold text-lg mb-4">INFORMACIÓN GENERAL</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm text-gray-500">ID</dt>
                    <dd class="font-medium">{{ $tipoactivo->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">NOMBRE</dt>
                    <dd class="font-medium">{{ $tipoactivo->nombre }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">DESCRIPCIÓN</dt>
                    <dd class="font-medium">{{ $tipoactivo->descripcion ?? 'Sin descripción' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">FECHA DE CREACIÓN</dt>
                    <dd class="font-medium">{{ $tipoactivo->created_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">ÚLTIMA ACTUALIZACIÓN</dt>
                    <dd class="font-medium">{{ $tipoactivo->updated_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}</dd>
                </div>
            </dl>
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