<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Juntas de Agua - Gestión Moderna de Agua Comunitaria</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-secondary-container selection:text-on-secondary-container antialiased">

    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 w-full z-50 px-6 py-4 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl shadow-[0px_4px_20px_rgba(0,88,188,0.04)]">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-teal-400 bg-clip-text text-transparent font-headline">Juntas de Agua</span>
            </div>
            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-blue-500 transition-colors duration-300">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:block text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-blue-500 transition-colors duration-300">Iniciar sesión</a>
                    @if (Route::has('register.organization'))
                        <a href="{{ route('register.organization') }}" class="bg-gradient-to-br from-primary to-primary-container text-on-primary px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-primary/20 scale-95 active:scale-90 transition-transform hidden sm:flex items-center justify-center">
                            Crear organización
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-24">
        <!-- Hero Section -->
        <section class="relative overflow-hidden px-6 py-24 lg:py-40">
            <!-- Asymmetrical Background Gradients -->
            <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-secondary-container/20 blur-[120px] rounded-full -z-10"></div>
            <div class="absolute bottom-[0%] left-[-10%] w-[600px] h-[600px] bg-primary/10 blur-[150px] rounded-full -z-10"></div>
            
            <div class="max-w-7xl mx-auto text-center relative z-10 w-full">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-primary-container text-sm font-bold mb-6 tracking-wide">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    Innovación en gestión comunitaria
                </div>

                <h1 class="font-headline text-5xl lg:text-8xl font-extrabold tracking-tight text-on-surface mb-8 leading-[1.1]">
                    Gestión moderna para <br class="hidden sm:block"/>
                    <span class="text-primary">Juntas de Agua</span>
                </h1>
                <p class="max-w-2xl mx-auto text-on-surface-variant text-lg lg:text-xl font-medium mb-12 leading-relaxed">
                    Digitaliza el recurso más vital. Transparencia, eficiencia y tecnología para la administración comunitaria del agua potable. Todo centralizado de manera segura.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    @guest
                        <a href="{{ route('register.organization') }}" class="w-full sm:w-auto px-10 py-5 bg-gradient-to-br from-primary to-primary-container text-on-primary rounded-xl font-bold text-lg shadow-xl shadow-primary/25 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                            Empezar ahora
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-5 bg-surface-container-highest text-on-primary-fixed-variant rounded-xl font-bold text-lg hover:bg-surface-container-high transition-all active:scale-95 flex justify-center items-center">
                            Iniciar sesión
                        </a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-10 py-5 bg-gradient-to-br from-primary to-primary-container text-on-primary rounded-xl font-bold text-lg shadow-xl shadow-primary/25 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                            Ir al Panel de Control
                            <span class="material-symbols-outlined text-xl">start</span>
                        </a>
                    @endguest
                </div>
            </div>
        </section>

        <!-- Features Bento Grid -->
        <section class="px-6 py-24 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1: Gestión de Miembros -->
                <div class="glass-card p-10 rounded-xl shadow-[0px_10px_40px_rgba(0,0,0,0.02)] border border-white/40 flex flex-col items-start text-left group hover:shadow-blue-500/10 transition-shadow">
                    <div class="w-16 h-16 rounded-xl bg-primary-fixed flex items-center justify-center mb-8 group-hover:scale-110 transition-transform shadow-sm">
                        <span class="material-symbols-outlined text-primary text-3xl" data-icon="group" data-weight="fill" style="font-variation-settings: 'FILL' 1;">group</span>
                    </div>
                    <h3 class="font-headline text-2xl font-bold text-on-surface mb-4">Gestión de Miembros</h3>
                    <p class="text-on-surface-variant font-medium leading-relaxed">
                        Censo digital completo de beneficiarios con geolocalización de tomas y estado de conexión en tiempo real dentro del sistema.
                    </p>
                </div>

                <!-- Feature 2: Control de Cobros -->
                <div class="glass-card p-10 rounded-xl shadow-[0px_10px_40px_rgba(0,0,0,0.02)] border border-white/40 flex flex-col items-start text-left group hover:shadow-teal-500/10 transition-shadow relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-teal-50 rounded-bl-full -z-10 opacity-50"></div>
                    <div class="w-16 h-16 rounded-xl bg-secondary-container flex items-center justify-center mb-8 group-hover:scale-110 transition-transform shadow-sm">
                        <span class="material-symbols-outlined text-on-secondary-container text-3xl" data-icon="receipt_long" data-weight="fill" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
                    </div>
                    <h3 class="font-headline text-2xl font-bold text-on-surface mb-4">Control de Cobros</h3>
                    <p class="text-on-surface-variant font-medium leading-relaxed">
                        Automatización de facturación mensual, multas por mora, impresión ágil y recordatorios de pago integrados.
                    </p>
                </div>

                <!-- Feature 3: Transparencia -->
                <div class="glass-card p-10 rounded-xl shadow-[0px_10px_40px_rgba(0,0,0,0.02)] border border-white/40 flex flex-col items-start text-left group hover:shadow-indigo-500/10 transition-shadow">
                    <div class="w-16 h-16 rounded-xl bg-tertiary-fixed flex items-center justify-center mb-8 group-hover:scale-110 transition-transform shadow-sm">
                        <span class="material-symbols-outlined text-tertiary-fixed-auto text-3xl text-slate-800" data-icon="visibility" data-weight="fill" style="font-variation-settings: 'FILL' 1;">visibility</span>
                    </div>
                    <h3 class="font-headline text-2xl font-bold text-on-surface mb-4">Transparencia</h3>
                    <p class="text-on-surface-variant font-medium leading-relaxed">
                        Reportes financieros abiertos y claros para la comunidad. Rendición de cuentas para respaldar cada proyecto comunal.
                    </p>
                </div>
            </div>
        </section>

        <!-- Metric Showcase Section (Asymmetrical Layout) -->
        <section class="px-6 py-24 bg-surface-container-low overflow-hidden">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-secondary-container text-on-secondary-container text-xs font-bold tracking-wider uppercase mb-6 shadow-sm shadow-emerald-700/10">
                        <span class="material-symbols-outlined text-sm">water_drop</span> Impacto Real
                    </div>
                    <h2 class="font-headline text-4xl lg:text-6xl font-extrabold text-on-surface mb-8 leading-tight">
                        Optimiza cada <br/> gota de gestión.
                    </h2>
                    <div class="space-y-8">
                        <div class="flex items-start gap-6 group hover:translate-x-2 transition-transform">
                            <div class="w-1.5 h-16 bg-primary rounded-full shadow-lg shadow-primary/30 group-hover:bg-blue-500 transition-colors"></div>
                            <div>
                                <h4 class="font-bold text-xl text-on-surface mb-1">Reducción de Mora</h4>
                                <p class="text-on-surface-variant font-medium">Automatiza y disminuye la morosidad rápidamente gestionando tus cobros desde una única plataforma.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-6 group hover:translate-x-2 transition-transform">
                            <div class="w-1.5 h-16 bg-secondary-fixed-dim rounded-full shadow-lg shadow-teal-500/30 group-hover:bg-teal-400 transition-colors"></div>
                            <div>
                                <h4 class="font-bold text-xl text-on-surface mb-1">Ahorro Administrativo</h4>
                                <p class="text-on-surface-variant font-medium">Olvídate de procesos manuales; ahorra decenas de horas en planillas, reportes y recibos.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2 relative mt-16 lg:mt-0">
                    <div class="bg-surface-container-lowest p-8 rounded-2xl shadow-2xl shadow-blue-900/10 border border-slate-100 relative z-10 hover:-translate-y-2 transition-transform duration-500">
                        <img class="rounded-xl w-full h-auto object-cover" alt="Dashboard showing water usage analytics and financial charts" src="https://lh3.googleusercontent.com/aida-public/AB6AXuALOUN1iqNum-ytNdI1LOKek6lgJZbfBNo-S2Wuo8eMILcbk9NmIwA0kJwqX0yb482Wgl82yxexCLKWTwBW811enelPvOplX1VrT5m8uwZqCzsmHT1ShtCD2157XCUdBNwNiT42VyjIsYCO9DcdQ1GPWBbfU4-lF7Wq4YhMqGToq7KEVIe_hakT8wy-vbsgDD0_EgdFgbnQkFIAMRWijiMhAFGyr3Em1K6br-sRHnodH3FtKGGcT4mfdc-id0HPM0HNqm8uGmYCAVzy" />
                        
                        <!-- Floating Glass Metric -->
                        <div class="absolute -bottom-8 -left-4 sm:-left-8 glass-card p-6 rounded-2xl shadow-2xl max-w-[220px] border border-white/60">
                            <p class="text-xs font-bold text-on-surface-variant uppercase mb-1 tracking-widest">Estado Sistémico</p>
                            <div class="flex items-end gap-2 text-primary">
                                <span class="text-3xl font-bold">100%</span>
                                <span class="material-symbols-outlined mb-1">trending_up</span>
                            </div>
                            <div class="w-full h-2.5 bg-surface-container rounded-full mt-3 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-r from-teal-400 to-blue-500 rounded-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative backdrop shape -->
                    <div class="absolute -top-12 -right-12 w-80 h-80 bg-primary/10 rounded-full blur-3xl -z-0"></div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="px-6 py-24 text-center">
            <div class="max-w-4xl mx-auto glass-card p-12 lg:p-20 rounded-[2.5rem] border border-white/40 shadow-2xl shadow-primary/5 bg-gradient-to-b from-white/80 to-blue-50/50">
                <h2 class="font-headline text-3xl lg:text-5xl font-extrabold text-on-surface mb-6 drop-shadow-sm">¿Listo para transformar tu Junta de Agua?</h2>
                <p class="text-on-surface-variant font-medium text-lg mb-10">Únete al ecosistema de gestión que potencia tu patronato con agilidad y modernidad.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @if (Route::has('register.organization'))
                        <a href="{{ route('register.organization') }}" class="px-8 py-4 bg-primary text-white font-bold rounded-xl hover:bg-primary-container transition-all shadow-xl shadow-primary/30 flex justify-center items-center">
                            Crear organización gratis
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-primary text-white font-bold rounded-xl hover:bg-primary-container transition-all shadow-xl shadow-primary/30 flex justify-center items-center">
                            Ingresar ahora
                        </a>
                    @endif
                    <a href="#" class="px-8 py-4 bg-white/50 border-2 border-primary text-primary font-bold rounded-xl hover:bg-primary/5 transition-all flex justify-center items-center">
                        Solicitar soporte técnico
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="w-full py-12 px-6 mt-auto bg-slate-50 dark:bg-slate-950 border-t border-slate-200/60">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 max-w-7xl mx-auto">
            <div class="flex flex-col gap-2 items-center md:items-start text-center md:text-left">
                <div class="flex items-center gap-2">
                    <div class="bg-gradient-to-br from-blue-500 to-teal-400 p-1.5 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <span class="text-lg font-bold text-slate-900 dark:text-slate-100 font-headline">Juntas de Agua</span>
                </div>
                <p class="text-sm font-medium font-body text-slate-500 mt-2">© {{ date('Y') }} Sistema de Gestión Comunitaria. Todos los derechos reservados.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-8">
                <a class="text-xs font-bold tracking-wider uppercase text-slate-500 hover:text-teal-600 transition-all" href="#">Privacidad</a>
                <a class="text-xs font-bold tracking-wider uppercase text-slate-500 hover:text-teal-600 transition-all" href="#">Términos</a>
                <a class="text-xs font-bold tracking-wider uppercase text-slate-500 hover:text-teal-600 transition-all" href="#">Contacto</a>
            </div>
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-slate-600 hover:bg-primary hover:text-white transition-all shadow-sm cursor-pointer">
                    <span class="material-symbols-outlined text-lg">public</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
