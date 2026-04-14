@extends('layouts.app')

@section('title', 'Planilla de Asistencia - Jornada #' . $jornada->numero_jornada)

@section('content')
<div class="container-fluid max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6 no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Planilla de Asistencia</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $proyecto->nombre_proyecto }} · Jornada #{{ $jornada->numero_jornada }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('proyecto.show', $proyecto) }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                Volver al Proyecto
            </a>
            <a href="{{ route('proyecto.jornadas.pdf', [$proyecto, $jornada]) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Descargar PDF
            </a>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6 p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase">Jornada</p>
                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">#{{ $jornada->numero_jornada }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase">Fecha</p>
                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $jornada->fecha?->format('d/m/Y') ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase">Hora Inicio</p>
                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $jornada->hora_inicio ? \Carbon\Carbon::parse($jornada->hora_inicio)->format('H:i') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase">Estado</p>
                @php
                    $estadoBadge = $jornada->estado === 'realizada'
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                        : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                @endphp
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $estadoBadge }}">{{ ucfirst($jornada->estado ?? 'programada') }}</span>
            </div>
        </div>
        @if($jornada->descripcion)
            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Descripción:</strong> {{ $jornada->descripcion }}</p>
        @endif
    </div>

    {{-- Attendance Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">Lista de Asistencia</h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $jornada->asistencias->count() }} convocados</span>
        </div>

        @if($jornada->estado !== 'realizada')
            <form method="POST" action="{{ route('proyecto.jornadas.lista', [$proyecto, $jornada]) }}">
                @csrf
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 w-10">#</th>
                        <th class="px-4 py-3">Nombre del Miembro</th>
                        <th class="px-4 py-3 text-center w-20">Asistió</th>
                        <th class="px-4 py-3 text-center w-24">Sustituto</th>
                        <th class="px-4 py-3">Nombre Sustituto</th>
                        <th class="px-4 py-3">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($jornada->asistencias as $idx => $asist)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ $asist->asistio ? 'bg-green-50/30 dark:bg-green-900/10' : '' }}">
                            <td class="px-4 py-3 text-gray-500">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-200">
                                {{ $asist->miembro?->persona?->nombre_completo ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($jornada->estado !== 'realizada')
                                    <input type="hidden" name="asistencias[{{ $idx }}][id]" value="{{ $asist->id }}">
                                    <input type="checkbox" name="asistencias[{{ $idx }}][asistio]" value="1" {{ $asist->asistio ? 'checked' : '' }}
                                           class="text-green-600 rounded border-gray-300 dark:border-gray-600 focus:ring-green-500">
                                @else
                                    @if($asist->asistio)
                                        <span class="text-green-600">✓</span>
                                    @else
                                        <span class="text-red-400">✗</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($jornada->estado !== 'realizada')
                                    <input type="checkbox" name="asistencias[{{ $idx }}][mando_sustituto]" value="1" {{ $asist->mando_sustituto ? 'checked' : '' }}
                                           class="text-yellow-600 rounded border-gray-300 dark:border-gray-600 focus:ring-yellow-500">
                                @else
                                    @if($asist->mando_sustituto) <span class="text-yellow-600">✓</span> @else - @endif
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($jornada->estado !== 'realizada')
                                    <input type="text" name="asistencias[{{ $idx }}][nombre_sustituto]" value="{{ $asist->nombre_sustituto }}"
                                           class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                           placeholder="Nombre del sustituto...">
                                @else
                                    {{ $asist->nombre_sustituto ?: '-' }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($jornada->estado !== 'realizada')
                                    <input type="text" name="asistencias[{{ $idx }}][observaciones]" value="{{ $asist->observaciones }}"
                                           class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                           placeholder="Observaciones...">
                                @else
                                    {{ $asist->observaciones ?: '-' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($jornada->estado !== 'realizada')
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                        Guardar Lista
                    </button>
                </div>
            </form>
        @else
            <div class="px-6 py-4 bg-green-50 dark:bg-green-900/20 border-t border-green-200 dark:border-green-800 text-center">
                <p class="text-sm text-green-700 dark:text-green-400 font-medium">
                    ✓ Esta jornada ha sido cerrada. La lista de asistencia es de solo lectura.
                </p>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('table');
        if (!table) return;

        table.addEventListener('change', function(e) {
            if (e.target.type === 'checkbox') {
                const name = e.target.name;
                const match = name.match(/asistencias\[(\d+)\]\[(asistio|mando_sustituto)\]/);
                
                if (match) {
                    const index = match[1];
                    const field = match[2];
                    const otherField = field === 'asistio' ? 'mando_sustituto' : 'asistio';
                    
                    const otherCheckbox = document.querySelector(`input[name="asistencias[${index}][${otherField}]"]`);
                    
                    if (e.target.checked) {
                        if (otherCheckbox) {
                            otherCheckbox.checked = false;
                        }
                        
                        // Si se marca asistio, limpiar campos de sustituto
                        if (field === 'asistio') {
                            const nombreSustituto = document.querySelector(`input[name="asistencias[${index}][nombre_sustituto]"]`);
                            if (nombreSustituto) nombreSustituto.value = '';
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
