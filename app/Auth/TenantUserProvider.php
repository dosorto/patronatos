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
        $isRoot = session('is_root', false);

        if ($isRoot) {
            $centralConnection = config('tenancy.central_connection', 'mysql');
            $model = $this->createModel();
            $model->setConnection($centralConnection);

            return $model->newQuery()
                ->where($model->getAuthIdentifierName(), $identifier)
                ->first();
        }

        $model = $this->createModel();
        $model->setConnection($this->getTenantConnection());

        return $model->newQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();
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