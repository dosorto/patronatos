@extends('layouts.app')

@section('title', 'Recibo - ' . $recibo->nombre)

@php
    $nombreOrg = $organization?->name ?? '';
    $omitidas = ['DE', 'LA', 'EL', 'DEL', 'LOS', 'LAS', 'Y'];

    $siglas = '';
    foreach (explode(' ', $nombreOrg) as $p) {
        $p = trim($p);
        if ($p !== '' && !in_array(strtoupper($p), $omitidas)) {
            $siglas .= strtoupper(substr($p, 0, 1));
        }
    }

    if ($siglas === '') {
        $siglas = 'ORG';
    }

    $logoUrl = null;

    if ($organization && $organization->logo) {
        $logoValue = ltrim($organization->logo, '/');

        if (str_starts_with($logoValue, 'logos/')) {
            $logoUrl = asset('storage/' . $logoValue);
        } else {
            $logoUrl = asset('storage/logos/' . $logoValue);
        }
    }

    $esPago = !is_null($recibo->pago_id);
    $esCobro = !is_null($recibo->cobro_id);
    $esDonacion = false;

    $cooperanteDonacion = null;
    $nombreRecibidoDe = '';
    $subtituloRecibidoDe = '';
    $conceptos = collect();
    $tituloRecibo = 'RECIBO';
    $tipoTexto = 'INGRESO';
    $colorTitulo = 'text-red-600 dark:text-red-400';
    $rutaVolver = route('cobro.index');

    if ($esCobro && $recibo->cobro) {
        $esDonacion = is_null($recibo->cobro->miembro_id) || $recibo->cobro->tipo_cobro === 'donacion';

        if ($esDonacion) {
            $primerDetalle = $recibo->cobro->detallesCobros->first();
            $cooperanteDonacion = $primerDetalle?->cooperante;

            $nombreRecibidoDe = $cooperanteDonacion?->nombre ?? 'Cooperante';
            $subtituloRecibidoDe = $cooperanteDonacion?->tipo_cooperante ?? 'Donación registrada';
            $tituloRecibo = 'RECIBO DE DONACIÓN';
            $tipoTexto = 'DONACIÓN';
            $colorTitulo = 'text-amber-600 dark:text-amber-400';
        } else {
            $nombreRecibidoDe = ($recibo->cobro->miembro->persona->nombre ?? '') . ' ' . ($recibo->cobro->miembro->persona->apellido ?? '');
            $subtituloRecibidoDe = 'DNI: ' . ($recibo->cobro->miembro->persona->dni ?? '');
        }

        $conceptos = $recibo->cobro->detallesCobros ?? collect();
    }

    if ($esPago && $recibo->pago) {
        $tituloRecibo = 'RECIBO DE PAGO';
        $tipoTexto = 'EGRESO';
        $colorTitulo = 'text-blue-600 dark:text-blue-400';
        $rutaVolver = route('pago.index');

        $nombreRecibidoDe = $recibo->pago->nombre_persona ?: 'Pago registrado';
        $subtituloRecibidoDe = $recibo->pago->descripcion ?: 'Egreso generado en sistema';

        $conceptos = $recibo->pago->detalles ?? collect();
    }
@endphp

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-900 py-12 px-4">
    <div class="max-w-6xl mx-auto">

        {{-- Botones --}}
        <div class="flex gap-4 justify-end mb-6">
            <a href="{{ $rutaVolver }}"
               class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition-all">
                Volver
            </a>

            <button onclick="window.print()"
               class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Imprimir Recibo
            </button>
        </div>

        {{-- Recibo --}}
        <div
            class="bg-white dark:bg-slate-800 rounded-lg shadow-2xl overflow-hidden border-2 border-dashed border-gray-700 dark:border-slate-500"
            id="recibo"
        >
            <div class="p-8">

                {{-- HEADER --}}
                <div class="grid grid-cols-3 gap-8 mb-8 pb-8 border-b-2 border-gray-300 dark:border-slate-600">

                    {{-- Organización --}}
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-gray-300 dark:bg-slate-700 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                            @if($logoUrl)
                                <img
                                    src="{{ $logoUrl }}"
                                    alt="Logo organización"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <svg class="w-8 h-8 text-gray-600 dark:text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                                </svg>
                            @endif
                        </div>

                        <h1 class="text-sm font-black text-gray-900 dark:text-white uppercase leading-tight">
                            {{ $organization?->name ?? 'ORGANIZACIÓN' }}
                        </h1>
                    </div>

                    {{-- Título --}}
                    <div class="text-center">
                        <h2 class="text-3xl font-black text-gray-900 dark:text-white">
                            {{ $tituloRecibo }}
                        </h2>
                        <h3 class="text-2xl font-black {{ $colorTitulo }} mt-1">
                            Nº {{ str_pad($recibo->correlativo, 6, '0', STR_PAD_LEFT) }}
                        </h3>
                        <p class="text-sm uppercase text-gray-600 dark:text-slate-300 mt-2 tracking-widest">
                            {{ $tipoTexto }}
                        </p>
                    </div>

                    {{-- Datos --}}
                    <div class="text-right space-y-3">
                        <div>
                            <p class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase">Fecha</p>
                            <input
                                value="{{ $recibo->fecha_emision->format('d/m/Y') }}"
                                readonly
                                class="w-full text-lg font-black border-b-2 border-gray-900 dark:border-slate-400 text-right bg-transparent text-gray-900 dark:text-white"
                            >
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase">Número</p>
                            <input
                                value="{{ $siglas }}-{{ $recibo->anio }}-{{ str_pad($recibo->correlativo, 6, '0', STR_PAD_LEFT) }}"
                                readonly
                                class="w-full text-lg font-black border-b-2 border-gray-900 dark:border-slate-400 text-right bg-transparent text-gray-900 dark:text-white"
                            >
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase">Importe</p>
                            <input
                                value="L. {{ number_format($recibo->monto, 2) }}"
                                readonly
                                class="w-full text-lg font-black border-b-2 border-gray-900 dark:border-slate-400 text-right bg-transparent text-gray-900 dark:text-white"
                            >
                        </div>
                    </div>
                </div>

                {{-- CONTENIDO --}}
                <div class="space-y-6 mb-8 pb-8 border-b-2 border-gray-300 dark:border-slate-600">

                    <div class="grid grid-cols-2 gap-8">

                        {{-- RECIBÍ DE / PAGADO A --}}
                        <div>
                            <p class="text-sm font-black text-gray-900 dark:text-white uppercase mb-2 tracking-wider">
                                @if($esPago)
                                    Pagado a:
                                @else
                                    Recibí de:
                                @endif
                            </p>
                            <div class="border-b border-gray-900 dark:border-slate-400 pb-3">
                                <p class="text-3xl font-black text-gray-900 dark:text-white leading-tight">
                                    {{ $nombreRecibidoDe }}
                                </p>
                                @if($subtituloRecibidoDe)
                                    <p class="text-lg font-semibold text-gray-700 dark:text-slate-200 mt-1">
                                        {{ $subtituloRecibidoDe }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- MONTO --}}
                        <div>
                            <p class="text-sm font-black text-gray-900 dark:text-white uppercase mb-2 tracking-wider">
                                La suma de:
                            </p>
                            <div class="border-2 border-gray-900 dark:border-slate-400 p-4 text-center rounded-sm">
                                <p class="text-4xl font-black text-gray-900 dark:text-white">
                                    L. {{ number_format($recibo->monto, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- CONCEPTOS --}}
                    <div>
                        <p class="text-sm font-black text-gray-900 dark:text-white uppercase mb-3 tracking-wider">
                            Por Concepto:
                        </p>
                        <div class="space-y-2">
                            @foreach($conceptos as $detalle)
                                <div class="flex justify-between items-center border-b border-gray-300 dark:border-slate-600 pb-2">
                                    <span class="text-lg font-bold uppercase text-gray-900 dark:text-white">
                                        {{ $detalle->concepto }}
                                    </span>
                                    <span class="text-lg font-black text-gray-900 dark:text-white">
                                        L. {{ number_format($detalle->monto, 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- FIRMAS --}}
                <div class="grid grid-cols-2 gap-12">
                    <div class="text-center">
                        <div class="h-12"></div>
                        <div class="border-t-2 border-gray-900 dark:border-slate-400 pt-1">
                            <p class="text-xs font-bold uppercase text-gray-900 dark:text-white">
                                @if($esPago)
                                    Recibí Conforme
                                @else
                                    Recibí Conforme
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="h-12"></div>
                        <div class="border-t-2 border-gray-900 dark:border-slate-400 pt-1">
                            <p class="text-xs font-bold uppercase text-gray-900 dark:text-white">
                                @if($esPago)
                                    Entregué Conforme
                                @else
                                    Entregué Conforme
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="mt-6 pt-4 border-t border-gray-300 dark:border-slate-600 text-center text-xs text-gray-500 dark:text-slate-300">
                    Generado: {{ now()->format('d/m/Y H:i') }} |
                    Usuario: {{ $recibo->user->name ?? auth()->user()->name }} |
                    Correlativo: {{ $recibo->anio }}-{{ str_pad($recibo->correlativo, 6, '0', STR_PAD_LEFT) }}
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        /* 1. RESET DE ESTRUCTURA: Desactivar el layout de la plantilla (sidebar/navbar) */
        .flex.h-screen, 
        .relative.flex.flex-1.flex-col,
        body, html {
            display: block !important;
            height: auto !important;
            width: 100% !important;
            overflow: visible !important;
            margin: 0 !important;
            padding: 0 !important;
            background-color: white !important;
        }

        /* 2. OCULTAR TODO LO QUE NO ES EL RECIBO */
        /* Ocultamos por ID y clase los componentes de layouts.app */
        #sidebar, #sidebar-toggle, #alert-success, #alert-error,
        .navbar, .flex.gap-4, .no-print, header, nav, aside {
            display: none !important;
        }

        /* 3. EXPANDIR CONTENEDORES INTERNOS */
        main, .mx-auto, .max-w-screen-2xl {
            max-width: none !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* 4. DISEÑO DEL RECIBO: Ocupar exactamente la media hoja */
        #recibo {
            width: 100% !important;
            min-height: 100vh !important; /* Forzar que ocupe todo el espacio asignado por @page */
            margin: 0 !important;
            padding: 1cm !important;
            border: none !important; /* El límite es el papel */
            display: block !important;
            visibility: visible !important;
            background: white !important;
        }

        /* Compactar secciones internas para que no desborden */
        #recibo .mb-8, #recibo .pb-8 { margin-bottom: 0.4rem !important; padding-bottom: 0.4rem !important; }
        #recibo .space-y-6 > * + * { margin-top: 0.4rem !important; }
        #recibo .h-12 { height: 1.2rem !important; }
        
        .text-gray-900, .text-gray-800, .text-gray-700 { color: black !important; }

        /* 5. CONFIGURACIÓN DE PÁGINA: LA CLAVE PARA ELIMINAR SOBRANTES */
        @page {
            size: A5 landscape; /* Esto define el papel como media hoja A4 */
            margin: 0 !important; /* Elimina márgenes de encabezados/pies del navegador */
        }
    }
</style>
@endsection