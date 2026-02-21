@extends('layouts.app')
@section('title', 'Detalle de Organización')
@section('content')
<div class="container-fluid max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Detalle de Organización</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Viendo la información detallada de la organización.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('organizacion.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 text-sm font-medium">
                Volver
            </a>
            @can('organizacion.edit')
                <a href="{{ route('organizacion.edit', $organizacion->id_organizacion) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                    Editar
                </a>
            @endcan
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">Información General</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Nombre</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $organizacion->nombre }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">RTN</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $organizacion->rtn }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Teléfono</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $organizacion->telefono }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Dirección</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $organizacion->direccion }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Tipo de Organización</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $organizacion->tipoOrganizacion->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Departamento</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $organizacion->departamento->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Municipio</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $organizacion->municipio->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Fecha de Creación</label>
                <p class="text-md font-medium text-gray-900 dark:text-white">{{ $organizacion->fecha_creacion?->format('d/m/Y') }}</p>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Estado</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $organizacion->estado === 'Activo' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                    {{ $organizacion->estado }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection