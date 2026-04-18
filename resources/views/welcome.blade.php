<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SISGAP | GIC SOLUTIONS</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;700;800&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
          tailwind.config = {
            darkMode: "class",
            theme: {
              extend: {
                colors: {
                  "primary": "#A2D2FF",
                  "secondary": "#BDE0FE",
                  "tertiary": "#CAF0F8",
                  "surface": "#F0F8FF",
                  "on-surface": "#1A3A5A",
                  "on-surface-variant": "#4A6A8A",
                  "outline": "#D1E9FF",
                  "accent-cyan": "#00B4D8",
                },
                fontFamily: {
                  "headline": ["Manrope", "sans-serif"],
                  "body": ["Plus Jakarta Sans", "sans-serif"],
                  "label": ["Plus Jakarta Sans", "sans-serif"]
                },
                borderRadius: {"DEFAULT": "1.5rem", "lg": "2.5rem", "xl": "3.5rem", "full": "9999px"},
              },
            },
          }
    </script>
    <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F0F8FF; color: #1A3A5A; }
            .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
            .crystalline-card { background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(162, 210, 255, 0.3); box-shadow: 0 8px 32px 0 rgba(162, 210, 255, 0.1); }
            .soft-glow { filter: drop-shadow(0 0 15px rgba(162, 210, 255, 0.4)); }
            .water-gradient { background: linear-gradient(135deg, #A2D2FF 0%, #BDE0FE 100%); }
    </style>
</head>
<body class="selection:bg-primary/30">

    <!-- Top Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-md shadow-sm shadow-sky-900/5">
        <div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
            <div class="text-xl font-extrabold text-sky-800 tracking-tight font-manrope">SISGAP</div>
            <div class="hidden md:flex gap-8">
                <a class="text-sky-600 font-semibold border-b-2 border-sky-400 pb-1 text-sm font-plus-jakarta" href="#">Inicio</a>
                <a class="text-slate-600 font-medium hover:text-sky-500 transition-all duration-300 text-sm font-plus-jakarta" href="#servicios">Servicios</a>
                <a class="text-slate-600 font-medium hover:text-sky-500 transition-all duration-300 text-sm font-plus-jakarta" href="#transparencia">Transparencia</a>
            </div>
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-sky-700 text-white px-6 py-2 rounded-full font-bold text-sm scale-95 hover:scale-100 active:scale-90 transition-transform shadow-[0_4px_10px_rgba(162,210,255,0.4)]">
                            Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sky-800 font-bold hover:text-sky-600 transition-colors px-4 hidden sm:block">
                            Iniciar Sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ url('/registro-organizacion') }}" class="bg-sky-700 text-white px-6 py-2 rounded-full font-bold text-sm scale-95 hover:scale-100 active:scale-90 transition-transform shadow-[0_4px_10px_rgba(162,210,255,0.4)]">
                                Crear Organización
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <header class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-surface/40 via-surface/80 to-surface z-10"></div>
            <img alt="Fondo agua" class="w-full h-full object-cover opacity-20 scale-110" src="https://images.unsplash.com/photo-1549467657-30c8ff0e199d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" />
        </div>
        <div class="relative z-20 max-w-7xl mx-auto px-8 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block px-4 py-1 rounded-full bg-primary/20 border border-primary/30 text-sky-700 text-xs font-bold tracking-widest uppercase mb-6">Innovación Hídrica</span>
                
                <h1 class="text-5xl md:text-7xl font-extrabold font-headline tracking-tight text-sky-900 leading-[1.1] mb-8">
                    SISGAP:<br><span class="text-accent-cyan">Sistema de Gestión de Agua y Patronatos</span>
                </h1>
                
                <p class="text-lg text-on-surface-variant max-w-xl mb-10 leading-relaxed font-body">
                    Transformamos la gestión tradicional en una infraestructura digital de élite. Control total, transparencia absoluta y eficiencia hídrica para tu comunidad.
                </p>
                
                <div class="flex flex-wrap gap-6">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="water-gradient text-sky-900 px-10 py-5 rounded-full font-extrabold text-lg shadow-xl shadow-primary/20 active:scale-95 transition-all">
                            Empieza Ahora
                        </a>
                    @endif
                    <a href="#servicios" class="flex items-center gap-3 px-8 py-5 rounded-full crystalline-card text-sky-800 font-bold hover:bg-white/80 transition-all">
                        <span class="material-symbols-outlined text-primary">play_circle</span>
                        Ver Demo
                    </a>
                </div>
            </div>
            
            <div class="hidden lg:block relative group">
                <div class="crystalline-card p-8 rounded-lg shadow-[0_20px_40px_rgba(162,210,255,0.2)] relative overflow-hidden transform group-hover:-translate-y-2 transition-transform duration-500">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-1">Consumo Comunitario</p>
                                <h3 class="text-4xl font-extrabold text-sky-900">45,280 m³</h3>
                            </div>
                            <span class="bg-primary/20 text-accent-cyan px-3 py-1 rounded-full text-sm font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">trending_up</span> +12% vs mes anterior
                            </span>
                        </div>
                        
                        <div class="h-48 w-full bg-tertiary/30 rounded-xl relative flex items-end p-4 gap-2">
                            <div class="w-full bg-primary/30 h-1/2 rounded-t-[4px]"></div>
                            <div class="w-full bg-primary/50 h-2/3 rounded-t-[4px]"></div>
                            <div class="w-full bg-primary/40 h-1/2 rounded-t-[4px]"></div>
                            <div class="w-full bg-accent-cyan h-full rounded-t-[4px] shadow-[0_0_15px_rgba(0,180,216,0.3)]"></div>
                            <div class="w-full bg-primary/70 h-3/4 rounded-t-[4px]"></div>
                            <div class="w-full bg-primary/50 h-1/2 rounded-t-[4px]"></div>
                        </div>
                    </div>
                </div>
                
                <div class="absolute -bottom-6 -left-6 crystalline-card p-6 rounded-lg shadow-xl animate-bounce-slow" style="animation: bounce 4s infinite;">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">shield</span>
                        </div>
                        <div>
                            <p class="text-xs text-on-surface-variant font-bold uppercase tracking-widest text-slate-500">Estado del Sistema</p>
                            <p class="text-sm text-sky-900 font-extrabold">100% Operativo</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </header>
    
    <!-- Steps Section -->
    <section id="servicios" class="py-24 bg-tertiary/10 relative">
        <div class="max-w-7xl mx-auto px-8">
            <div class="mb-20">
                <h2 class="text-4xl md:text-5xl font-extrabold font-headline text-sky-900 mb-4 tracking-tight">Eficiencia en 3 Pasos</h2>
                <div class="h-1.5 w-24 bg-primary rounded-full"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-12">
                <!-- Step 1 -->
                <div class="group crystalline-card p-8 rounded-3xl hover:border-primary/50 hover:shadow-2xl transition-all duration-300">
                    <div class="mb-8 w-20 h-20 rounded-full flex items-center justify-center bg-white/60 border border-primary/30 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl text-sky-600" style="font-variation-settings: 'FILL' 1;">groups</span>
                    </div>
                    <h3 class="text-2xl font-extrabold text-sky-900 mb-4">1. Digitaliza</h3>
                    <p class="text-on-surface-variant leading-relaxed font-medium">Censo automatizado de usuarios, registro unificado y geolocalización de todas las tomas de agua.</p>
                </div>
                <!-- Step 2 -->
                <div class="group crystalline-card p-8 rounded-3xl hover:border-primary/50 hover:shadow-2xl transition-all duration-300">
                    <div class="mb-8 w-20 h-20 rounded-full flex items-center justify-center bg-white/60 border border-primary/30 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl text-sky-600" style="font-variation-settings: 'FILL' 1;">payments</span>
                    </div>
                    <h3 class="text-2xl font-extrabold text-sky-900 mb-4">2. Gestiona</h3>
                    <p class="text-on-surface-variant leading-relaxed font-medium">Cobros inteligentes, facturación electrónica automática y control de morosidad simplificado.</p>
                </div>
                <!-- Step 3 -->
                <div class="group crystalline-card p-8 rounded-3xl hover:border-primary/50 hover:shadow-2xl transition-all duration-300">
                    <div class="mb-8 w-20 h-20 rounded-full flex items-center justify-center bg-white/60 border border-primary/30 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl text-sky-600" style="font-variation-settings: 'FILL' 1;">analytics</span>
                    </div>
                    <h3 class="text-2xl font-extrabold text-sky-900 mb-4">3. Ejecuta</h3>
                    <p class="text-on-surface-variant leading-relaxed font-medium">Reportes financieros transparentes, gestión de mantenimientos y ejecución de proyectos de infraestructura.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-32 px-8">
        <div class="max-w-5xl mx-auto crystalline-card p-16 rounded-[3rem] text-center relative overflow-hidden border border-primary/30 shadow-[0_20px_50px_rgba(162,210,255,0.2)]">
            <h2 class="text-4xl md:text-6xl font-extrabold font-headline text-sky-900 mb-8 tracking-tighter">¿Listo para modernizar tu comunidad?</h2>
            <p class="text-xl text-on-surface-variant mb-12 max-w-2xl mx-auto font-body">Únete a cientos de Juntas de Agua que ya están operando con la transparencia y agilidad del futuro.</p>
            @if (Route::has('register'))
                <a href="{{ url('/registro-organizacion') }}" class="water-gradient text-sky-900 px-16 py-6 inline-block rounded-full font-extrabold text-2xl shadow-xl shadow-primary/30 hover:scale-105 active:scale-95 transition-all">
                    Registrar Mi Junta
                </a>
            @endif
        </div>
    </section>
    
    <!-- Footer -->
    <footer id="transparencia" class="bg-white/50 w-full py-12 border-t border-primary/10">
        <div class="flex flex-col md:flex-row justify-between items-center px-12 gap-6 max-w-7xl mx-auto">
            <div>
                <div class="font-manrope font-extrabold text-sky-900 text-2xl">SISGAP</div>
                <p class="font-plus-jakarta text-xs uppercase tracking-widest text-slate-500 mt-2">© {{ date('Y') }} Gestión Integral Comunitaria (GIC SOLUTIONS).</p>
            </div>
            <div class="flex flex-wrap gap-8 md:justify-end">
                <a class="text-slate-500 hover:text-sky-600 font-plus-jakarta font-bold text-sm transition-opacity hover:opacity-80" href="#">Privacidad</a>
                <a class="text-slate-500 hover:text-sky-600 font-plus-jakarta font-bold text-sm transition-opacity hover:opacity-80" href="#">Términos</a>
                <a class="text-slate-500 hover:text-sky-600 font-plus-jakarta font-bold text-sm transition-opacity hover:opacity-80" href="https://wa.me/50498602116" target="_blank">Contactanos</a>
            </div>
        </div>
    </footer>
    <style>
        @keyframes bounce-slow {
            0%, 100% {
                transform: translateY(-5%);
            }
            50% {
                transform: translateY(5%);
            }
        }
        .animate-bounce-slow {
            animation: bounce-slow 4s ease-in-out infinite;
        }
    </style>
    <!-- Botón Flotante de WhatsApp - Diseño de Élite -->
    <a href="https://wa.me/50498602116" target="_blank" 
       class="fixed bottom-8 right-8 z-[100] flex items-center gap-4 bg-gradient-to-br from-[#25D366] to-[#075E54] text-white px-8 py-5 rounded-full shadow-[0_15px_45px_rgba(37,211,102,0.5)] hover:shadow-[0_20px_60px_rgba(37,211,102,0.7)] hover:-translate-y-3 active:scale-90 transition-all duration-500 group overflow-hidden border border-white/20">
        
        <!-- Efecto Glow de Fondo -->
        <div class="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/5 to-white/0 group-hover:via-white/20 transition-all duration-700"></div>

        <div class="relative flex items-center gap-4">
            <!-- Icono de WhatsApp con Animación de Latido -->
            <div class="p-1 group-hover:scale-110 transition-transform duration-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 drop-shadow-lg">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.311-4.437 9.887-9.885 9.887m8.415-18.303A11.334 11.334 0 0012.052 0C5.412 0 .011 5.4 0 12.04c0 2.123.554 4.197 1.608 6.04L0 24l6.117-1.605A11.237 11.237 0 0012.048 23.95c6.64 0 12.041-5.401 12.044-12.042 0-3.216-1.252-6.239-3.527-8.515"/>
                </svg>
            </div>
            
            <div class="flex flex-col">
                <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-white/70 -mb-1">¿Necesitas ayuda?</span>
                <span class="font-extrabold text-xl tracking-tight">Contáctanos</span>
            </div>
        </div>

        <!-- Indicador de Notificación Tipo 'Ping' -->
        <span class="flex h-4 w-4 absolute -top-1 -right-1">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 border-2 border-white shadow-sm"></span>
        </span>
    </a>
</body>
</html>
