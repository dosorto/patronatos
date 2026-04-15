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
        // 1. Obtener el subdominio desde el Host
        $host = $request->getHost(); 
        $parts = explode('.', $host);
        
        // Determinar si estamos en un dominio central (localhost, IP, o dominio base sin subdominio)
        $isIP = filter_var($host, FILTER_VALIDATE_IP);
        $isLocalhost = ($host === 'localhost');
        $hasNoSubdomain = (count($parts) < 2);
        
        $isCentralDomain = $isIP || $isLocalhost || $hasNoSubdomain;
        
        // 2. Definir si es una ruta que obligatoriamente debe tratarse como central (Wizard, Registro, etc.)
        $isCentralRoute = $request->is('registro-organizacion*') 
            || $request->is('configuracioninicial*') 
            || $request->is('wizard/*') 
            || $request->is('organization/upload-logo*');

        $centralConnection = config('tenancy.central_connection', 'mysql');
        $subdomain = $parts[0];

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
                if (!$request->session()->isStarted()) $request->session()->start();
                
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
                $request->session()->put('is_root', false);
            }
        }
    }
}
