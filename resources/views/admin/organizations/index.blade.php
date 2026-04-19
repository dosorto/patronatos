@extends('layouts.admin')

@section('title', 'Panel de Organizaciones | SISGAP')

@section('content')
    <div class="container mx-auto">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Panel de Organizaciones Maestras</h1>
                <p class="text-gray-600 dark:text-gray-400">Gestión global de patronatos y sus usuarios técnicos registrados.</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-blue-100 text-blue-800 text-xs font-semibold px-4 py-2 rounded-xl dark:bg-blue-900 dark:text-blue-200 flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-lg">domain</span>
                    <span class="font-bold">{{ $organizations->count() }} Organizaciones Registradas</span>
                </div>
            </div>
        </div>

        {{-- Grid de Organizaciones --}}
        <div class="grid gap-6">
            @foreach($organizations as $org)
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="p-8">
                        <div class="flex flex-col lg:flex-row justify-between lg:items-start gap-8">
                            
                            {{-- Info de Organización --}}
                            <div class="flex items-start gap-6 lg:w-1/3">
                                <div class="w-20 h-20 rounded-2xl bg-gray-50 dark:bg-gray-900 flex items-center justify-center border border-gray-100 dark:border-gray-700 overflow-hidden flex-shrink-0 shadow-inner">
                                    @if($org->logo)
                                        <img src="{{ Storage::url($org->logo) }}" alt="Logo" class="w-full h-full object-contain p-2">
                                    @else
                                        <span class="material-symbols-outlined text-4xl text-gray-300 dark:text-gray-700">domain</span>
                                    @endif
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black text-gray-900 dark:text-white leading-tight mb-1">{{ $org->name }}</h2>
                                    <div class="flex flex-col gap-1">
                                        <p class="text-sm text-blue-600 dark:text-blue-400 font-bold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-xs">link</span>
                                            {{ $org->slug }}.{{ parse_url(config('app.url'), PHP_URL_HOST) }}
                                        </p>
                                        <p class="text-xs text-gray-400 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-xs">calendar_today</span>
                                            Desde: {{ $org->created_at->format('d M, Y') }}
                                        </p>
                                    </div>
                                    
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        {{-- Badge de Plan --}}
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                            @if($org->plan_name == 'Macro') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                                            @elseif($org->plan_name == 'Residencial') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                            @else bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 @endif">
                                            {{ $org->plan_name ?? 'Básico' }}
                                        </span>
                                        {{-- Badge de Estado --}}
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                            @if($org->subscription_status == 'active') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                            @elseif($org->subscription_status == 'suspended') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                            @else bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 @endif">
                                            {{ $org->subscription_status ?? 'Desconocido' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Usuarios Técnicos --}}
                            <div class="lg:w-1/2">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="material-symbols-outlined text-gray-400 text-lg">badge</span>
                                    <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Usuarios Encargados ({{ $org->users->count() }})</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @forelse($org->users as $user)
                                        <div class="group flex items-center gap-3 p-3 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm font-black shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-all">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="overflow-hidden">
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-tight truncate">{{ $user->name }}</p>
                                                <p class="text-[10px] text-gray-500 truncate">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-2 text-center py-4 rounded-2xl border-2 border-dashed border-gray-100 dark:border-gray-800">
                                            <p class="text-xs text-gray-400 italic">No hay usuarios técnicos registrados.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Footer informativo --}}
        <div class="mt-12 text-center">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.3em]">SISGAP Security System • GIC Solutions</p>
        </div>
    </div>
@endsection
