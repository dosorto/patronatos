@extends('layouts.app')

@section('title', 'Mantenimientos')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Mantenimientos</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Administra los mantenimientos preventivos y correctivos de los activos</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @can('mantenimiento.export')
                <a href="{{ route('mantenimiento.export') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar a Excel
                </a>
            @endcan

            <a href="{{ route('mantenimiento.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nuevo Mantenimiento
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Table Container --}}
    <div class="content-container mx-auto w-full max-w-7xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            {{-- Table Header --}}
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center gap-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Lista de Mantenimientos</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                        {{ count($mantenimientos) }} {{ count($mantenimientos) === 1 ? 'registro' : 'registros' }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Activo / Descripción</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prioridad</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</span>
                            </th>
                            <th class="px-6 py-3 text-left w-32">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($mantenimientos as $mant)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer group"
                                onclick="window.location='{{ route('mantenimiento.show', $mant->id) }}'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3 shadow-sm group-hover:scale-110 transition-transform">
                                            <span class="text-white text-xs font-bold">{{ strtoupper(substr($mant->activo->nombre ?? 'MA', 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $mant->activo ? $mant->activo->nombre : 'Mantenimiento General' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                                {{ $mant->descripcion }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($mant->tipo_mantenimiento == 'Preventivo') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 
                                        @elseif($mant->tipo_mantenimiento == 'Correctivo') bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 
                                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 @endif">
                                        {{ $mant->tipo_mantenimiento }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $mant->fecha_registro->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                        @if($mant->prioridad == 'Alta') border-red-500 text-red-600 dark:text-red-400
                                        @elseif($mant->prioridad == 'Media') border-yellow-500 text-yellow-600 dark:text-yellow-400
                                        @else border-green-500 text-green-600 dark:text-green-400 @endif">
                                        {{ $mant->prioridad }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($mant->estado == 'Activo') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 
                                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 @endif">
                                        {{ $mant->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4" onclick="event.stopPropagation()">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('mantenimiento.show', $mant->id) }}" 
                                           class="p-1 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 rounded transition-colors"
                                           title="Ver detalles">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('mantenimiento.edit', $mant->id) }}" 
                                           class="p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('mantenimiento.destroy', $mant->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Está seguro de eliminar este registro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors"
                                                    title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No hay mantenimientos registrados</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-4 max-w-sm">Comienza registrando los mantenimientos realizados a tus activos.</p>
                                        <a href="{{ route('mantenimiento.create') }}"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Crear primer mantenimiento
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
