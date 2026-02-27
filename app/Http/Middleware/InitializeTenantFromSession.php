<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantFromSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasSession()) {
            return $next($request);
        }

        $request->session()->start();

        $tenant = $request->session()->get('tenant');

        if (!$tenant || empty($tenant['database'])) {
            // Intentar reconstruir desde organization_id
            $orgId = $request->session()->get('tenant_organization_id');
            if ($orgId) {
                $organization = Organization::find($orgId);
                if ($organization && $organization->db_database) {
                    $tenant = [
                        'host'     => $organization->db_host,
                        'port'     => $organization->db_port,
                        'database' => $organization->db_database,
                        'username' => $organization->db_username,
                        'password' => $organization->db_password,
                    ];
                    $request->session()->put('tenant', $tenant);
                }
            }
        }

        if (!$tenant || empty($tenant['database'])) {
            return $next($request);
        }

        $centralConnection = config('tenancy.central_connection', 'mysql');
        $tenantConnection = config('tenancy.tenant_connection', 'tenant');

        $baseConfig = config("database.connections.{$centralConnection}");
        if (!$baseConfig) {
            return $next($request);
        }

        $tenantConfig = array_merge($baseConfig, [
            'host'     => $tenant['host']     ?? $baseConfig['host']     ?? null,
            'port'     => $tenant['port']     ?? $baseConfig['port']     ?? null,
            'database' => $tenant['database'],
            'username' => $tenant['username'] ?? $baseConfig['username'] ?? null,
            'password' => $tenant['password'] ?? $baseConfig['password'] ?? null,
        ]);

        config([
            "database.connections.{$tenantConnection}" => $tenantConfig,
            'database.default' => $tenantConnection,
        ]);

        DB::purge($tenantConnection);

        return $next($request);
    }
}