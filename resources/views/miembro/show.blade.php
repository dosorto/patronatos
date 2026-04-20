@extends(request()->boolean('wizard') ? 'layouts.app' : 'layouts.app')

@section('title', 'Perfil de Miembro - ' . ($miembro->persona->nombre ?? 'Miembro'))

@section('content')
<div class="container-fluid max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'perfil' }">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center text-white shadow-lg">
                <span class="text-2xl font-black uppercase">{{ substr($miembro->persona->nombre ?? 'M', 0, 1) }}{{ substr($miembro->persona->apellido ?? '', 0, 1) }}</span>
            </div>
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white leading-tight uppercase tracking-tight">
                    {{ $miembro->persona->nombre ?? 'N/A' }} {{ $miembro->persona->apellido ?? '' }}
                </h1>
                <div class="flex items-center gap-3 mt-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                        ID: {{ $miembro->id }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $miembro->estado == 'Activo' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-red-100 text-red-700 border-red-200' }} dark:bg-opacity-20 border">
                        {{ $miembro->estado }}
                    </span>
                    @php
                        $moraTotal = $miembro->moras->sum('monto_pendiente');
                    @endphp
                    @if($moraTotal > 0)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-rose-100 text-rose-700 border border-rose-200 dark:bg-rose-900/30 dark:text-rose-400">
                            MOROSO: L. {{ number_format($moraTotal, 2) }}
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400">
                            SOLVENTE
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('miembro.index') . (request()->boolean('wizard') ? '?wizard=1' : '') }}" 
               class="px-5 py-2.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl font-bold text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Regresar
            </a>
            @can('miembro.edit')
            <a href="{{ route('miembro.edit', $miembro) . (request()->boolean('wizard') ? '?wizard=1' : '') }}" 
               class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Editar Perfil
            </a>
            @endcan
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex overflow-x-auto gap-1 mb-8 bg-white dark:bg-gray-800 p-1.5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm scrollbar-hide">
        <button @click="activeTab = 'perfil'" :class="activeTab === 'perfil' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'" 
                class="flex-1 min-w-[120px] px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
            Información
        </button>
        <button @click="activeTab = 'suscripciones'" :class="activeTab === 'suscripciones' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'" 
                class="flex-1 min-w-[120px] px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all flex items-center justify-center gap-2">
            Servicios
            <span class="bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 px-1.5 py-0.5 rounded text-[10px]">{{ $miembro->suscripciones->count() }}</span>
        </button>
        <button @click="activeTab = 'moras'" :class="activeTab === 'moras' ? 'bg-rose-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'" 
                class="flex-1 min-w-[120px] px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all flex items-center justify-center gap-2">
            Mora
            @if($moraTotal > 0)
                <span class="bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded text-[10px]">{{ $miembro->moras->where('monto_pendiente', '>', 0)->where('estado', '!=', 'Cancelado')->count() }}</span>
            @endif
        </button>
        <button @click="activeTab = 'cobros'" :class="activeTab === 'cobros' ? 'bg-emerald-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'" 
                class="flex-1 min-w-[120px] px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all flex items-center justify-center gap-2">
            Pagos
            <span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300 px-1.5 py-0.5 rounded text-[10px]">{{ $miembro->cobros->count() }}</span>
        </button>
        <button @click="activeTab = 'proyectos'" :class="activeTab === 'proyectos' ? 'bg-amber-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'" 
                class="flex-1 min-w-[120px] px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all select-none">
            Proyectos
        </button>
        <button @click="activeTab = 'donaciones'" :class="activeTab === 'donaciones' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'" 
                class="flex-1 min-w-[120px] px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
            Donaciones
        </button>
        <button @click="activeTab = 'logs'" :class="activeTab === 'logs' ? 'bg-gray-700 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'" 
                class="flex-1 min-w-[120px] px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
            Bitácora
        </button>
    </div>

    {{-- Content Area --}}
    <div class="space-y-6">

        {{-- TAB: PERFIL --}}
        <div x-show="activeTab === 'perfil'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-8 text-center border-b border-gray-100 dark:border-gray-700">
                        <div class="w-24 h-24 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $miembro->persona->nombre }} {{ $miembro->persona->apellido }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-widest font-bold">DNI: {{ $miembro->persona->formatted_dni ?? 'N/A' }}</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-tighter block mb-1">Ubicación</label>
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-snug">
                                {{ $organization?->municipio?->nombre ?? 'N/A' }}, {{ $organization?->departamento?->nombre ?? '' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-tighter block mb-1">Dirección Exacta</label>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 italic">
                                {{ $miembro->direccion ?? 'Sin dirección registrada' }}
                            </p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                             <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-tighter block mb-1">Contacto</label>
                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $miembro->persona->telefono ?? 'S/N' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-tighter block mb-1">Sexo</label>
                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase">{{ $miembro->persona->sexo ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-blue-600 rounded-2xl p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">
                    <div class="absolute right-0 top-0 -translate-y-1/2 translate-x-1/2 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <h3 class="text-sm font-black uppercase tracking-widest text-blue-100">Capital Social / Aportes</h3>
                        <p class="text-4xl font-black mt-2">L. {{ number_format($miembro->cobros->sum('total'), 2) }}</p>
                        <p class="text-xs font-medium text-blue-100 mt-1 opacity-80 uppercase tracking-tighter">Total aportado a la organización desde su ingreso</p>
                    </div>
                    <div class="relative z-10 grid grid-cols-2 gap-8 text-center bg-black/10 p-4 rounded-xl backdrop-blur-sm">
                        <div>
                            <p class="text-[10px] font-black uppercase text-blue-100 leading-none">Vencido</p>
                            <p class="text-xl font-black mt-1 text-rose-300">L. {{ number_format($moraTotal, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-blue-100 leading-none">Donado</p>
                            <p class="text-xl font-black mt-1 text-emerald-300">L. {{ number_format($donaciones->sum('monto'), 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                    <h3 class="text-sm font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Resumen de Actividad
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-xl border border-gray-100 dark:border-gray-700">
                            <span class="block text-[10px] font-black uppercase text-gray-400 tracking-tighter">Último Pago</span>
                            <span class="block text-sm font-bold mt-1 text-gray-900 dark:text-white">
                                {{ $miembro->cobros->sortByDesc('fecha_cobro')->first()?->fecha_cobro?->format('d/m/Y') ?? 'Sin registros' }}
                            </span>
                        </div>
                         <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-xl border border-gray-100 dark:border-gray-700">
                            <span class="block text-[10px] font-black uppercase text-gray-400 tracking-tighter">Proyectos</span>
                            <span class="block text-sm font-bold mt-1 text-gray-900 dark:text-white">
                                {{ $miembro->aportaciones->unique('proyecto_id')->count() }} Proyectos activos
                            </span>
                        </div>
                         <div class="p-4 bg-gray-50 dark:bg-gray-900/40 rounded-xl border border-gray-100 dark:border-gray-700">
                            <span class="block text-[10px] font-black uppercase text-gray-400 tracking-tighter">Antigüedad</span>
                            <span class="block text-sm font-bold mt-1 text-gray-900 dark:text-white">
                                {{ $miembro->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB: SUSCRIPCIONES --}}
        <div x-show="activeTab === 'suscripciones'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 dark:text-gray-300">Servicios Contratados</h3>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($miembro->suscripciones as $sub)
                    <div class="relative group p-6 border-2 border-gray-100 dark:border-gray-700 rounded-2xl hover:border-blue-500 transition-all bg-white dark:bg-gray-800">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-xl text-blue-600 dark:text-blue-400 font-black">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <span class="text-xs font-black uppercase tracking-widest {{ $sub->estado == 1 ? 'text-emerald-500' : 'text-gray-400' }}">
                                {{ $sub->estado == 1 ? 'Activo' : 'Suspendido' }}
                            </span>
                        </div>
                        <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase leading-tight">{{ $sub->servicio->nombre }}</h4>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter mt-1">L. {{ number_format($sub->servicio->precio, 2) }} mensual</p>
                        
                        <div class="mt-6 pt-6 border-t border-gray-50 dark:border-gray-700 space-y-3">
                            <div class="flex justify-between text-[10px] uppercase font-bold tracking-widest">
                                <span class="text-gray-400">Identificador</span>
                                <span class="text-gray-900 dark:text-white">{{ $sub->identificador ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between text-[10px] uppercase font-bold tracking-widest">
                                <span class="text-gray-400">Medidor</span>
                                <span class="text-gray-900 dark:text-white">{{ $sub->medidor->numero_medidor ?? 'No aplica' }}</span>
                            </div>
                            <div class="flex justify-between text-[10px] uppercase font-bold tracking-widest">
                                <span class="text-gray-400">Último Pago</span>
                                <span class="text-emerald-500 font-black">{{ $sub->ultimo_mes_pagado ? \Carbon\Carbon::parse($sub->ultimo_mes_pagado)->format('M/Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-12 text-center text-gray-400 dark:text-gray-500">
                        <p class="text-lg font-bold italic">No tiene servicios suscritos.</p>
                        <a href="{{ route('miembro.edit', $miembro) }}" class="text-blue-500 font-bold uppercase text-xs mt-2 inline-block">Asignar servicios</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- TAB: MORAS (ESTADO DE CUENTA) --}}
        <div x-show="activeTab === 'moras'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-8 py-6 bg-rose-50 dark:bg-rose-900/20 border-b border-rose-100 dark:border-rose-900/30 flex justify-between items-center">
                <h3 class="text-sm font-black uppercase tracking-widest text-rose-700 dark:text-rose-400">Mora y Pendientes Acumulados</h3>
                <span class="text-xl font-black text-rose-700 dark:text-rose-400">Total: L. {{ number_format($moraTotal, 2) }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900/40 text-[10px] uppercase font-black tracking-widest text-gray-400">
                        <tr>
                            <th class="px-8 py-4">Concepto / Periodo</th>
                            <th class="px-8 py-4 text-right">Monto Original</th>
                            <th class="px-8 py-4 text-right">Pendiente</th>
                            <th class="px-8 py-4 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($miembro->moras->where('estado', '!=', 'Cancelado') as $mora)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20 transition-colors">
                            <td class="px-8 py-4">
                                <p class="text-sm font-bold text-gray-900 dark:text-white uppercase leading-tight">{{ $mora->periodo ?? 'Servicio' }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $mora->mes_referencia ? \Carbon\Carbon::parse($mora->mes_referencia)->format('F Y') : '--' }}</p>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="text-sm font-bold text-gray-600 dark:text-gray-400 italic">L. {{ number_format($mora->monto_original, 2) }}</span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="text-sm font-black text-rose-600 dark:text-rose-400">L. {{ number_format($mora->monto_pendiente, 2) }}</span>
                            </td>
                            <td class="px-8 py-4 text-center">
                                @php
                                    $mColor = 'bg-emerald-100 text-emerald-700';
                                    $mText = 'Pagado';
                                    
                                    if ($mora->estado === 'Cancelado') {
                                        $mColor = 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
                                        $mText = 'Cancelado';
                                    } elseif ($mora->monto_pendiente > 0) {
                                        $mColor = 'bg-rose-100 text-rose-700';
                                        $mText = 'En Mora';
                                    }
                                @endphp
                                <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase {{ $mColor }} dark:bg-opacity-20 border border-current">
                                    {{ $mText }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-emerald-500 italic font-bold">Sin meses de mora pendientes. ¡Felicidades, está al día!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB: COBROS (PAGOS HECHOS) --}}
        <div x-show="activeTab === 'cobros'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
             <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 dark:text-gray-300">Historial de Cobros Recibidos</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900/40 text-[10px] uppercase font-black tracking-widest text-gray-400">
                        <tr>
                            <th class="px-8 py-4">Fecha</th>
                            <th class="px-8 py-4">Nº Recibo</th>
                            <th class="px-8 py-4">Conceptos Pagados</th>
                            <th class="px-8 py-4 text-right">Monto Total</th>
                            <th class="px-8 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($miembro->cobros->sortByDesc('fecha_cobro') as $cobro)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20 transition-colors">
                            <td class="px-8 py-4 whitespace-nowrap">
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $cobro->fecha_cobro->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <span class="text-xs font-black text-blue-600 dark:text-blue-400">REC-{{ str_pad($cobro->recibo->correlativo ?? $cobro->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($cobro->detallesCobros as $det)
                                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-[10px] rounded uppercase font-bold">{{ $det->concepto }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="text-sm font-black text-gray-900 dark:text-white">L. {{ number_format($cobro->total, 2) }}</span>
                            </td>
                            <td class="px-8 py-4 text-center">
                                @if($cobro->recibo)
                                <a href="{{ route('recibo.show', $cobro->recibo) }}" class="text-blue-600 hover:text-blue-800 transition-colors font-black text-[10px] uppercase tracking-tighter">Ver Recibo</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-gray-400 italic font-bold">No se han registrado pagos aún.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB: PROYECTOS --}}
        <div x-show="activeTab === 'proyectos'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-8 py-6 bg-amber-50 dark:bg-amber-900/20 border-b border-amber-100 dark:border-amber-900/30">
                <h3 class="text-sm font-black uppercase tracking-widest text-amber-700 dark:text-amber-400">Proyectos y Aportaciones Extraordinarias</h3>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($miembro->aportaciones->unique('proyecto_id')->filter(fn($a) => $a->proyecto && !in_array($a->proyecto->estado, ['Cancelado', 'Completado'])) as $aport)
                        @php
                             $totalAportado = $miembro->aportaciones->where('proyecto_id', $aport->proyecto_id)->sum('monto_pagado');
                             $totalAsignado = $miembro->aportaciones->where('proyecto_id', $aport->proyecto_id)->first()->monto_asignado ?? 0;
                             $progreso = ($totalAsignado > 0) ? min(100, ($totalAportado / $totalAsignado) * 100) : 0;
                        @endphp
                        <div class="p-6 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 shadow-sm relative">
                            <div class="flex items-start justify-between">
                                <h4 class="text-lg font-black text-gray-900 dark:text-white uppercase leading-tight">{{ $aport->proyecto->nombre }}</h4>
                                @if(in_array($aport->proyecto->estado, ['Cancelado', 'Completado']))
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-red-50 text-red-600 dark:bg-red-900/30 font-bold border border-red-200 block uppercase">{{ $aport->proyecto->estado }}</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-blue-50 text-blue-600 dark:bg-blue-900/30 font-bold border border-blue-200 block uppercase">{{ $aport->proyecto->estado ?? 'Activo' }}</span>
                                @endif
                            </div>
                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-tighter mt-2">{{ Str::limit($aport->proyecto->descripcion, 60) }}</p>
                            
                            <div class="mt-6 space-y-4">
                                <div class="flex justify-between text-xs font-black uppercase">
                                    <span class="text-gray-500">Aportado</span>
                                    <span class="text-amber-600">L. {{ number_format($totalAportado, 2) }} / L. {{ number_format($totalAsignado, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-amber-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $progreso }}%"></div>
                                </div>
                                <p class="text-[10px] font-black text-right text-gray-400 uppercase tracking-widest">{{ number_format($progreso, 0) }}% Completado</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-gray-400 italic">No ha participado en proyectos comunitarios aún.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- TAB: DONACIONES --}}
        <div x-show="activeTab === 'donaciones'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-8 py-6 bg-indigo-50 dark:bg-indigo-900/20 border-b border-indigo-100 dark:border-indigo-900/30 flex justify-between items-center">
                <h3 class="text-sm font-black uppercase tracking-widest text-indigo-700 dark:text-indigo-400">Muestras de Generosidad</h3>
                <span class="text-xs font-bold uppercase py-1 px-3 bg-indigo-600 text-white rounded-full">Total Donado: L. {{ number_format($donaciones->sum('monto'), 2) }}</span>
            </div>
            <div class="p-8">
                <div class="space-y-4">
                    @forelse($donaciones as $don)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-900/30 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900 dark:text-white uppercase leading-tight">{{ $don->concepto }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $don->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-black text-indigo-600 dark:text-indigo-400">L. {{ number_format($don->monto, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-gray-400 italic">No se han registrado donaciones voluntarias por este miembro.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- TAB: LOGS --}}
        <div x-show="activeTab === 'logs'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
             <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 dark:text-gray-300">Auditoría del Registro</h3>
            </div>
            <div class="p-8">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($miembro->auditLogs as $log)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-100 dark:bg-gray-700" aria-hidden="true"></span>
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
                                            <span class="h-8 w-8 rounded-lg {{ $colors[$log->event] ?? 'bg-gray-500' }} flex items-center justify-center ring-4 ring-white dark:ring-gray-800">
                                                @if($log->event === 'created')
                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                                                @elseif($log->event === 'updated')
                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                @else
                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-black tracking-widest leading-none mb-1">
                                                    {{ $log->event === 'created' ? 'Registro inicial' : ($log->event === 'updated' ? 'Actualización' : 'Eliminación') }}
                                                </p>
                                                <p class="text-xs font-bold text-gray-900 dark:text-white">
                                                    por <span class="text-blue-600">{{ $log->user_name ?? ($log->user->name ?? 'Sistema') }}</span>
                                                </p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-[10px] font-bold text-gray-400 uppercase">
                                                <time>{{ $log->created_at->format('d/m/y H:i') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 italic py-4 text-center">Sin movimientos auditables.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
section