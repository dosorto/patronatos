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
        <p class="font-medium tracking-wide mb-1 text-slate-600 dark:text-slate-400">GESTIÓN HÍDRICA</p>
        <h2 class="text-4xl font-light font-headline text-slate-900 dark:text-white">Bienvenido, <span class="font-bold">{{ auth()->user()->name ?? 'Administrador' }}</span></h2>
        <p class="text-sm mt-2 text-slate-600 dark:text-slate-400">Resumen general del sistema y estadísticas de su organización.</p>
    </section>

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
                <span class="material-symbols-outlined !text-3xl" data-icon="architecture">architecture</span>
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
                <span class="material-symbols-outlined text-4xl text-blue-500 dark:text-blue-400 mb-3 group-hover:scale-110 transition-transform">architecture</span>
                <h5 class="font-bold text-slate-900 dark:text-slate-200">Proyectos</h5>
            </a>
            <a href="{{ route('cobro.index') ?? '#' }}" class="p-6 rounded-xl border border-outline-variant/30 dark:border-slate-700 hover:border-indigo-500/50 dark:hover:border-indigo-400/50 hover:bg-indigo-500/5 dark:hover:bg-indigo-400/10 transition-all text-center group">
                <span class="material-symbols-outlined text-4xl text-indigo-500 dark:text-indigo-400 mb-3 group-hover:scale-110 transition-transform">payments</span>
                <h5 class="font-bold text-slate-900 dark:text-slate-200">Cobros</h5>
            </a>
        </div>
    </section>
</div>
@endsection