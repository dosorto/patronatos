<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JuntaDigital | Gestión Hídrica de Élite</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            background-color: #0c1324;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .glass-card {
            background: rgba(7, 13, 31, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(195, 245, 255, 0.1);
        }
        .glow-cyan {
            box-shadow: 0 0 32px 0 rgba(0, 229, 255, 0.06);
        }
        .text-glow {
            text-shadow: 0 0 15px rgba(0, 229, 255, 0.4);
        }
        /* Fluid Wave Animation Replacement via CSS Gradients */
        .water-flow-bg {
            background: linear-gradient(180deg, rgba(0,229,255,0.05) 0%, rgba(12,19,36,0) 100%);
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: {
                "tertiary-fixed-dim": "#bec6e0",
                "on-primary": "#00363d",
                "secondary": "#bcc7de",
                "on-secondary-fixed-variant": "#3c475a",
                "on-tertiary-fixed": "#131b2e",
                "primary": "#c3f5ff",
                "surface-variant": "#2e3447",
                "on-secondary": "#263143",
                "on-background": "#dce1fb",
                "inverse-on-surface": "#2a3043",
                "surface-tint": "#00daf3",
                "tertiary-container": "#c8d0ea",
                "surface-container-highest": "#2e3447",
                "outline-variant": "#3b494c",
                "inverse-primary": "#006875",
                "on-primary-container": "#00626e",
                "on-tertiary": "#283044",
                "on-primary-fixed-variant": "#004f58",
                "surface-container-high": "#23293c",
                "tertiary-fixed": "#dae2fd",
                "surface-container-lowest": "#070d1f",
                "outline": "#849396",
                "on-secondary-container": "#aeb9d0",
                "primary-fixed-dim": "#00daf3",
                "primary-fixed": "#9cf0ff",
                "background": "#0c1324",
                "primary-container": "#00e5ff",
                "on-surface-variant": "#bac9cc",
                "error-container": "#93000a",
                "on-tertiary-fixed-variant": "#3f465c",
                "error": "#ffb4ab",
                "on-error": "#690005",
                "surface-bright": "#33394c",
                "on-primary-fixed": "#001f24",
                "surface-dim": "#0c1324",
                "surface-container-low": "#151b2d",
                "tertiary": "#e8ecff",
                "on-tertiary-container": "#51596f",
                "secondary-fixed-dim": "#bcc7de",
                "secondary-container": "#3e495d",
                "on-secondary-fixed": "#111c2d",
                "surface-container": "#191f31",
                "inverse-surface": "#dce1fb",
                "on-error-container": "#ffdad6",
                "on-surface": "#dce1fb",
                "secondary-fixed": "#d8e3fb",
                "surface": "#0c1324"
              },
              fontFamily: {
                "headline": ["Manrope"],
                "body": ["Manrope"],
                "label": ["Manrope"]
              },
              borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
            },
          },
        }
    </script>
</head>
<body class="bg-surface-dim text-on-surface selection:bg-primary-container selection:text-on-primary relative">
    
    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-50 bg-[#0c1324]/60 backdrop-blur-xl shadow-[0_0_32px_0_rgba(0,229,255,0.06)]">
        <div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-extrabold tracking-tighter text-white">JuntaDigital</span>
            </div>
            <div class="hidden lg:flex items-center gap-10">
            </div>
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-primary-container text-on-primary font-bold px-6 py-2.5 rounded-full hover:scale-105 active:scale-95 transition-all shadow-[0_0_20px_rgba(0,229,255,0.3)]">
                            Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-400 font-medium hover:text-white transition-colors px-4 hidden sm:block">
                            Iniciar Sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-primary-container text-on-primary font-bold px-6 py-2.5 rounded-full hover:scale-105 active:scale-95 transition-all shadow-[0_0_20px_rgba(0,229,255,0.3)]">
                                Crear Organización
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>
    
    <main class="relative pt-32 pb-20 px-6 md:px-12 overflow-hidden min-h-[90vh] flex flex-col justify-center">
        <!-- Ambient Radial Gradients -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-primary-container/5 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-primary-container/5 rounded-full blur-[120px] pointer-events-none"></div>
        
        <section class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <!-- Left Column: Content -->
            <div class="flex flex-col items-start space-y-8 z-10">
                <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-primary-container/10 border border-primary-container/20">
                    <span class="text-[0.6875rem] font-extrabold uppercase tracking-widest text-[#00E5FF]">Innovación Hídrica</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-extrabold leading-[1.1] tracking-tighter text-white">
                    Juntas de Agua y Patronatos:<br> <span class="text-[#00E5FF] text-glow line-clamp-2">El Futuro</span> del Recurso Comunitario
                </h1>
                <p class="text-lg text-on-surface-variant max-w-xl leading-relaxed">
                    Elevamos la gestión del agua a estándares de precisión técnica. Digitalización de élite para comunidades que exigen transparencia, eficiencia y sostenibilidad en tiempo real.
                </p>
                <div class="flex flex-wrap gap-4 pt-4">
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-primary-container text-on-primary font-bold rounded-full shadow-[0_0_30px_rgba(0,229,255,0.4)] hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                        Empieza Ahora
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                    @endif
                    <a href="#features" class="px-8 py-4 bg-transparent border border-outline-variant/30 text-white font-bold rounded-full hover:bg-white/5 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">bolt</span>
                        Características
                    </a>
                </div>
            </div>
            
            <!-- Right Column: Visualization -->
            <div class="relative group mt-12 lg:mt-0">
                
                <!-- Main Glass Card -->
                <div class="glass-card rounded-2xl p-8 relative z-10 overflow-hidden glow-cyan transform hover:scale-[1.02] transition-transform duration-500">
                    <div class="flex justify-between items-start mb-12">
                        <div>
                            <p class="text-[0.6875rem] font-bold uppercase tracking-widest text-slate-500 mb-1">Consumo Comunitario</p>
                            <h3 class="text-4xl font-extrabold text-white">45,280 m3</h3>
                            <p class="text-sm font-bold text-[#00E5FF] flex items-center gap-1 mt-2">
                                <span class="material-symbols-outlined text-sm">trending_up</span>
                                +12% vs mes anterior
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-primary-container/10 flex items-center justify-center border border-primary-container/20">
                            <span class="material-symbols-outlined text-[#00E5FF]">water_drop</span>
                        </div>
                    </div>
                    
                    <!-- Futuristic Graph Placeholder -->
                    <div class="h-48 w-full relative">
                        <svg class="w-full h-full overflow-visible" viewbox="0 0 400 150">
                            <defs>
                                <lineargradient id="lineGradient" x1="0%" x2="100%" y1="0%" y2="0%">
                                    <stop offset="0%" style="stop-color:#00E5FF;stop-opacity:0"></stop>
                                    <stop offset="50%" style="stop-color:#00E5FF;stop-opacity:1"></stop>
                                    <stop offset="100%" style="stop-color:#00E5FF;stop-opacity:0.2"></stop>
                                </lineargradient>
                                <lineargradient id="lineGradient2" x1="0%" x2="100%" y1="0%" y2="0%">
                                    <stop offset="0%" style="stop-color:#00E5FF;stop-opacity:0.1"></stop>
                                    <stop offset="50%" style="stop-color:#00E5FF;stop-opacity:0.5"></stop>
                                    <stop offset="100%" style="stop-color:#00E5FF;stop-opacity:0.1"></stop>
                                </lineargradient>
                            </defs>
                            <path d="M0,100 C100,60 200,140 400,80" fill="none" stroke="url(#lineGradient2)" stroke-width="8" filter="blur(4px)"></path>
                            <path d="M0,100 C100,60 200,140 400,80" fill="none" stroke="url(#lineGradient)" stroke-width="3"></path>
                        </svg>
                        <div class="absolute bottom-0 left-0 w-full h-2/3 bg-gradient-to-t from-[#0c1324] via-[#0c1324]/80 to-transparent"></div>
                    </div>
                </div>
                
                <!-- Floating Status Card -->
                <div class="absolute -bottom-8 -left-2 md:-left-12 glass-card rounded-lg p-5 z-20 flex items-center gap-4 shadow-[0_20px_40px_rgba(0,0,0,0.5)] border-primary-container/20 animate-bounce-slow">
                    <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center shadow-[0_0_15px_rgba(0,229,255,0.5)]">
                        <span class="material-symbols-outlined text-on-primary text-xl" style="font-variation-settings: 'FILL' 1;">shield</span>
                    </div>
                    <div>
                        <p class="text-[0.6rem] font-bold uppercase tracking-widest text-slate-400">Estado del Sistema</p>
                        <p class="text-sm font-extrabold text-white">100% Operativo</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section (Asymmetric Layout Fixed Alignment) -->
        <section class="max-w-7xl mx-auto mt-40 grid grid-cols-2 lg:grid-cols-4 gap-12 w-full">
            <div class="space-y-3 group cursor-default">
                <p class="text-4xl font-extrabold text-white text-glow group-hover:scale-110 transition-transform origin-left">120+</p>
                <div class="h-1 w-8 bg-[#00E5FF] rounded-full transition-all group-hover:w-16"></div>
                <p class="text-[0.6875rem] font-medium uppercase tracking-[0.2em] text-slate-500">Juntas Activas</p>
            </div>
            <div class="space-y-3 group cursor-default">
                <p class="text-4xl font-extrabold text-white text-glow group-hover:scale-110 transition-transform origin-left">0.2s</p>
                <div class="h-1 w-8 bg-[#00E5FF] rounded-full transition-all group-hover:w-16"></div>
                <p class="text-[0.6875rem] font-medium uppercase tracking-[0.2em] text-slate-500">Latencia Reporte</p>
            </div>
            <div class="space-y-3 group cursor-default">
                <p class="text-4xl font-extrabold text-white text-glow group-hover:scale-110 transition-transform origin-left">99.9%</p>
                <div class="h-1 w-8 bg-[#00E5FF] rounded-full transition-all group-hover:w-16"></div>
                <p class="text-[0.6875rem] font-medium uppercase tracking-[0.2em] text-slate-500">Disponibilidad</p>
            </div>
            <div class="space-y-3 group cursor-default">
                <p class="text-4xl font-extrabold text-white text-glow group-hover:scale-110 transition-transform origin-left">2.4M</p>
                <div class="h-1 w-8 bg-[#00E5FF] rounded-full transition-all group-hover:w-16"></div>
                <p class="text-[0.6875rem] font-medium uppercase tracking-[0.2em] text-slate-500">Litros Gest.</p>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="max-w-7xl mx-auto mt-60 w-full mb-40">
            <div class="text-center mb-20 space-y-4">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight">Capacidades de <span class="text-[#00E5FF]">Élite</span></h2>
                <div class="h-1 w-20 bg-[#00E5FF] mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-card p-10 rounded-3xl group hover:border-primary-container/40 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-primary-container/5 rounded-full blur-2xl group-hover:bg-primary-container/20 transition-all"></div>
                    <span class="material-symbols-outlined text-4xl text-[#00E5FF] mb-6 block" style="font-variation-settings: 'FILL' 1;">analytics</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Métricas Avanzadas</h3>
                    <p class="text-slate-400 leading-relaxed">Visualización de consumo y recaudación en tiempo real para una toma de decisiones informada.</p>
                </div>
                
                <div class="glass-card p-10 rounded-3xl group hover:border-primary-container/40 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-primary-container/5 rounded-full blur-2xl group-hover:bg-primary-container/20 transition-all"></div>
                    <span class="material-symbols-outlined text-4xl text-[#00E5FF] mb-6 block" style="font-variation-settings: 'FILL' 1;">security</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Seguridad Institucional</h3>
                    <p class="text-slate-400 leading-relaxed">Protección de datos bajo estándares bancarios, garantizando la integridad de su comunidad.</p>
                </div>
                
                <div class="glass-card p-10 rounded-3xl group hover:border-primary-container/40 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-primary-container/5 rounded-full blur-2xl group-hover:bg-primary-container/20 transition-all"></div>
                    <span class="material-symbols-outlined text-4xl text-[#00E5FF] mb-6 block" style="font-variation-settings: 'FILL' 1;">diversity_2</span>
                    <h3 class="text-2xl font-bold text-white mb-4">Gestión de Patronatos</h3>
                    <p class="text-slate-400 leading-relaxed">Módulos especializados para la administración de personal, activos y proyectos comunitarios.</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-[#3b494c]/15 bg-[#070d1f] relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-center px-12 py-16 w-full max-w-7xl mx-auto">
            <div class="flex flex-col gap-4 mb-10 md:mb-0 items-center md:items-start text-center md:text-left">
                <span class="text-3xl font-black text-white tracking-tighter">JuntaDigital</span>
                <p class="font-['Manrope'] text-[11px] uppercase tracking-[0.3em] text-slate-500">
                    © {{ date('Y') }} JuntaDigital. Tecnología para el Desarrollo Hídrico.
                </p>
            </div>
            <div class="flex flex-wrap justify-center gap-12">
                <div class="flex flex-col gap-4">
                    <span class="text-white font-bold text-sm">Plataforma</span>
                    <a class="text-slate-500 hover:text-[#00E5FF] transition-all text-sm" href="#">Inicio</a>
                    <a class="text-slate-500 hover:text-[#00E5FF] transition-all text-sm" href="{{ route('login') }}">Acceso</a>
                </div>
                <div class="flex flex-col gap-4">
                    <span class="text-white font-bold text-sm">Legal</span>
                    <a class="text-slate-500 hover:text-[#00E5FF] transition-all text-sm" href="#">Privacidad</a>
                    <a class="text-slate-500 hover:text-[#00E5FF] transition-all text-sm" href="#">Términos</a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pb-12 text-center">
            <div class="h-[1px] w-full bg-gradient-to-r from-transparent via-[#3b494c]/30 to-transparent mb-8"></div>
            <p class="text-slate-600 text-[10px] uppercase tracking-[0.5em]">LIDERANDO LA REVOLUCIÓN DIGITAL RURAL</p>
        </div>
    </footer>
</body>
</html>
</body>
</html>
