<div class="p-6">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Centro de Reportes</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Genera informes detallados de la gestión administrativa y social.</p>
    </div>

    {{-- Filtros Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                {{-- Tipo de Reporte --}}
                <div class="md:col-span-1 space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tipo</label>
                    <select wire:model.live="reportType" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        <option value="ingresos">Ingresos</option>
                        <option value="egresos">Egresos</option>
                        <option value="mantenimientos">Mantenimientos</option>
                        <option value="miembros">Miembros</option>
                        <option value="moras">Moras</option>
                    </select>
                </div>

                {{-- Modo de Filtro --}}
                <div class="md:col-span-1 space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Modo</label>
                    <select wire:model.live="filterMode" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        <option value="monthly">Por Mes</option>
                        <option value="range">Rango Libre</option>
                    </select>
                </div>

                @if($filterMode === 'monthly')
                    {{-- Seleccionar Mes --}}
                    <div class="md:col-span-1 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Mes</label>
                        <select wire:model.live="selectedMonth" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>

                    {{-- Seleccionar Año --}}
                    <div class="md:col-span-1 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Año</label>
                        <select wire:model.live="selectedYear" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                @else
                    {{-- Fecha Desde --}}
                    <div class="md:col-span-1 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Desde</label>
                        <input type="date" wire:model.live="dateFrom" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                    </div>

                    {{-- Fecha Hasta --}}
                    <div class="md:col-span-1 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Hasta</label>
                        <input type="date" wire:model.live="dateTo" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                    </div>
                @endif

                {{-- Acciones --}}
                <div class="md:col-span-2 flex items-end space-x-2">
                    <button wire:click="generate" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-95 flex items-center justify-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Generar
                    </button>
                    @if(count($results) > 0)
                        <button wire:click="exportPdf" class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-xl shadow-lg shadow-red-500/30 transition-all active:scale-95 text-sm font-bold" title="Generar PDF">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Generar PDF
                        </button>
                        <button wire:click="exportExcel" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-xl shadow-lg shadow-green-500/30 transition-all active:scale-95 text-sm font-bold" title="Exportar Excel">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Exportar Excel
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Vista Previa / Resultados --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white capitalize">Vista Previa: {{ str_replace('_', ' ', $reportType) }}</h2>
            @if(isset($summary['total']) || isset($summary['total_estimado']) || isset($summary['total_pendiente']))
                <div class="text-lg font-black text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-4 py-1 rounded-full border border-blue-100 dark:border-blue-800">
                    Total: L. {{ number_format($summary['total'] ?? $summary['total_estimado'] ?? $summary['total_pendiente'] ?? 0, 2) }}
                </div>
            @endif
        </div>
        
        <div class="overflow-x-auto">
            @if(count($results) > 0)
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            @if($reportType == 'ingresos')
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Miembro</th>
                                <th class="px-6 py-4">Concepto</th>
                                <th class="px-10 py-4 text-right">Monto</th>
                            @elseif($reportType == 'egresos')
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Concepto</th>
                                <th class="px-6 py-4">Proveedor</th>
                                <th class="px-6 py-4 text-right">Monto</th>
                            @elseif($reportType == 'mantenimientos')
                                <th class="px-6 py-4">Fecha Reg.</th>
                                <th class="px-6 py-4">Activo</th>
                                <th class="px-6 py-4">Tipo</th>
                                <th class="px-6 py-4">Prioridad</th>
                                <th class="px-6 py-4 text-right">Costo Est.</th>
                            @elseif($reportType == 'miembros')
                                <th class="px-6 py-4">DNI</th>
                                <th class="px-6 py-4">Nombre Completo</th>
                                <th class="px-6 py-4">Teléfono</th>
                                <th class="px-6 py-4 text-center">Estado</th>
                            @elseif($reportType == 'moras')
                                <th class="px-6 py-4">Miembro</th>
                                <th class="px-6 py-4">DNI</th>
                                <th class="px-6 py-4 text-right">Monto Pendiente</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($results as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                @if($reportType == 'ingresos')
                                    <td class="px-6 py-4">{{ optional($item->fecha_cobro)->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $item->miembro->persona->nombre_completo ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $conceptsList = [];
                                            if($item->detallesCobros) {
                                                foreach($item->detallesCobros as $d) {
                                                    $conceptsList[] = ($d->servicio->nombre ?? '') . ($d->concepto ? ' (' . $d->concepto . ')' : '');
                                                }
                                            }
                                            if($item->aportaciones) {
                                                foreach($item->aportaciones as $a) {
                                                    if($a->proyecto) $conceptsList[] = "Aporte: " . $a->proyecto->nombre;
                                                }
                                            }
                                            echo implode(', ', array_filter($conceptsList)) ?: $item->tipo_cobro;
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">L. {{ number_format($item->total, 2) }}</td>
                                @elseif($reportType == 'egresos')
                                    <td class="px-6 py-4">{{ optional($item->fecha_pago)->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $item->descripcion }}</td>
                                    <td class="px-6 py-4">{{ $item->proveedor }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">L. {{ number_format($item->total, 2) }}</td>
                                @elseif($reportType == 'mantenimientos')
                                    <td class="px-6 py-4">{{ optional($item->fecha_registro)->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $item->activo->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $item->tipo_mantenimiento }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $item->prioridad == 'Alta' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $item->prioridad }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold">L. {{ number_format($item->costo_estimado, 2) }}</td>
                                @elseif($reportType == 'miembros')
                                    <td class="px-6 py-4">{{ $item->persona->dni }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $item->persona->nombre_completo }}</td>
                                    <td class="px-6 py-4">{{ $item->persona->telefono }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $item->estado == 'Activo' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $item->estado }}
                                        </span>
                                    </td>
                                @elseif($reportType == 'moras')
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $item->miembro->persona->nombre_completo ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $item->miembro->persona->dni ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-red-600">L. {{ number_format($item->monto_pendiente, 2) }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No hay datos para mostrar con los filtros seleccionados.</p>
                </div>
            @endif
        </div>
    </div>
</div>
