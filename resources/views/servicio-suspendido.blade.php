<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio Suspendido - SISGAP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Outfit:wght@500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    <style>
        body { font-family: 'Plus+Jakarta+Sans', sans-serif; }
        .font-headline { font-family: 'Outfit', sans-serif; }
        .crystalline-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Decoración de fondo -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-sky-200/40 rounded-full blur-[100px]"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-100/40 rounded-full blur-[100px]"></div>

    <div class="max-w-xl w-full relative z-10">
        <div class="crystalline-card rounded-[3rem] p-10 md:p-16 text-center">
            <div class="w-24 h-24 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                <span class="material-symbols-outlined text-5xl">lock_open</span>
            </div>
            
            <h1 class="text-4xl font-black font-headline text-slate-900 mb-6 leading-tight">Servicio Temporalmente<br><span class="text-red-500">Suspendido</span></h1>
            
            <p class="text-slate-500 text-lg mb-10 leading-relaxed">
                Su acceso a la plataforma ha sido pausado. Esto ocurre generalmente por un pago pendiente o mantenimiento en su cuenta.
            </p>

            <div class="space-y-4">
                <a href="https://wa.me/50498602116" target="_blank" class="w-full py-5 bg-emerald-500 text-white rounded-2xl font-black text-lg shadow-xl shadow-emerald-200 hover:bg-emerald-600 transition-all flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.311-4.437 9.887-9.885 9.887m8.415-18.303A11.334 11.334 0 0012.052 0C5.412 0 .011 5.4 0 12.04c0 2.123.554 4.197 1.608 6.04L0 24l6.117-1.605A11.237 11.237 0 0012.048 23.95c6.64 0 12.041-5.401 12.044-12.042 0-3.216-1.252-6.239-3.527-8.515"/></svg>
                    Contactar Soporte GIC
                </a>

                <form method="POST" action="/logout" class="block w-full">
                    @csrf
                    <button type="submit" class="w-full text-slate-400 hover:text-slate-600 font-bold text-sm transition-colors py-2">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
            
            <p class="mt-12 text-[10px] text-slate-300 uppercase tracking-widest font-bold">SISGAP System by GIC Solutions</p>
        </div>
    </div>
</body>
</html>
