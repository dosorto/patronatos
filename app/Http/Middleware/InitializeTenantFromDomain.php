<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantFromDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Identificar Dominios Centrales usando el Host actual y la configuración de la App
        $currentHost = $request->getHost();
        $parts = explode('.', $currentHost);
        
        $appUrl = config('app.url');
        $baseConfigHost = parse_url($appUrl, PHP_URL_HOST) ?? 'localhost';
        
        // Casos de Dominio Central
        $isIP = filter_var($currentHost, FILTER_VALIDATE_IP);
        $isLocalhost = ($currentHost === 'localhost');
        $isExactBase = ($currentHost === $baseConfigHost);
        $isWwwBase = ($currentHost === 'www.' . $baseConfigHost);
        $hasNoDots = (count($parts) < 2); // Caso localhost o nombres de red simples
        
        $isCentralDomain = $isIP || $isLocalhost || $isExactBase || $isWwwBase || $hasNoDots;
        
        // 2. Definir si es una ruta que obligatoriamente debe tratarse como central (Wizard, Registro, etc.)
        $isCentralRoute = $request->is('registro-organizacion*') 
            || $request->is('configuracioninicial*') 
            || $request->is('wizard/*') 
            || $request->is('organization/upload-logo*');

        $centralConnection = config('tenancy.central_connection', 'mysql');
        
        // Extraer subdominio si no es central
        $subdomain = null;
        if (!$isCentralDomain) {
            // Intentamos limpiar el host base del host actual para obtener el subdominio
            // Ej: "tenant.sisgap.com" -> str_replace(".sisgap.com", "") -> "tenant"
            $cleanSubdomain = str_replace(['www.', '.' . $baseConfigHost], '', $currentHost);
            $subdomainParts = explode('.', $cleanSubdomain);
            $subdomain = $subdomainParts[0];
        }

        // 3. Buscar organización basada en el subdominio (si no es ruta central forzada)
        $organization = null;
        if (!$isCentralDomain && !$isCentralRoute) {
            try {
                $organization = Organization::on($centralConnection)
                    ->where('slug', $subdomain)
                    ->first();
            } catch (\Exception $e) { }
        }

        // 4. CASO A: Identificación por Subdominio
        if ($organization && $organization->db_database) {
            $this->applyTenantConfig($organization, $request, $centralConnection);
        } 
        // 5. CASO B: Identificación por Sesión (Fallback para localhost/wizard/dashboard local)
        // Se activa si estamos en dominio central/localhost y NO es la ruta de registro
        elseif (($isCentralDomain || $isCentralRoute) && !$request->is('registro-organizacion*')) {
            config(['database.default' => $centralConnection]);
            
            if ($request->hasSession()) {
                $orgId = $request->session()->get('tenant_organization_id');
                
                if ($orgId) {
                    $request->session()->put('is_root', false);
                    try {
                        $organization = Organization::on($centralConnection)->find($orgId);
                        if ($organization && $organization->db_database) {
                            $this->applyTenantConfig($organization, $request, $centralConnection);
                        }
                    } catch (\Exception $e) { }
                } else {
                    $request->session()->put('is_root', true);
                }
            }
        }
        // 6. CASO C: Sin identificación (Entra como Root/Admin central)
        else {
            config(['database.default' => $centralConnection]);
        }

        return $next($request);
    }

    protected function applyTenantConfig($organization, $request, $centralConnection)
    {
        $tenantConnection = config('tenancy.tenant_connection', 'tenant');
        $baseConfig = config("database.connections.{$centralConnection}");

        if ($baseConfig) {
            $tenantConfig = array_merge($baseConfig, [
                'host'     => $organization->db_host     ?? $baseConfig['host'],
                'port'     => $organization->db_port     ?? $baseConfig['port'],
                'database' => $organization->db_database,
                'username' => $organization->db_username ?? $baseConfig['username'],
                'password' => $organization->db_password ?? $baseConfig['password'],
            ]);

            config([
                "database.connections.{$tenantConnection}" => $tenantConfig,
                'database.default' => $tenantConnection,
            ]);

            DB::purge($tenantConnection);

            if ($request->hasSession()) {
                if (!$request->session()->isStarted()) $request->session()->start();
                $request->session()->put('tenant_organization_id', $organization->id);
                
                // Solo forzamos is_root a false si no está ya marcado como true.
                // Esto permite que los usuarios root mantengan su estado al entrar a subdominios.
                if (!$request->session()->get('is_root')) {
                    $request->session()->put('is_root', false);
                }
            }
        }
    }
}
