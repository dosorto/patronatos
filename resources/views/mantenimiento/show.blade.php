@extends('layouts.app')

@section('title', 'Detalles del Mantenimiento')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalles del Mantenimiento</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Información detallada del registro de mantenimiento</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('mantenimiento.index') }}" 
               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200 font-medium">
                Volver
            </a>
            <a href="{{ route('mantenimiento.edit', $mantenimiento->id) }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium shadow-sm">
                Editar
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Tipo de Mantenimiento --}}
            <div class="space-y-1">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Mantenimiento</span>
                <p class="text-base text-gray-900 dark:text-white font-semibold">
                    {{ $mantenimiento->tipo_mantenimiento }}
                </p>
            </div>

            {{-- Activo Asociado --}}
            <div class="space-y-1">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Activo Asociado</span>
                <p class="text-base text-gray-900 dark:text-white">
                    {{ $mantenimiento->activo ? $mantenimiento->activo->nombre : 'Mantenimiento General' }}
                </p>
            </div>

            {{-- Fecha de Registro --}}
            <div class="space-y-1">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Registro</span>
                <p class="text-base text-gray-900 dark:text-white">
                    {{ $mantenimiento->fecha_registro ? $mantenimiento->fecha_registro->format('d/m/Y') : 'N/A' }}
                </p>
            </div>

            {{-- Prioridad --}}
            <div class="space-y-1">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Prioridad</span>
                <div>
                    @php
                        $prioridadColor = [
                            'Baja' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                            'Media' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                            'Alta' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                        ][$mantenimiento->prioridad] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadColor }}">
                        {{ $mantenimiento->prioridad }}
                    </span>
                </div>
            </div>

            {{-- Costo Estimado --}}
            <div class="space-y-1">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Costo Estimado</span>
                <p class="text-base text-gray-900 dark:text-white font-mono">
                    L {{ number_format($mantenimiento->costo_estimado, 2) }}
                </p>
            </div>

            {{-- Estado --}}
            <div class="space-y-1">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</span>
                <div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        {{ $mantenimiento->estado ?? 'Pendiente' }}
                    </span>
                </div>
            </div>

            {{-- Descripción --}}
            <div class="md:col-span-2 space-y-1">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</span>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-600 text-gray-800 dark:text-gray-200">
                    {{ $mantenimiento->descripcion }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
