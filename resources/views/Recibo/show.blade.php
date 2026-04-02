@extends('layouts.app')

@section('title', 'Recibo - ' . $recibo->nombre)

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4">
    <div class="max-w-6xl mx-auto">
       {{-- Botones de acción --}}
        <div class="flex gap-4 justify-end mb-6">
            <a href="{{ route('cobro.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition-all">
                Volver
            </a>
            <a href="{{ route('recibo.pdf', $recibo->id) }}" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Descargar PDF
            </a>
        </div>

        {{-- Recibo Horizontal --}}
        <div class="bg-white rounded-lg shadow-2xl overflow-hidden" id="recibo" style="border: 3px dashed #333;">
            <div class="p-8">
                {{-- Header con Logo y Datos - Horizontal --}}
                <div class="grid grid-cols-3 gap-8 mb-8 pb-8 border-b-2 border-gray-300">
                    {{-- Logo y Organización --}}
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-sm font-black text-gray-900 uppercase tracking-tight leading-tight">{{ $organization?->name ?? 'ORGANIZACIÓN' }}</h1>
                            
                        </div>
                    </div>

                    {{-- Titulo RECIBO --}}
                    <div class="text-center flex flex-col justify-center">
                        <h2 class="text-2xl font-black text-gray-900 uppercase">RECIBO</h2>
                        <h3 class="text-xl font-black text-red-600 uppercase mt-1">Nº {{ str_pad($recibo->correlativo, 6, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-xs text-gray-600 uppercase tracking-widest font-semibold mt-2">INGRESO</p>
                    </div>

                    {{-- Datos del Recibo - Derecha --}}
                    <div class="text-right space-y-2">
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-widest font-bold">Fecha</p>
                            <input type="text" value="{{ $recibo->fecha_emision->format('d/m/Y') }}" class="w-full text-right text-sm font-bold border-b-2 border-gray-900 bg-white px-2 py-1" readonly>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-widest font-bold">Número</p>
                            <input type="text" value="ACP-{{ $recibo->anio }}" class="w-full text-right text-sm font-bold border-b-2 border-gray-900 bg-white px-2 py-1" readonly>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-widest font-bold">Importe</p>
                            <input type="text" value="L. {{ number_format($recibo->monto, 2) }}" class="w-full text-right text-sm font-bold border-b-2 border-gray-900 bg-white px-2 py-1" readonly>
                        </div>
                    </div>
                </div>

                {{-- Contenido Principal - Horizontal --}}
                <div class="space-y-4 mb-8 pb-8 border-b-2 border-gray-300">
                    {{-- Recibí de --}}
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-xs font-bold text-gray-900 uppercase tracking-widest mb-1">Recibí de:</p>
                            <div class="border-b border-gray-900 pb-2 min-h-6">
                                <p class="text-sm font-bold text-gray-900">{{ $recibo->cobro->miembro->persona->nombre }} {{ $recibo->cobro->miembro->persona->apellido }}</p>
                                <p class="text-xs text-gray-600">DNI: {{ $recibo->cobro->miembro->persona->dni }}</p>
                            </div>
                        </div>

                        {{-- La suma de --}}
                        <div>
                            <p class="text-xs font-bold text-gray-900 uppercase tracking-widest mb-1">La suma de:</p>
                            <div class="border-2 border-gray-900 p-3 text-center">
                                <p class="text-2xl font-black text-gray-900">L. {{ number_format($recibo->monto, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Por Concepto --}}
                    <div>
                        <p class="text-xs font-bold text-gray-900 uppercase tracking-widest mb-2">Por Concepto:</p>
                        <div class="space-y-1">
                            @foreach($recibo->cobro->detallesCobros as $detalle)
                            <div class="flex justify-between text-xs border-b border-gray-300 pb-1">
                                <span class="text-gray-900 font-medium">{{ $detalle->concepto }}</span>
                                <span class="text-gray-900 font-bold">L. {{ number_format($detalle->monto, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Firmas Horizontal --}}
                <div class="grid grid-cols-2 gap-12">
                    <div class="text-center">
                        <div class="h-12 mb-2"></div>
                        <div class="border-t-2 border-gray-900 pt-1">
                            <p class="text-xs font-bold text-gray-900 uppercase">Recibí Conforme</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="h-12 mb-2"></div>
                        <div class="border-t-2 border-gray-900 pt-1">
                            <p class="text-xs font-bold text-gray-900 uppercase">Entregué Conforme</p>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mt-6 pt-4 border-t border-gray-300 text-center text-[9px] text-gray-500 space-y-0">
                    <p>Generado: {{ now()->format('d/m/Y H:i') }} | Usuario: {{ $recibo->user->name ?? auth()->user()->name }} | Correlativo: {{ $recibo->anio }}-{{ str_pad($recibo->correlativo, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body {
            background-color: white;
            margin: 0;
            padding: 0;
        }
        
        .min-h-screen {
            background-color: white;
            padding: 0;
            margin: 0;
        }
        
        #recibo {
            box-shadow: none;
            margin: 0;
            padding: 0;
            border-radius: 0;
        }
        
        button, a:not([href="#"]) {
            display: none !important;
        }
        
        .flex.gap-4 {
            display: none !important;
        }

        /* Papel apaisado (Landscape) */
        @page {
            size: A4 landscape;
            margin: 0.5cm;
        }
    }
</style>
@endsection