<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GIC Admin')</title>
    
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">

    {{-- Navbar Maestro --}}
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Branding --}}
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gradient-to-tr from-sky-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg transform hover:rotate-12 transition-transform duration-300">
                        <span class="material-symbols-outlined text-white">admin_panel_settings</span>
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-tight text-gray-900 dark:text-white">GIC <span class="text-blue-600">Solutions</span> Admin</span>
                        <div class="flex md:hidden items-center gap-1.5 mt-[-2px]">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            <span class="text-[8px] font-black uppercase text-emerald-600 dark:text-emerald-400 tracking-tighter">Root Session</span>
                        </div>
                    </div>
                </div>
                
                {{-- Right Actions --}}
                <div class="flex items-center gap-4 md:gap-8">
                    {{-- Status Badge (Desktop) --}}
                    <div class="hidden md:flex items-center gap-2 px-4 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 rounded-full border border-emerald-100 dark:border-emerald-800/30">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-black uppercase tracking-[0.1em]">Entorno Central Maestro</span>
                    </div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="group flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-gray-500 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 transition-all">
                            <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">logout</span>
                            <span class="hidden sm:inline">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Container --}}
    <main class="py-12 px-4">
        <div class="max-w-7xl mx-auto">
            
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="mb-8 p-5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/20 text-emerald-700 dark:text-emerald-400 rounded-2xl flex items-center gap-4 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800 flex items-center justify-center">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                    </div>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-5 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/20 text-red-700 dark:text-red-400 rounded-2xl flex items-center gap-4 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-800 flex items-center justify-center">
                        <span class="material-symbols-outlined text-lg">error</span>
                    </div>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')
            
        </div>
    </main>

    {{-- Footer --}}
    <footer class="py-12 border-t border-gray-100 dark:border-gray-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.4em]">
                &copy; {{ date('Y') }} GIC Solutions • Central Management Suite • Powering SISGAP Platform
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
