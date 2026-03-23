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
        <p class="text-on-surface-variant font-medium tracking-wide mb-1">GESTIÓN HÍDRICA</p>
        <h2 class="text-4xl font-light font-headline text-on-surface">Bienvenido, <span class="font-bold">{{ auth()->user()->name ?? 'Administrador' }}</span></h2>
        <p class="text-on-surface-variant text-sm mt-2">Resumen general del sistema y estadísticas principales.</p>
    </section>

    <!-- Visual Polish: Background Gradients -->
    <div class="absolute top-0 right-0 -z-10 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -z-10 w-[400px] h-[400px] bg-secondary-fixed/5 rounded-full blur-[100px] pointer-events-none"></div>

    <!-- Stats Bento Grid -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12 relative z-10">
        <!-- Card 1: Total Usuarios -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_4px_20px_rgba(0,88,188,0.04)] flex justify-between items-center group hover:-translate-y-1 transition-all duration-300">
            <div>
                <p class="text-on-surface-variant font-manrope font-semibold text-sm mb-2 tracking-tight">Total Usuarios</p>
                <h3 class="text-5xl font-bold font-manrope text-primary tracking-tighter">{{ \App\Models\User::count() }}</h3>
                <div class="mt-4 flex items-center gap-2 text-on-secondary-container bg-secondary-container/20 px-3 py-1 rounded-full w-fit">
                    <span class="material-symbols-outlined text-sm" data-icon="group">group</span>
                    <span class="text-xs font-bold">En el sistema</span>
                </div>
            </div>
            <div class="w-16 h-16 rounded-xl bg-primary-fixed-dim/20 flex items-center justify-center text-primary group-hover:rotate-6 transition-transform">
                <span class="material-symbols-outlined !text-4xl" data-icon="group">group</span>
            </div>
        </div>

        <!-- Card 2: Roles Activos -->
        <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_4px_20px_rgba(0,88,188,0.04)] flex justify-between items-center group hover:-translate-y-1 transition-all duration-300">
            <div>
                <p class="text-on-surface-variant font-manrope font-semibold text-sm mb-2 tracking-tight">Roles Activos</p>
                <h3 class="text-5xl font-bold font-manrope text-primary tracking-tighter">{{ \Spatie\Permission\Models\Role::count() }}</h3>
                <p class="mt-4 text-xs font-medium text-on-surface-variant/70">Asignados en plataforma</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-secondary-container/20 flex items-center justify-center text-on-secondary-container group-hover:-rotate-6 transition-transform">
                <span class="material-symbols-outlined !text-4xl" data-icon="badge">badge</span>
            </div>
        </div>

        <!-- Card 3: Feature Highlight (Asymmetric/Premium) -->
        <div class="bg-gradient-to-br from-primary to-primary-container p-8 rounded-xl shadow-xl flex flex-col justify-between text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-white/80 font-manrope font-semibold text-sm mb-1">Estado de Red</p>
                <h3 class="text-2xl font-bold font-manrope">Flujo Óptimo</h3>
            </div>
            <div class="relative z-10 flex items-center gap-3">
                <span class="material-symbols-outlined" data-icon="waves">waves</span>
                <span class="text-sm font-medium">Sistema funcionando</span>
            </div>
            <!-- Abstract Design Element -->
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        </div>
    </section>

    <!-- Accesos Rapidos Section -->
    <section class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_4px_20px_rgba(0,88,188,0.02)] relative z-10 border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h4 class="text-2xl font-bold font-headline mb-2">Accesos Rápidos</h4>
                <p class="text-on-surface-variant text-sm">Navegue a las diferentes secciones administrativas.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('miembro.index') ?? '#' }}" class="p-6 rounded-xl border border-outline-variant/30 hover:border-primary/50 hover:bg-primary/5 transition-all text-center group">
                <span class="material-symbols-outlined text-4xl text-primary mb-3 group-hover:scale-110 transition-transform">groups</span>
                <h5 class="font-bold text-on-surface">Miembros</h5>
            </a>
            <a href="{{ route('cooperantes.index') ?? '#' }}" class="p-6 rounded-xl border border-outline-variant/30 hover:border-teal-500/50 hover:bg-teal-500/5 transition-all text-center group">
                <span class="material-symbols-outlined text-4xl text-teal-600 mb-3 group-hover:scale-110 transition-transform">handshake</span>
                <h5 class="font-bold text-on-surface">Cooperantes</h5>
            </a>
            <a href="{{ route('proyecto.index') ?? '#' }}" class="p-6 rounded-xl border border-outline-variant/30 hover:border-blue-500/50 hover:bg-blue-500/5 transition-all text-center group">
                <span class="material-symbols-outlined text-4xl text-blue-500 mb-3 group-hover:scale-110 transition-transform">architecture</span>
                <h5 class="font-bold text-on-surface">Proyectos</h5>
            </a>
            <a href="{{ route('cobro.index') ?? '#' }}" class="p-6 rounded-xl border border-outline-variant/30 hover:border-indigo-500/50 hover:bg-indigo-500/5 transition-all text-center group">
                <span class="material-symbols-outlined text-4xl text-indigo-500 mb-3 group-hover:scale-110 transition-transform">payments</span>
                <h5 class="font-bold text-on-surface">Cobros</h5>
            </a>
        </div>
    </section>
</div>
@endsection