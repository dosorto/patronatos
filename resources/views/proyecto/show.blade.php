@extends('layouts.app')

@section('title', 'Ficha de Proyecto - ' . $proyecto->nombre_proyecto)

@section('content')
<div class="container-fluid max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6 no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Ficha de Proyecto</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Vista tipo documento con historial de actividad.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('proyecto.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 text-sm font-medium">
                Volver
            </a>
            @can('proyecto.edit')
                <a href="{{ route('proyecto.edit', $proyecto) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                    Editar
                </a>
            @endcan
            <a href="{{ route('proyecto.pdf', $proyecto) }}" target="_blank"
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar PDF
            </a>
            <button type="button" onclick="scrollToGestion()"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Gestionar Aportes
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ═══════════════════════════════════════════ --}}
        {{-- DOCUMENTO (Centro / Izquierda - 2 columnas) --}}
        {{-- ═══════════════════════════════════════════ --}}
        <style>
            .document-wrapper {
                display: flex;
                flex-direction: column;
                gap: 2rem;
                align-items: center;
                width: 100%;
                background-color: #f3f4f6; /* Gray background behind the pages */
                padding: 2rem 0;
                border-radius: 0.5rem;
            }
            .dark .document-wrapper {
                background-color: #111827;
            }
            .document-page {
                background: white;
                width: 21cm; /* Standard A4 width */
                min-height: 29.7cm; /* Standard A4 height */
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(0,0,0,0.05);
                display: flex;
                flex-direction: column;
                padding: 2.54cm 2.54cm; /* 1 inch margins all around like Word */
                font-family: 'Times New Roman', Times, serif; /* Classic document font */
                color: #000;
                line-height: 1.5;
                position: relative;
            }
            /* Dark mode override for the page (some prefer white pages even in dark mode, but we'll soften it) */
            .dark .document-page {
                background: #e5e7eb; /* Slightly darker white for dark mode to not blind */
                color: #111827;
            }
            
            /* Typography inside document */
            .document-page h1, .document-page h2, .document-page h3, .document-page h4 {
                font-family: 'Times New Roman', Times, serif;
                color: #000;
                margin-top: 0;
                margin-bottom: 0.5rem;
            }
            .document-page h1 { font-size: 24pt; font-weight: bold; text-align: center; text-transform: uppercase; margin-bottom: 2rem; }
            .document-page h2 { font-size: 16pt; font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 0.25rem; margin-top: 1.5rem; margin-bottom: 1rem; text-transform: uppercase;}
            .document-page h3 { font-size: 14pt; font-weight: bold; margin-top: 1rem; }
            .document-page p, .document-page span, .document-page td, .document-page th { font-size: 12pt; }
            
            /* Tables */
            .doc-table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
            .doc-table th, .doc-table td { border: 1px solid #000; padding: 0.25rem 0.5rem; vertical-align: top; }
            .doc-table th { background-color: #f3f4f6; font-weight: bold; text-align: center; }
            .dark .doc-table th { background-color: #d1d5db; }
            
            .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
            .info-item { display: flex; flex-direction: column; }
            .info-label { font-weight: bold; font-size: 11pt; }
            .info-value { font-size: 12pt; border-bottom: 1px dotted #999; padding-bottom: 2px;}

            /* Page numbering */
            .page-footer {
                position: absolute;
                bottom: 1cm;
                left: 2.54cm;
                right: 2.54cm;
                display: flex;
                justify-content: space-between;
                font-size: 10pt;
                color: #666;
            }

            @media print {
                .document-wrapper { background: none; padding: 0; gap: 0; display: block; }
                .document-page {
                    width: 100%;
                    max-width: 100%;
                    min-height: auto;
                    box-shadow: none !important;
                    margin: 0;
                    padding: 0;
                    page-break-after: always;
                    background: white !important;
                }
                .document-page:last-child { page-break-after: auto; }
                .page-footer { bottom: 0; left: 0; right: 0; }
            }
        </style>

        <div class="lg:col-span-2 document-wrapper" id="proyecto-documento">
            
            {{-- ================= PÁGINA 1 ================= --}}
            <div class="document-page">
                
                {{-- Encabezado Formal --}}
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="font-size: 14pt; font-weight: bold; text-transform: uppercase;">{{ $proyecto->organizacion->name ?? 'Sistema de Patronatos' }}</div>
                    <div style="font-size: 12pt;">Ficha Técnica de Proyecto Oficial</div>
                    <div style="font-size: 10pt; margin-top: 0.5rem; color: #555;">ID: {{ str_pad($proyecto->id, 4, '0', STR_PAD_LEFT) }}</div>
                </div>

                <h1>{{ $proyecto->nombre_proyecto }}</h1>
                @if($proyecto->tipo_proyecto)
                    <p style="text-align: center; font-style: italic; margin-top: -1.5rem; margin-bottom: 2rem;">Tipo: {{ $proyecto->tipo_proyecto }}</p>
                @endif

                {{-- ── I. Información General ── --}}
                <h2>I. Información General</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Número de Acta:</span>
                        <span class="info-value">{{ $proyecto->numero_acta ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Responsable del Proyecto:</span>
                        <span class="info-value">
                            {{ $proyecto->miembroResponsable->miembro->persona->nombre_completo ?? 'N/A' }}
                            @if($proyecto->miembroResponsable?->cargo)
                                ({{ $proyecto->miembroResponsable->cargo }})
                            @endif
                            <br>
                            <span style="font-size: 10pt; color: #555;">Tel: {{ $proyecto->miembroResponsable?->miembro->persona->telefono ?? 'N/A' }}</span>
                        </span>
                    </div>
                </div>
                <div class="info-grid">
                    <div class="info-item" style="grid-column: span 2;">
                        <span class="info-label">Ubicación Geográfica:</span>
                        <span class="info-value">
                            {{ $proyecto->municipio->nombre ?? '' }}{{ $proyecto->municipio && $proyecto->departamento ? ', ' : '' }}{{ $proyecto->departamento->nombre ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                {{-- ── II. Fechas ── --}}
                <h2>II. Fechas del Proyecto</h2>
                <table class="doc-table">
                    <tr>
                        <th style="width: 33%">Aprobación Asamblea</th>
                        <th style="width: 33%">Fecha de Inicio</th>
                        <th style="width: 34%">Fecha de Finalización</th>
                    </tr>
                    <tr>
                        <td style="text-align: center">{{ $proyecto->fecha_aprobacion_asamblea?->format('d/m/Y') ?? 'N/A' }}</td>
                        <td style="text-align: center">{{ $proyecto->fecha_inicio?->format('d/m/Y') ?? 'N/A' }}</td>
                        <td style="text-align: center">{{ $proyecto->fecha_fin?->format('d/m/Y') ?? 'N/A' }}</td>
                    </tr>
                </table>

                {{-- ── III. Descripción y Justificación ── --}}
                @if($proyecto->descripcion || $proyecto->justificacion)
                    <h2>III. Descripción y Justificación</h2>
                    @if($proyecto->descripcion)
                        <p style="text-align: justify; margin-bottom: 1rem;">
                            <strong>Descripción: </strong> {{ $proyecto->descripcion }}
                        </p>
                    @endif
                    @if($proyecto->justificacion)
                        <p style="text-align: justify; margin-bottom: 1rem;">
                            <strong>Justificación: </strong> {{ $proyecto->justificacion }}
                        </p>
                    @endif
                @endif

                {{-- ── IV. Beneficiarios ── --}}
                <h2>IV. Beneficiarios</h2>
                @if($proyecto->descripcion_beneficiarios)
                    <p style="text-align: justify; margin-bottom: 1rem;">
                        <strong>Descripción: </strong> {{ $proyecto->descripcion_beneficiarios }}
                    </p>
                @endif
                <table class="doc-table" style="width: 80%; margin: 0 auto;">
                    <tr>
                        <th>Hombres</th>
                        <th>Mujeres</th>
                        <th>Niños</th>
                        <th>Familias</th>
                        <th>Total Personas</th>
                    </tr>
                    <tr>
                        <td style="text-align: center">{{ $proyecto->benef_hombres ?? 0 }}</td>
                        <td style="text-align: center">{{ $proyecto->benef_mujeres ?? 0 }}</td>
                        <td style="text-align: center">{{ $proyecto->benef_ninos ?? 0 }}</td>
                        <td style="text-align: center">{{ $proyecto->benef_familias ?? 0 }}</td>
                        <td style="text-align: center; font-weight: bold;">
                            {{ ($proyecto->benef_hombres ?? 0) + ($proyecto->benef_mujeres ?? 0) + ($proyecto->benef_ninos ?? 0) }}
                        </td>
                    </tr>
                </table>

                {{-- Pie de Página 1 --}}
                <div class="page-footer">
                    <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
                    <span>Página 1</span>
                </div>
            </div>

            {{-- ================= PÁGINA 2 ================= --}}
            <div class="document-page">
                {{-- Encabezado Continuación --}}
                <div style="font-size: 10pt; color: #555; text-align: right; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 1rem;">
                    Continuación: {{ $proyecto->nombre_proyecto }}
                </div>

                {{-- ── V. Presupuestos ── --}}
                <h2>V. Presupuesto del Proyecto</h2>
                
                @if($proyecto->presupuestos->count() > 0)
                    @php
                        $presupuesto = $proyecto->presupuestos->first();
                        $totalComunidad = $presupuesto->detalles->where('es_donacion', false)->sum('total');
                        $totalFinanciador = $presupuesto->detalles->where('es_donacion', true)->sum('total');
                        $granTotal = $totalComunidad + $totalFinanciador;
                    @endphp
                    <div style="margin-bottom: 2rem;">
                        <h3 style="background-color: #f3f4f6; padding: 0.25rem 0.5rem; display: flex; justify-content: space-between;">
                            <span>Desglose General (Año {{ $presupuesto->anio_presupuesto ?? now()->year }})</span>
                            <span>Total: L. {{ number_format($granTotal, 2) }}</span>
                        </h3>

                        <table class="doc-table" style="margin-top: 0.5rem; border: none;">
                            <tr style="border: none;">
                                <td style="border: none; padding-left: 0; width: 50%;"><strong>Aporte Comunidad:</strong> L. {{ number_format($totalComunidad, 2) }}</td>
                                <td style="border: none; width: 50%;"><strong>Aporte Financiadores:</strong> L. {{ number_format($totalFinanciador, 2) }}</td>
                            </tr>
                        </table>

                        @if($presupuesto->detalles->count() > 0)
                            <table class="doc-table" style="margin-top: 1rem;">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="text-align: left; width: 30%;">Descripción / Rubro</th>
                                        <th style="width: 10%;">Cant.</th>
                                        <th style="width: 10%;">Unidad</th>
                                        <th style="width: 15%; text-align: right;">P. Unitario</th>
                                        <th style="width: 15%; text-align: center;">Aporte / Cooperante</th>
                                        <th style="width: 15%; text-align: right;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presupuesto->detalles as $idx => $detalle)
                                        <tr>
                                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                                            <td>{{ $detalle->nombre ?? '-' }}</td>
                                            <td style="text-align: center;">{{ $detalle->cantidad ?? '-' }}</td>
                                            <td style="text-align: center;">{{ $detalle->unidad_medida ?? '-' }}</td>
                                            <td style="text-align: right;">{{ $detalle->precio_unitario ? 'L. ' . number_format($detalle->precio_unitario, 2) : '-' }}</td>
                                            <td style="text-align: center; font-size: 0.9em; color: #555;">
                                                @if($detalle->es_donacion && $detalle->cooperante)
                                                    {{ $detalle->cooperante->nombre }}
                                                @else
                                                    Comunidad
                                                @endif
                                            </td>
                                            <td style="text-align: right; font-weight: bold;">{{ $detalle->total ? 'L. ' . number_format($detalle->total, 2) : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @endif
                
                {{-- ── VI. Resumen de Aportaciones ── --}}
                @if($proyecto->presupuestos->count() > 0)
                    @php
                        $presupuesto = $proyecto->presupuestos->first();
                        $resumenAportes = [];
                        $totalProyecto = 0;

                        foreach($presupuesto->detalles as $detalle) {
                            $totalProyecto += $detalle->total;
                            
                            if ($detalle->es_donacion && $detalle->cooperante) {
                                $key = 'coop_' . $detalle->id_cooperante;
                                $nombre = $detalle->cooperante->nombre;
                            } else {
                                $key = 'comunidad';
                                $nombre = 'Comunidad (Contrapartida local)';
                            }
                            
                            if (!isset($resumenAportes[$key])) {
                                $resumenAportes[$key] = [
                                    'nombre' => $nombre,
                                    'monto' => 0
                                ];
                            }
                            $resumenAportes[$key]['monto'] += $detalle->total;
                        }
                        
                        // Ordenar: Comunidad primero, luego cooperantes por monto descendente
                        uasort($resumenAportes, function($a, $b) {
                            if ($a['nombre'] === 'Comunidad (Contrapartida local)') return -1;
                            if ($b['nombre'] === 'Comunidad (Contrapartida local)') return 1;
                            return $b['monto'] <=> $a['monto'];
                        });
                    @endphp

                    <div style="margin-top: 2rem;">
                        <h2>VI. Resumen de Aportaciones por Socios Estratégicos</h2>
                        <p style="font-size: 10pt; color: #555; margin-bottom: 1rem;">
                            A continuación se detalla la consolidación de aportes financieros por cada entidad participante en el proyecto:
                        </p>
                        <table class="doc-table">
                            <thead>
                                <tr>
                                    <th style="width: 50%; text-align: left;">Socio / Cooperante</th>
                                    <th style="width: 25%; text-align: right;">Monto Aportado</th>
                                    <th style="width: 25%; text-align: center;">% Participación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resumenAportes as $item)
                                    <tr>
                                        <td>{{ $item['nombre'] }}</td>
                                        <td style="text-align: right;">L. {{ number_format($item['monto'], 2) }}</td>
                                        <td style="text-align: center;">
                                            {{ $totalProyecto > 0 ? number_format(($item['monto'] / $totalProyecto) * 100, 1) : '0.0' }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f3f4f6; font-weight: bold;">
                                    <td style="text-align: right;">VALOR TOTAL DEL PROYECTO</td>
                                    <td style="text-align: right;">L. {{ number_format($totalProyecto, 2) }}</td>
                                    <td style="text-align: center;">100.0%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif

                {{-- Pie de Página 2 --}}
                <div class="page-footer">
                    <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
                    <span>Página 2</span>
                </div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- APORTES Y JORNADAS (Full-width tabs)       --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div id="seccion-para-scroll" class="lg:col-span-3 no-print"></div> 
        <div style="height: 50px;"></div>
        <div id="seccion-gestion-proyecto" class="lg:col-span-3 no-print">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                {{-- Tab Buttons --}}
                <div class="flex border-b border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="switchShowTab('aportaciones')" id="show-tab-btn-aportaciones"
                            class="px-6 py-3 text-sm font-bold border-b-2 border-blue-600 text-blue-600 dark:text-blue-400 uppercase tracking-wider transition-colors">
                        Aportaciones Monetarias
                        @if($aportaciones->total() > 0)
                            <span class="ml-1 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $aportaciones->total() }}</span>
                        @endif
                    </button>
                    <button type="button" onclick="switchShowTab('jornadas')" id="show-tab-btn-jornadas"
                            class="px-6 py-3 text-sm font-bold border-b-2 border-transparent text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                        Jornadas de Trabajo
                        @if($jornadas->total() > 0)
                            <span class="ml-1 px-2 py-0.5 bg-gray-100 text-gray-800 text-xs rounded-full">{{ $jornadas->total() }}</span>
                        @endif
                    </button>
                </div>

                {{-- Tab Content: Aportaciones --}}
                <div id="show-tab-aportaciones" class="p-6">
                    @if($proyecto->configuracionAportacion)
                        @php
                            $config = $proyecto->configuracionAportacion;
                            $totalAsignado = $proyecto->aportaciones->sum('monto_asignado');
                            $totalPagado = $proyecto->aportaciones->sum('monto_pagado');
                            $pctPagado = $totalAsignado > 0 ? round(($totalPagado / $totalAsignado) * 100) : 0;
                            $totalPendientes = $proyecto->aportaciones->where('estado', 'pendiente')->count();
                            $totalParciales = $proyecto->aportaciones->where('estado', 'parcial')->count();
                            $totalPagados = $proyecto->aportaciones->where('estado', 'pagado')->count();
                        @endphp

                        {{-- Resumen --}}
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase">Distribución</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200 capitalize">{{ $config->tipo_distribucion }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase">Monto Total</p>
                                <p class="text-sm font-bold text-blue-600">L. {{ number_format($config->monto_total_requerido, 2) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase">Recaudado</p>
                                <p class="text-sm font-bold text-green-600">L. {{ number_format($totalPagado, 2) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase">Pendiente</p>
                                <p class="text-sm font-bold text-red-600">L. {{ number_format($totalAsignado - $totalPagado, 2) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase">Fecha Límite</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $config->fecha_limite?->format('d/m/Y') ?? 'N/A' }}</p>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mb-6">
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                <span>Progreso de recaudación</span>
                                <span>{{ $pctPagado }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full transition-all duration-500" style="width: {{ min($pctPagado, 100) }}%"></div>
                            </div>
                            <div class="flex gap-4 mt-2 text-xs">
                                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Pendientes: {{ $totalPendientes }}</span>
                                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-500"></span> Parciales: {{ $totalParciales }}</span>
                                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Pagados: {{ $totalPagados }}</span>
                            </div>
                        </div>

                        {{-- Tabla de aportaciones --}}
                        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    <tr>
                                        <th class="px-3 py-2">#</th>
                                        <th class="px-3 py-2">Miembro</th>
                                        <th class="px-3 py-2 text-right">Asignado</th>
                                        <th class="px-3 py-2 text-right">Pagado</th>
                                        <th class="px-3 py-2 text-right">Saldo</th>
                                        <th class="px-3 py-2 text-center">Estado</th>
                                        <th class="px-3 py-2 text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($aportaciones as $idx => $aport)
                                        @php
                                            $saldo = $aport->monto_asignado - $aport->monto_pagado;
                                            $badgeClass = match($aport->estado) {
                                                'pagado' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                                'parcial' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                default => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                            };
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-3 py-2 text-gray-500">{{ $idx + 1 + ($aportaciones->currentPage() - 1) * $aportaciones->perPage() }}</td>
                                            <td class="px-3 py-2 font-medium text-gray-800 dark:text-gray-200">{{ $aport->miembro?->persona?->nombre_completo ?? 'N/A' }}</td>
                                            <td class="px-3 py-2 text-right">L. {{ number_format($aport->monto_asignado, 2) }}</td>
                                            <td class="px-3 py-2 text-right text-green-600 font-semibold">L. {{ number_format($aport->monto_pagado, 2) }}</td>
                                            <td class="px-3 py-2 text-right {{ $saldo > 0 ? 'text-red-600' : 'text-green-600' }}">L. {{ number_format(max($saldo, 0), 2) }}</td>
                                            <td class="px-3 py-2 text-center">
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $badgeClass }}">{{ ucfirst($aport->estado ?? 'pendiente') }}</span>
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                @if($aport->estado !== 'pagado')
                                                    <a href="{{ route('cobro.create') }}"
                                                            class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                                                        Cobrar en Caja
                                                    </a>
                                                @else
                                                    <span class="text-xs text-green-600">✓ Completado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="px-3 py-4 text-center text-gray-500 italic">No hay aportaciones configuradas.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $aportaciones->appends(['tab' => 'aportaciones', 'page_jornadas' => request('page_jornadas')])->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="text-gray-500 dark:text-gray-400">No se han configurado aportaciones para este proyecto.</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Puedes configurarlas editando el proyecto.</p>
                        </div>
                    @endif
                </div>

                {{-- Tab Content: Jornadas --}}
                <div id="show-tab-jornadas" class="p-6 hidden">
                    {{-- Botón nueva jornada --}}
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Jornadas Programadas</h3>
                        <button type="button" onclick="document.getElementById('modal-nueva-jornada').classList.remove('hidden')"
                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Nueva Jornada
                        </button>
                    </div>

                    @if($jornadas->total() > 0)
                        <div class="space-y-3">
                            @foreach($jornadas as $jornada)
                                @php
                                    $estadoJornada = $jornada->estado ?? 'programada';
                                    $jornadaBadge = $estadoJornada === 'realizada'
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                        : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                                    $asistieron = $jornada->asistencias->where('asistio', true)->count();
                                    $totalConvocados = $jornada->asistencias->count();
                                @endphp
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center gap-4">
                                        <div class="flex flex-col items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-lg text-xs font-bold">
                                            <span class="text-lg leading-none">{{ $jornada->numero_jornada }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm">
                                                Jornada #{{ $jornada->numero_jornada }}
                                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $jornadaBadge }}">{{ ucfirst($estadoJornada) }}</span>
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $jornada->fecha?->format('d/m/Y') ?? 'Sin fecha' }}
                                                @if($jornada->hora_inicio) · {{ \Carbon\Carbon::parse($jornada->hora_inicio)->format('H:i') }} @endif
                                                @if($jornada->descripcion) · {{ Str::limit($jornada->descripcion, 50) }} @endif
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                Asistencia: {{ $asistieron }}/{{ $totalConvocados }} convocados
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('proyecto.jornadas.show', [$proyecto, $jornada]) }}"
                                           class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                                            Ver Planilla
                                        </a>
                                        @if($estadoJornada !== 'realizada')
                                            <button type="button" 
                                                    onclick="abrirModalConfirmarCerrar('{{ route('proyecto.jornadas.cerrar', [$proyecto, $jornada]) }}', {{ $jornada->numero_jornada }})"
                                                    class="px-3 py-1.5 bg-yellow-600 text-white text-xs rounded-lg hover:bg-yellow-700 transition-colors">
                                                Cerrar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $jornadas->appends(['tab' => 'jornadas', 'page_aportes' => request('page_aportes')])->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-gray-500 dark:text-gray-400">No hay jornadas de trabajo registradas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        {{-- Modal Nueva Jornada --}}
        <div id="modal-nueva-jornada" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center no-print">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg mx-4">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Nueva Jornada de Trabajo</h3>
                    <button onclick="document.getElementById('modal-nueva-jornada').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <form method="POST" action="{{ route('proyecto.jornadas.store', $proyecto) }}" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha *</label>
                                <input type="date" name="fecha" required class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hora Inicio</label>
                                <input type="time" name="hora_inicio" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                            <input type="text" name="descripcion" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Convocatoria *</label>
                            <select name="tipo_convocatoria" id="jornada-conv-tipo" onchange="document.getElementById('jornada-conv-miembros').classList.toggle('hidden', this.value !== 'manual')"
                                    required class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="todos">Todos los miembros activos</option>
                                <option value="manual">Seleccionar manualmente</option>
                            </select>
                        </div>
                        <div id="jornada-conv-miembros" class="hidden max-h-40 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                            @foreach($miembrosActivos as $m)
                                <label class="flex items-center gap-2 py-1 cursor-pointer">
                                    <input type="checkbox" name="miembros[]" value="{{ $m->id }}" class="text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $m->persona->nombre_completo ?? 'N/A' }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('modal-nueva-jornada').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">Cancelar</button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white text-sm font-bold rounded-lg hover:bg-green-700 transition-colors">Crear Jornada</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- HISTORIAL DE CAMBIOS (Derecha - 1 columna) --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="lg:col-span-1 no-print">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 sticky top-6">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">Historial de Cambios</h2>
                </div>
                <div class="p-6 max-h-[75vh] overflow-y-auto">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse($proyecto->auditLogs as $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                @php
                                                    $colors = [
                                                        'created' => 'bg-green-500',
                                                        'updated' => 'bg-blue-500',
                                                        'deleted' => 'bg-red-500',
                                                    ];
                                                @endphp
                                                <span class="h-8 w-8 rounded-full {{ $colors[$log->event] ?? 'bg-gray-500' }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    @if($log->event === 'created')
                                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                                                    @elseif($log->event === 'updated')
                                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                    @else
                                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 flex-col pt-1.5">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="font-bold text-gray-900 dark:text-white uppercase text-xs">{{ $log->event === 'created' ? 'Registro inicial' : ($log->event === 'updated' ? 'Actualización' : 'Eliminación') }}</span> por
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $log->user_name ?? ($log->user->name ?? 'Sistema') }}</span>
                                                </p>
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                                    {{ $log->created_at->format('d/m/y H:i') }} · {{ $log->created_at->diffForHumans() }}
                                                </span>
                                                @if($log->event === 'updated' && $log->new_values)
                                                    <div class="mt-2 text-[11px] bg-gray-50 dark:bg-gray-900 px-3 py-2 rounded border border-gray-200 dark:border-gray-700">
                                                        @foreach($log->new_values as $key => $value)
                                                            @if(!in_array($key, ['updated_by', 'created_by', 'deleted_by']))
                                                                <div class="flex items-center gap-1 flex-wrap">
                                                                    <span class="font-bold text-gray-400 uppercase tracking-tighter">{{ str_replace('_', ' ', $key) }}:</span>
                                                                    <span class="text-red-400 line-through">{{ is_array($log->old_values[$key] ?? '') ? '...' : ($log->old_values[$key] ?? 'N/A') }}</span>
                                                                    <svg class="w-3 h-3 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                                    <span class="text-green-500 font-bold">{{ is_array($value) ? '...' : $value }}</span>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400 italic py-4 text-center">No se han registrado movimientos.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        body {
            background-color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print {
            display: none !important;
        }

        .container-fluid {
            max-width: 100% !important;
            padding: 0 !important;
        }

        .lg\:col-span-2 {
            grid-column: span 3 / span 3 !important;
        }

        #proyecto-documento {
            width: 100% !important;
        }

        #proyecto-documento > div {
            box-shadow: none !important;
            border: none !important;
        }

        .grid {
            display: grid !important;
        }
    }
</style>

        {{-- Modal Confirmar Cierre de Jornada --}}
        <div id="modal-confirm-cerrar" class="fixed inset-0 z-[60] hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center no-print">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">¿Cerrar Jornada #<span id="cerrar-jornada-numero"></span>?</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Esta acción marcará la jornada como "Realizada" y bloqueará cualquier edición futura en la lista de asistencia. Esta operación no se puede deshacer.</p>
                    
                    <form id="form-confirm-cerrar" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-3 justify-center">
                            <button type="button" onclick="document.getElementById('modal-confirm-cerrar').classList.add('hidden')"
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-sm font-bold rounded-lg hover:bg-yellow-700 transition-colors shadow-lg shadow-yellow-600/20">
                                Sí, Cerrar Jornada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<script>
    function switchShowTab(tab) {
        const tabs = ['aportaciones', 'jornadas'];
        tabs.forEach(t => {
            document.getElementById(`show-tab-${t}`).classList.toggle('hidden', t !== tab);
            const btn = document.getElementById(`show-tab-btn-${t}`);
            if (t === tab) {
                btn.classList.add('border-blue-600', 'text-blue-600', 'dark:text-blue-400');
                btn.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            } else {
                btn.classList.remove('border-blue-600', 'text-blue-600', 'dark:text-blue-400');
                btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            }
        });
    }

    function abrirModalConfirmarCerrar(url, numero) {
        document.getElementById('form-confirm-cerrar').action = url;
        document.getElementById('cerrar-jornada-numero').textContent = numero;
        document.getElementById('modal-confirm-cerrar').classList.remove('hidden');
    }

    function scrollToGestion() {
        switchShowTab('aportaciones');
        const target = document.getElementById('seccion-para-scroll');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Auto-switch tab on load based on URL parameter and get seccion para scroll
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab === 'jornadas') {
            switchShowTab('jornadas');
        } else {
            switchShowTab('aportaciones');
        }
    };
</script>
@endsection