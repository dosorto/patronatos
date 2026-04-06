<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tesorería</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Resumen financiero de ingresos y egresos</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <input
                type="date"
                wire:model.live="fechaInicio"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
            >
            <input
                type="date"
                wire:model.live="fechaFin"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white"
            >
            <button
                wire:click="limpiarFiltros"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium"
            >
                Limpiar
            </button>
        </div>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ingresos</p>
            <p class="text-3xl font-bold text-green-600 mt-2">L. {{ number_format($totalIngresos, 2) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $cantidadCobros }} cobro(s)</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Egresos</p>
            <p class="text-3xl font-bold text-red-600 mt-2">L. {{ number_format($totalEgresos, 2) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $cantidadPagos }} pago(s)</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Balance</p>
            <p class="text-3xl font-bold mt-2 {{ $balance >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                L. {{ number_format($balance, 2) }}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Ingresos - Egresos</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Movimientos</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                {{ $cantidadCobros + $cantidadPagos }}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Total del período</p>
        </div>
    </div>

    {{-- Resumen por tipo --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ingresos clasificados</h2>
            </div>

            <div class="p-6 space-y-6">
                @forelse($ingresosClasificados as $bloque)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 flex justify-between items-center">
                            <span class="font-bold text-gray-900 dark:text-white">
                                {{ $bloque['grupo'] }}
                            </span>
                            <span class="font-bold text-green-600">
                                L. {{ number_format($bloque['total'], 2) }}
                            </span>
                        </div>

                        <div class="p-4 space-y-2">
                            @forelse($bloque['conceptos'] as $item)
                                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-2">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $item['concepto'] }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        L. {{ number_format($item['total'], 2) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Sin conceptos en esta categoría.
                                </p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sin ingresos en este período.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Egresos clasificados</h2>
            </div>

            <div class="p-6 space-y-6">
                @forelse($egresosClasificados as $bloque)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 flex justify-between items-center">
                            <span class="font-bold text-gray-900 dark:text-white">
                                {{ $bloque['grupo'] }}
                            </span>
                            <span class="font-bold text-red-600">
                                L. {{ number_format($bloque['total'], 2) }}
                            </span>
                        </div>

                        <div class="p-4 space-y-2">
                            @forelse($bloque['conceptos'] as $item)
                                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-2">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $item['concepto'] }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        L. {{ number_format($item['total'], 2) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Sin conceptos en esta categoría.
                                </p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sin egresos en este período.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tablas --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Cobros recientes</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Miembro</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($cobrosRecientes as $cobro)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    {{ $cobro->miembro?->persona?->nombre }} {{ $cobro->miembro?->persona?->apellido }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    {{ optional($cobro->fecha_cobro)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium text-green-600">
                                    L. {{ number_format($cobro->total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No hay cobros en este período.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pagos recientes</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Beneficiario</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($pagosRecientes as $pago)
                            @php
                                $nombrePago = $pago->nombre_persona
                                    ?: trim(($pago->empleado?->persona?->nombre ?? '') . ' ' . ($pago->empleado?->persona?->apellido ?? ''));

                                $nombrePago = $nombrePago ?: 'Pago registrado';
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $nombrePago }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    {{ optional($pago->fecha_pago)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium text-red-600">
                                    L. {{ number_format($pago->total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No hay pagos en este período.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>