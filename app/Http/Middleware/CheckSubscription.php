<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Rutas críticas que nunca deben ser bloqueadas (evitar bucles infinitos)
        if ($request->routeIs('logout') || $request->routeIs('servicio.suspendido')) {
            return $next($request);
        }

        // 2. Si el usuario no ha iniciado sesión (Guest), permitirle llegar al Login
        // Esto soluciona que se vea el error de "credenciales incorrectas"
        if (auth()->guest()) {
            return $next($request);
        }

        // 3. Si el usuario está autenticado, verificar si es ROOT (Desarrollador) de forma real en la DB central
        $user = auth()->user();
        if ($user) {
            // Buscamos si este correo tiene el rol root en la base de datos maestra
            $isRoot = \App\Models\User::on('mysql')
                ->where('email', $user->email)
                ->role('root')
                ->exists();

            if ($isRoot) {
                return $next($request);
            }
        }

        // 4. Para el resto de usuarios autenticados, verificar el estado de su organización
        $orgId = session('tenant_organization_id');
        if ($orgId) {
            $organization = \App\Models\Organization::on('mysql')->find($orgId);

            if ($organization) {
                // Verificar si está suspendido manualmente o expirado
                if ($organization->isSuspended() || !$organization->isSubscriptionActive()) {
                    return redirect()->route('servicio.suspendido');
                }
            }
        }

        return $next($request);
    }
}
