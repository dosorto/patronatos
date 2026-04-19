<?php

namespace App\Auth;

use App\Models\Organization;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class TenantUserProvider extends EloquentUserProvider
{
    protected function getTenantConnection(): string
    {
        if (config('database.connections.tenant')) {
            return 'tenant';
        }

        $session = session();
        if ($session) {
            $orgId = $session->get('tenant_organization_id');
            if ($orgId) {
                $organization = Organization::on('mysql')->find($orgId);
                if ($organization && $organization->db_database) {
                    $centralConnection = config('tenancy.central_connection', 'mysql');
                    $baseConfig = config("database.connections.{$centralConnection}");

                    config([
                        'database.connections.tenant' => array_merge($baseConfig, [
                            'host'     => $organization->db_host     ?? $baseConfig['host'],
                            'port'     => $organization->db_port     ?? $baseConfig['port'],
                            'database' => $organization->db_database,
                            'username' => $organization->db_username ?? $baseConfig['username'],
                            'password' => $organization->db_password ?? $baseConfig['password'],
                        ]),
                        'database.default' => 'tenant',
                    ]);

                    \DB::purge('tenant');
                    return 'tenant';
                }
            }
        }

        return config('database.default');
    }

    public function retrieveById($identifier): ?Authenticatable
    {
        // 1. Si la sesión dice que somos ROOT, vamos directo a la central
        if (session('is_root')) {
            $centralConnection = config('tenancy.central_connection', 'mysql');
            $modelCentral = $this->createModel();
            $modelCentral->setConnection($centralConnection);

            $centralUser = $modelCentral->newQuery()
                ->where($modelCentral->getAuthIdentifierName(), $identifier)
                ->first();

            if ($centralUser && $centralUser->hasRole('root')) {
                return $centralUser;
            }
        }

        // 2. Si no es root o no se encontró en la central, buscamos en el tenant
        $model = $this->createModel();
        $model->setConnection($this->getTenantConnection());

        $user = $model->newQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();

        if ($user) {
            return $user;
        }

        return null;
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $model = $this->createModel();
        $model->setConnection($this->getTenantConnection());

        $query = $model->newQuery();

        foreach ($credentials as $key => $value) {
            if (!str_contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        $user = $query->first();

        if ($user) {
            return $user;
        }

        // Fallback root
        $centralConnection = config('tenancy.central_connection', 'mysql');
        $modelCentral = $this->createModel();
        $modelCentral->setConnection($centralConnection);
        $queryCentral = $modelCentral->newQuery();
        foreach ($credentials as $key => $value) {
            if (!str_contains($key, 'password')) {
                $queryCentral->where($key, $value);
            }
        }

        $userCentral = $queryCentral->first();

        if ($userCentral && $userCentral->hasRole('root')) {
            // IMPORTANTE: Marcamos la sesión como root para que retrieveById sepa 
            // que debe buscar en la central en la siguiente petición.
            if (session()) session(['is_root' => true]);
            return $userCentral;
        }

        return null;
    }

    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        $model = $this->createModel();
        $model->setConnection($this->getTenantConnection());

        return $model->newQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->where($model->getRememberTokenName(), $token)
            ->first();
    }
}