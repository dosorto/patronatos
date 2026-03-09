@extends('layouts.app')

@section('title', 'Recibo - ' . $recibo->nombre)

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        {{-- Botones de acción --}}
        <div class="flex gap-4 justify-end mb-6">
            <a href="{{ route('cobro.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition-all">
                Volver
            </a>
            <button onclick="window.print()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all">
                🖨️ Imprimir
            </button>
        </div>

        {{-- Recibo --}}
        <div class="bg-white rounded-lg shadow-2xl overflow-hidden" id="recibo">
            {{-- Encabezado --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 text-center">
                <h1 class="text-4xl font-bold mb-2">RECIBO</h1>
                <p class="text-xl font-semibold">{{ $recibo->nombre }}</p>
            </div>

            {{-- Contenido Principal --}}
            <div class="p-8 space-y-8">
                
                {{-- Línea divisoria --}}
                <div class="border-b-2 border-gray-300"></div>

                {{-- Datos del Recibo --}}
                <div class="grid grid-cols-3 gap-6 text-center">
                    <div>
                        <p class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Correlativo</p>
                        <p class="text-xl font-bold text-gray-900">{{ $recibo->correlativo }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Fecha</p>
                        <p class="text-xl font-bold text-gray-900">{{ $recibo->fecha_emision->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Año</p>
                        <p class="text-xl font-bold text-gray-900">{{ $recibo->anio }}</p>
                    </div>
                </div>

                {{-- Línea divisoria --}}
                <div class="border-t-2 border-gray-300"></div>

                {{-- Datos del Miembro --}}
                <div>
                    <h2 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-4">Recibido de:</h2>
                    <div class="space-y-3 ml-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-semibold">Nombre:</span>
                            <span class="text-gray-900 font-bold">
                                {{ $recibo->cobro->miembro->persona->nombre }} {{ $recibo->cobro->miembro->persona->apellido }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-semibold">DNI:</span>
                            <span class="text-gray-900 font-bold">{{ $recibo->cobro->miembro->persona->dni }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-semibold">Dirección:</span>
                            <span class="text-gray-900 font-bold">{{ $recibo->cobro->miembro->direccion }}</span>
                        </div>
                    </div>
                </div>

                {{-- Línea divisoria --}}
                <div class="border-t-2 border-gray-300"></div>

                {{-- Tabla de Conceptos --}}
                <div>
                    <h2 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-4">Concepto de Pago:</h2>
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-3 px-4 text-gray-700 font-bold">Descripción</th>
                                <th class="text-right py-3 px-4 text-gray-700 font-bold">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recibo->cobro->detallesCobros as $detalle)
                            <tr>
                                <td class="py-3 px-4 text-gray-900">{{ $detalle->concepto }}</td>
                                <td class="py-3 px-4 text-right text-gray-900 font-bold">
                                    L. {{ number_format($detalle->monto, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Línea divisoria --}}
                <div class="border-t-2 border-gray-300"></div>

                {{-- Total --}}
                <div class="bg-gradient-to-r from-gray-100 to-gray-50 rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">TOTAL:</span>
                        <span class="text-4xl font-bold text-blue-600">L. {{ number_format($recibo->monto, 2) }}</span>
                    </div>
                </div>

                {{-- Línea divisoria --}}
                <div class="border-t-2 border-gray-300"></div>

                {{-- Observaciones --}}
                @if($recibo->observaciones)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs font-bold text-gray-600 uppercase mb-2">Observaciones:</p>
                    <p class="text-gray-900">{{ $recibo->observaciones }}</p>
                </div>
                @endif

                {{-- Firma --}}
                <div class="pt-8">
                    <div class="grid grid-cols-2 gap-12">
                        <div class="text-center">
                            <div class="border-t-2 border-gray-400 pt-2 mt-12">
                                <p class="text-xs font-bold text-gray-600">Firma de Recepción</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="border-t-2 border-gray-400 pt-2 mt-12">
                                <p class="text-xs font-bold text-gray-600">Autorizado por</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pie de página --}}
                <div class="text-center pt-6 border-t border-gray-300">
                    <p class="text-xs text-gray-500">Generado: {{ now()->format('d/m/Y H:i:s') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Documento válido para efectos fiscales</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body {
            background-color: white;
        }
        
        .min-h-screen {
            background-color: white;
        }
        
        #recibo {
            box-shadow: none;
            margin: 0;
            padding: 0;
        }
        
        button, a:not([href="#"]) {
            display: none !important;
        }
        
        .flex.gap-4 {
            display: none !important;
        }
    }
</style>
@endsection