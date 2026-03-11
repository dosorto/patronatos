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
                    <div style="font-size: 10pt; margin-top: 0.5rem; color: #555;">ID: {{ str_pad($proyecto->id, 4, '0', STR_PAD_LEFT) }} | Estado: {{ $proyecto->getRawOriginal('estado') == 1 ? 'Activo' : 'Inactivo' }}</div>
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
                    <p style="text-align: justify; margin-bottom: 1rem;">{{ $proyecto->descripcion_beneficiarios }}</p>
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
                <h2>V. Detalle de Presupuestos</h2>
                
                @if($proyecto->presupuestos->count() > 0)
                    @foreach($proyecto->presupuestos as $idx => $presupuesto)
                        <div style="margin-bottom: 2rem;">
                            <h3 style="background-color: #f3f4f6; padding: 0.25rem 0.5rem; display: flex; justify-content: space-between;">
                                <span>
                                    Presupuesto {{ $presupuesto->anio_presupuesto ? 'Año ' . $presupuesto->anio_presupuesto : '#' . ($idx + 1) }}
                                    <span style="font-size: 10pt; font-weight: normal; margin-left: 10px;">
                                        ({{ $presupuesto->es_donacion ? 'Donación' : 'Fondos de Comunidad' }})
                                    </span>
                                </span>
                                @if($presupuesto->presupuesto_total)
                                    <span>L. {{ number_format($presupuesto->presupuesto_total, 2) }}</span>
                                @endif
                            </h3>

                            <table class="doc-table" style="margin-top: 0.5rem; border: none;">
                                <tr style="border: none;">
                                    <td style="border: none; padding-left: 0; width: 50%;"><strong>Monto Financiador:</strong> L. {{ number_format($presupuesto->monto_financiador ?? 0, 2) }}</td>
                                    <td style="border: none; width: 50%;"><strong>Monto Comunidad:</strong> L. {{ number_format($presupuesto->monto_comunidad ?? 0, 2) }}</td>
                                </tr>
                                @if($presupuesto->es_donacion && $presupuesto->cooperante)
                                    <tr style="border: none;">
                                        <td style="border: none; padding-left: 0;" colspan="2"><strong>Cooperante:</strong> {{ $presupuesto->cooperante->nombre }}</td>
                                    </tr>
                                @endif
                            </table>

                            @if($presupuesto->detalles->count() > 0)
                                <p style="font-weight: bold; margin-bottom: 0.5rem; margin-top: 1rem;">Desglose de Rubros:</p>
                                <table class="doc-table">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left;">Descripción / Rubro</th>
                                            <th style="width: 10%;">Cant.</th>
                                            <th style="width: 15%;">Unidad</th>
                                            <th style="width: 20%; text-align: right;">P. Unitario</th>
                                            <th style="width: 20%; text-align: right;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($presupuesto->detalles as $detalle)
                                            <tr>
                                                <td>{{ $detalle->nombre ?? '-' }}</td>
                                                <td style="text-align: center;">{{ $detalle->cantidad ?? '-' }}</td>
                                                <td style="text-align: center;">{{ $detalle->unidad_medida ?? '-' }}</td>
                                                <td style="text-align: right;">{{ $detalle->precio_unitario ? 'L. ' . number_format($detalle->precio_unitario, 2) : '-' }}</td>
                                                <td style="text-align: right; font-weight: bold;">{{ $detalle->total ? 'L. ' . number_format($detalle->total, 2) : '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p style="text-align: center; font-style: italic; color: #666; padding: 2rem;">
                        No existen presupuestos registrados para referenciar en este documento.
                    </p>
                @endif

                {{-- Pie de Página 2 --}}
                <div class="page-footer">
                    <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
                    <span>Página 2</span>
                </div>
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
@endsection