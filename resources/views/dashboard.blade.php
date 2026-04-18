@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-manrope, .font-headline { font-family: 'Manrope', sans-serif; }
    </style>
@endpush

@section('content')
<div class="relative w-full h-full min-h-[85vh]">
    <!-- Welcome Section -->
    <section class="mb-10 mt-4 relative z-10">
        <div class="flex items-center gap-5">
            {{-- Logo de la organización --}}
            @if($organization && $organization->logo)
                <img src="{{ Storage::url($organization->logo) }}"
                     alt="Logo {{ $organization->name }}"
                     class="w-16 h-16 rounded-xl object-contain border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-1 shadow-sm flex-shrink-0">
            @else
                <div class="w-16 h-16 rounded-xl bg-primary/10 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-3xl text-primary dark:text-blue-400">water_drop</span>
                </div>
            @endif
            <div>
                <h2 class="text-4xl font-light font-headline text-slate-900 dark:text-white">Bienvenido, <span class="font-bold">{{ auth()->user()->name ?? 'Administrador' }}</span></h2>
                <p class="text-sm mt-2 text-slate-600 dark:text-slate-400">{{ $organization->name ?? 'Su organización' }} · Resumen general del sistema.</p>
            </div>
        </div>
    </section>

    <!-- Alertas de Configuración Pendiente -->
    @if(!$configStatus['logo'] || !$configStatus['directiva'] || !$configStatus['miembros'] || !$configStatus['servicios'])
    <section class="mb-10 animate-in fade-in slide-in-from-top-4 duration-500 relative z-10">
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="p-2 bg-amber-100 dark:bg-amber-800/40 rounded-lg text-amber-600 dark:text-amber-400">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <div>
                    <h4 class="font-bold text-amber-900 dark:text-amber-200 mb-2">Configuraciones sugeridas para el sistema</h4>
                    <p class="text-sm text-amber-800 dark:text-amber-300 mb-4 text-balance">Para que su sistema funcione de manera óptima y personalizada, le recomendamos completar las siguientes configuraciones:</p>
                    <div class="flex flex-wrap gap-3">
                        @if(!$configStatus['logo'])
                            <a href="{{ route('organization.edit') }}" class="flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-slate-800 border border-amber-200 dark:border-amber-700 rounded-lg text-xs font-bold text-amber-700 dark:text-amber-300 hover:bg-amber-100 transition-colors">
                                <span class="material-symbols-outlined !text-sm">image</span> Subir Logo
                            </a>
                        @endif
                        @if(!$configStatus['directiva'])
                            <a href="{{ route('directiva.index') }}" class="flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-slate-800 border border-amber-200 dark:border-amber-700 rounded-lg text-xs font-bold text-amber-700 dark:text-amber-300 hover:bg-amber-100 transition-colors">
                                <span class="material-symbols-outlined !text-sm">groups_3</span> Definir Directiva
                            </a>
                        @endif
                        @if(!$configStatus['miembros'])
                            <a href="{{ route('miembro.create') }}" class="flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-slate-800 border border-amber-200 dark:border-amber-700 rounded-lg text-xs font-bold text-amber-700 dark:text-amber-300 hover:bg-amber-100 transition-colors">
                                <span class="material-symbols-outlined !text-sm">person_add</span> Registrar Miembros
                            </a>
                        @endif
                        @if(!$configStatus['servicios'])
                            <a href="{{ route('servicios.create') }}" class="flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-slate-800 border border-amber-200 dark:border-amber-700 rounded-lg text-xs font-bold text-amber-700 dark:text-amber-300 hover:bg-amber-100 transition-colors">
                                <span class="material-symbols-outlined !text-sm">water_drop</span> Configurar Servicios
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Visual Polish: Background Gradients -->
    <div class="absolute top-0 right-0 -z-10 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -z-10 w-[400px] h-[400px] bg-secondary-fixed/5 rounded-full blur-[100px] pointer-events-none"></div>

    <!-- Stats Bento Grid -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12 relative z-10">
        <!-- Card 1: Total Miembros -->
        <div class="bg-surface-container-lowest dark:bg-slate-800 p-6 rounded-xl shadow-[0_4px_20px_rgba(0,88,188,0.04)] dark:shadow-none flex justify-between items-center group hover:-translate-y-1 transition-all duration-300 border border-transparent dark:border-slate-700">
            <div>
                <p class="text-on-surface-variant dark:text-slate-400 font-manrope font-semibold text-sm mb-2 tracking-tight">Total Miembros</p>
                <h3 class="text-4xl font-bold font-manrope text-primary dark:text-blue-400 tracking-tighter">{{ $totalMiembros ?? 0 }}</h3>
            </div>
            <div class="w-14 h-14 rounded-xl bg-primary-fixed-dim/20 dark:bg-blue-900/40 flex items-center justify-center text-primary dark:text-blue-400 group-hover:rotate-6 transition-transform">
                <span class="material-symbols-outlined !text-3xl" data-icon="group">group</span>
            </div>
        </div>

        <!-- Card 2: Total Activos -->
        <div class="bg-surface-container-lowest dark:bg-slate-800 p-6 rounded-xl shadow-[0_4px_20px_rgba(0,88,188,0.04)] dark:shadow-none flex justify-between items-center group hover:-translate-y-1 transition-all duration-300 border border-transparent dark:border-slate-700">
            <div>
                <p class="text-on-surface-variant dark:text-slate-400 font-manrope font-semibold text-sm mb-2 tracking-tight">Total Activos</p>
                <h3 class="text-4xl font-bold font-manrope text-secondary dark:text-teal-400 tracking-tighter">{{ $totalActivos ?? 0 }}</h3>
            </div>
            <div class="w-14 h-14 rounded-xl bg-secondary-container/20 dark:bg-teal-900/40 flex items-center justify-center text-secondary dark:text-teal-400 group-hover:-rotate-6 transition-transform">
                <span class="material-symbols-outlined !text-3xl" data-icon="inventory_2">inventory_2</span>
            </div>
        </div>

        <!-- Card 3: Total Proyectos -->
        <div class="bg-surface-container-lowest dark:bg-slate-800 p-6 rounded-xl shadow-[0_4px_20px_rgba(0,88,188,0.04)] dark:shadow-none flex justify-between items-center group hover:-translate-y-1 transition-all duration-300 border border-transparent dark:border-slate-700">
            <div>
                <p class="text-on-surface-variant dark:text-slate-400 font-manrope font-semibold text-sm mb-2 tracking-tight">Total Proyectos</p>
                <h3 class="text-4xl font-bold font-manrope text-indigo-500 dark:text-indigo-400 tracking-tighter">{{ $totalProyectos ?? 0 }}</h3>
            </div>
            <div class="w-14 h-14 rounded-xl bg-indigo-500/10 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-500 dark:text-indigo-400 group-hover:rotate-6 transition-transform">
                <span class="material-symbols-outlined !text-3xl" data-icon="assessment">assessment</span>
            </div>
        </div>

        <!-- Card 4: Total Servicios -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-[0_4px_20px_rgba(0,88,188,0.04)] dark:shadow-none flex justify-between items-center group hover:-translate-y-1 transition-all duration-300 border border-transparent dark:border-slate-700">
            <div>
                <p class="text-slate-600 dark:text-slate-400 font-manrope font-semibold text-sm mb-2 tracking-tight">Total Servicios</p>
                <h3 class="text-4xl font-bold font-manrope text-blue-600 dark:text-blue-400 tracking-tighter">{{ $totalServicios ?? 0 }}</h3>
            </div>
            <div class="w-14 h-14 rounded-xl bg-blue-600/10 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:-rotate-6 transition-transform">
                <span class="material-symbols-outlined !text-3xl" data-icon="water_drop">water_drop</span>
            </div>
        </div>
    </section>

    <!-- Financial Stats Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 relative z-10">
        <h2 class="text-2xl font-bold font-headline dark:text-white">Resumen Financiero</h2>
        
        <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2 bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
            @php
                $meses = [
                    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                ];
            @endphp
            <select name="month" onchange="this.form.submit()" class="bg-transparent border-none text-sm font-semibold focus:ring-0 dark:text-slate-300">
                @foreach($meses as $num => $nombre)
                    <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>
            <select name="year" onchange="this.form.submit()" class="bg-transparent border-none text-sm font-semibold focus:ring-0 dark:text-slate-300">
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 relative z-10">
        <!-- Card: Ingresos -->
        <div class="bg-surface-container-lowest dark:bg-slate-800 p-6 rounded-xl shadow-[0_4px_20px_rgba(0,188,88,0.04)] dark:shadow-none flex justify-between items-center group hover:-translate-y-1 transition-all duration-300 border border-transparent dark:border-slate-700">
            <div>
                <p class="text-slate-600 dark:text-slate-400 font-manrope font-semibold text-sm mb-2 tracking-tight">Ingresos Totales</p>
                <h3 class="text-3xl font-bold font-manrope text-green-600 dark:text-green-400 tracking-tighter">L. {{ number_format($totalIngresos ?? 0, 2) }}</h3>
            </div>
            <div class="w-14 h-14 rounded-xl bg-green-600/10 dark:bg-green-900/40 flex items-center justify-center text-green-600 dark:text-green-400 group-hover:rotate-6 transition-transform">
                <span class="material-symbols-outlined !text-3xl" data-icon="trending_up">trending_up</span>
            </div>
        </div>

        <!-- Card: Egresos -->
        <div class="bg-surface-container-lowest dark:bg-slate-800 p-6 rounded-xl shadow-[0_4px_20px_rgba(188,0,0,0.04)] dark:shadow-none flex justify-between items-center group hover:-translate-y-1 transition-all duration-300 border border-transparent dark:border-slate-700">
            <div>
                <p class="text-slate-600 dark:text-slate-400 font-manrope font-semibold text-sm mb-2 tracking-tight">Egresos Totales</p>
                <h3 class="text-3xl font-bold font-manrope text-red-600 dark:text-red-400 tracking-tighter">L. {{ number_format($totalEgresos ?? 0, 2) }}</h3>
            </div>
            <div class="w-14 h-14 rounded-xl bg-red-600/10 dark:bg-red-900/40 flex items-center justify-center text-red-600 dark:text-red-400 group-hover:-rotate-6 transition-transform">
                <span class="material-symbols-outlined !text-3xl" data-icon="trending_down">trending_down</span>
            </div>
        </div>

        <!-- Card: Balance -->
        <div class="bg-surface-container-lowest dark:bg-slate-800 p-6 rounded-xl shadow-[0_4px_20px_rgba(88,0,188,0.04)] dark:shadow-none flex justify-between items-center group hover:-translate-y-1 transition-all duration-300 border border-transparent dark:border-slate-700">
            <div>
                <p class="text-slate-600 dark:text-slate-400 font-manrope font-semibold text-sm mb-2 tracking-tight">Balance Neto</p>
                <h3 class="text-3xl font-bold font-manrope text-indigo-600 dark:text-indigo-400 tracking-tighter">L. {{ number_format($balance ?? 0, 2) }}</h3>
            </div>
            <div class="w-14 h-14 rounded-xl bg-indigo-600/10 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:rotate-12 transition-transform">
                <span class="material-symbols-outlined !text-3xl" data-icon="account_balance_wallet">account_balance_wallet</span>
            </div>
        </div>
    </section>

    <!-- Accesos Rapidos Section -->
    <section class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-lg relative z-10 border border-slate-100 dark:border-slate-800 dark:shadow-none">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h4 class="text-2xl font-bold font-headline mb-2 dark:text-white">Accesos Rápidos</h4>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Navegue a las diferentes secciones administrativas.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('miembro.index') ?? '#' }}" class="p-6 rounded-xl border border-outline-variant/30 dark:border-slate-700 hover:border-primary/50 dark:hover:border-blue-500/50 hover:bg-primary/5 dark:hover:bg-blue-500/10 transition-all text-center group">
                <span class="material-symbols-outlined text-4xl text-primary dark:text-blue-400 mb-3 group-hover:scale-110 transition-transform">groups</span>
                <h5 class="font-bold text-slate-900 dark:text-slate-200">Miembros</h5>
            </a>
            <a href="{{ route('cooperantes.index') ?? '#' }}" class="p-6 rounded-xl border border-outline-variant/30 dark:border-slate-700 hover:border-teal-500/50 dark:hover:border-teal-400/50 hover:bg-teal-500/5 dark:hover:bg-teal-400/10 transition-all text-center group">
                <span class="material-symbols-outlined text-4xl text-teal-600 dark:text-teal-400 mb-3 group-hover:scale-110 transition-transform">handshake</span>
                <h5 class="font-bold text-slate-900 dark:text-slate-200">Cooperantes</h5>
            </a>
            <a href="{{ route('proyecto.index') ?? '#' }}" class="p-6 rounded-xl border border-outline-variant/30 dark:border-slate-700 hover:border-blue-500/50 dark:hover:border-blue-400/50 hover:bg-blue-500/5 dark:hover:bg-blue-400/10 transition-all text-center group">
                <span class="material-symbols-outlined text-4xl text-blue-500 dark:text-blue-400 mb-3 group-hover:scale-110 transition-transform">assessment</span>
                <h5 class="font-bold text-slate-900 dark:text-slate-200">Proyectos</h5>
            </a>
            <a href="{{ route('cobro.index') ?? '#' }}" class="p-6 rounded-xl border border-outline-variant/30 dark:border-slate-700 hover:border-indigo-500/50 dark:hover:border-indigo-400/50 hover:bg-indigo-500/5 dark:hover:bg-indigo-400/10 transition-all text-center group">
                <span class="material-symbols-outlined text-4xl text-indigo-500 dark:text-indigo-400 mb-3 group-hover:scale-110 transition-transform">payments</span>
                <h5 class="font-bold text-slate-900 dark:text-slate-200">Cobros</h5>
            </a>
            <a href="{{ route('tesoreria.index') ?? '#' }}" class="p-6 rounded-xl border border-outline-variant/30 dark:border-slate-700 hover:border-green-500/50 dark:hover:border-green-400/50 hover:bg-green-500/5 dark:hover:bg-green-400/10 transition-all text-center group">
                <span class="material-symbols-outlined text-4xl text-green-500 dark:text-green-400 mb-3 group-hover:scale-110 transition-transform">account_balance</span>
                <h5 class="font-bold text-slate-900 dark:text-slate-200">Tesorería</h5>
            </a>
        </div>
    </section>
</div>
@endsection