<?php

namespace App\Services\Tenancy;

use App\Models\Organization;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantProvisioner
{
    public function provisionDatabase(Organization $organization): array
    {
        $centralConnection = config('tenancy.central_connection') ?: config('database.default');
        $tenantConnection = config('tenancy.tenant_connection', 'tenant');
        $prefix = config('tenancy.database_prefix', 'tenant_');

        $centralConfig = config("database.connections.{$centralConnection}");
        $driver = $centralConfig['driver'] ?? null;

        if (app()->environment('testing') || !in_array($driver, ['mysql', 'mariadb'], true)) {
            return [
                'connection' => config('database.default'),
                'host' => null,
                'port' => null,
                'database' => config('database.connections.' . config('database.default') . '.database'),
                'username' => null,
                'password' => null,
            ];
        }

        $databaseName = $this->tenantDatabaseName($organization, $prefix);
        DB::connection($centralConnection)->statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $tenantConfig = array_merge($centralConfig, [
            'database' => $databaseName,
        ]);

        config(["database.connections.{$tenantConnection}" => $tenantConfig]);
        DB::purge($tenantConnection);
        DB::reconnect($tenantConnection);

        Artisan::call('migrate', [
            '--database' => $tenantConnection,
            '--force' => true,
        ]);

        foreach (config('tenancy.seeders', []) as $seederClass) {
            Artisan::call('db:seed', [
                '--database' => $tenantConnection,
                '--class' => $seederClass,
                '--force' => true,
            ]);
        }

        // Deshabilitar FK checks temporalmente
        DB::connection($tenantConnection)->statement('SET FOREIGN_KEY_CHECKS=0');

        // Copiar tipo de organización
        $tipoOrg = DB::connection($centralConnection)->table('tipo_organizacion')
            ->where('id_tipo_organizacion', $organization->id_tipo_organizacion)
            ->first();

        if ($tipoOrg) {
            DB::connection($tenantConnection)->table('tipo_organizacion')->insertOrIgnore([
                'id_tipo_organizacion' => $tipoOrg->id_tipo_organizacion,
                'nombre'               => $tipoOrg->nombre,
                'descripcion'          => $tipoOrg->descripcion,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }

        // Copiar departamento
        $departamento = DB::connection($centralConnection)->table('departamentos')
            ->where('id', $organization->id_departamento)
            ->first();

        if ($departamento) {
            DB::connection($tenantConnection)->table('departamentos')->insertOrIgnore([
                'id'         => $departamento->id,
                'nombre'     => $departamento->nombre,
                'pais_id'    => $departamento->pais_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Copiar municipio
        $municipio = DB::connection($centralConnection)->table('municipios')
            ->where('id', $organization->id_municipio)
            ->first();

        if ($municipio) {
            DB::connection($tenantConnection)->table('municipios')->insertOrIgnore([
                'id'              => $municipio->id,
                'nombre'          => $municipio->nombre,
                'departamento_id' => $municipio->departamento_id,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // Copiar organización
        DB::connection($tenantConnection)->table('organizacion')->insert([
            'id_organizacion'      => $organization->id,
            'nombre'               => $organization->name,
            'telefono'             => $organization->phone,
            'rtn'                  => $organization->rtn,
            'direccion'            => $organization->direccion,
            'estado'               => $organization->estado ?? 'Activo',
            'fecha_creacion'       => $organization->fecha_creacion,
            'id_tipo_organizacion' => $organization->id_tipo_organizacion,
            'id_municipio'         => $organization->id_municipio,
            'id_departamento'      => $organization->id_departamento,
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        // Reactivar FK checks
        DB::connection($tenantConnection)->statement('SET FOREIGN_KEY_CHECKS=1');

        return [
            'connection' => $tenantConnection,
            'host' => $tenantConfig['host'] ?? null,
            'port' => (string) ($tenantConfig['port'] ?? ''),
            'database' => $tenantConfig['database'] ?? null,
            'username' => $tenantConfig['username'] ?? null,
            'password' => $tenantConfig['password'] ?? null,
        ];
    }

    private function tenantDatabaseName(Organization $organization, string $prefix): string
    {
        $base = Str::slug($organization->slug ?: $organization->name, '_');
        $base = $base !== '' ? $base : 'org';
        $name = "{$prefix}{$base}_{$organization->id}";

        return Str::limit($name, 64, '');
    }
}