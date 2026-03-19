<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Juntas de Agua y Patronatos</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-gray-50 text-gray-900 selection:bg-blue-200 selection:text-blue-900">
    <!-- Navbar -->
    <nav class="absolute top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-2">
                    <!-- Icon -->
                    <div class="bg-gradient-to-br from-blue-500 to-teal-400 p-2 rounded-xl shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-sm font-medium text-gray-700 hover:text-blue-600 transition">Iniciar sesión</a>
                        @if (Route::has('register.organization'))
                            <a href="{{ route('register.organization') }}" class="text-sm font-semibold bg-blue-600 text-white px-5 py-2.5 rounded-full hover:bg-blue-700 transition shadow-lg shadow-blue-600/30 hover:-translate-y-0.5 relative overflow-hidden group">
                                <span class="relative z-10">Crear organización</span>
                                <div class="absolute inset-0 h-full w-full scale-0 rounded-full transition-all duration-300 group-hover:scale-100 group-hover:bg-white/10"></div>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden min-h-screen flex flex-col justify-center">
        <!-- Background shapes -->
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-100/60 via-gray-50 to-gray-50"></div>
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3">
            <div class="w-96 h-96 bg-blue-400/20 rounded-full blur-3xl animate-pulse" style="animation-duration: 4s;"></div>
        </div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3">
            <div class="w-96 h-96 bg-teal-400/20 rounded-full blur-3xl animate-pulse" style="animation-duration: 5s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 w-full">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-sm font-medium mb-6">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                El mejor sistema de gestión comunitaria
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-gray-900 mb-6 font-inter drop-shadow-sm">
                Gestión moderna para <br class="hidden sm:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-teal-500">Juntas de Agua</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500 mb-10 leading-relaxed">
                Administre cobros, directivas y proyectos comunitarios de manera transparente y eficiente. Todo en una plataforma segura y fácil de usar.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @guest
                    <a href="{{ route('register.organization') }}" class="inline-flex justify-center items-center gap-2 rounded-full bg-blue-600 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-blue-600/30 hover:bg-blue-700 transition-all hover:-translate-y-1 active:scale-95">
                        Comienza creando tu organización
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-full border-2 border-gray-200 bg-white px-8 py-4 text-base font-semibold text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition-all active:scale-95">
                        Iniciar sesión
                    </a>
                @else
                    <a href="{{ url('/dashboard') }}" class="inline-flex justify-center items-center gap-2 rounded-full bg-blue-600 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-blue-600/30 hover:bg-blue-700 transition-all hover:-translate-y-1 active:scale-95">
                        Ir al Panel de Control
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                @endguest
            </div>
            
            <!-- Features Grid below Heroes -->
            <div class="mt-20 lg:mt-24 max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                    <!-- Feature 1 -->
                    <div class="bg-white/60 backdrop-blur-lg rounded-[2rem] p-8 border border-white/40 shadow-xl shadow-gray-200/50 hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1 group">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-blue-100/50 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Gestión de Miembros</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">Administre fácilmente a todos los afiliados, datos personales y la estructura de su directiva en un entorno seguro.</p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="bg-white/60 backdrop-blur-lg rounded-[2rem] p-8 border border-white/40 shadow-xl shadow-gray-200/50 hover:shadow-teal-500/10 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-teal-50 rounded-bl-full -z-10 opacity-50"></div>
                        <div class="w-14 h-14 bg-gradient-to-br from-teal-100 to-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-teal-100/50 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Control de Cobros</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">Mantenga un registro en tiempo real de los aportes, cuotas mensuales e históricos de pago de manera automatizada.</p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="bg-white/60 backdrop-blur-lg rounded-[2rem] p-8 border border-white/40 shadow-xl shadow-gray-200/50 hover:shadow-indigo-500/10 transition-all duration-300 hover:-translate-y-1 group">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-indigo-100/50 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Transparencia</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">Genere reportes detallados y gráficas para respaldar cada proyecto y asamblea comunitaria.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
            <div class="flex items-center gap-2 mb-4">
                <div class="bg-gray-100 p-1.5 rounded-lg">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
            </div>
            <p class="text-gray-400 text-sm font-medium">
                &copy; {{ date('Y') }} Sistema de Gestión Comunitaria. Todos los derechos reservados.
            </p>
        </div>
    </footer>
</body>
</html>
