<?php

namespace App\Livewire\Forms;

use App\Models\Organization;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';
    
    #[Validate('nullable|exists:organizations,id')]
    public string $organization_id = '';

    #[Validate('boolean')]
    public bool $remember = false;


    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Si seleccionó organización, conectar al tenant
        if (!empty($this->organization_id)) {
            $organization = \App\Models\Organization::find($this->organization_id);

            if (!$organization || !$organization->db_database) {
                throw ValidationException::withMessages([
                    'form.organization_id' => 'Organización no válida.',
                ]);
            }

            $tenantConnection = config('tenancy.tenant_connection', 'tenant');
            $baseConfig = config('database.connections.' . config('tenancy.central_connection', 'mysql'));

            $tenantConfig = array_merge($baseConfig, [
                'host'     => $organization->db_host     ?? $baseConfig['host'],
                'port'     => $organization->db_port     ?? $baseConfig['port'],
                'database' => $organization->db_database,
                'username' => $organization->db_username ?? $baseConfig['username'],
                'password' => $organization->db_password ?? $baseConfig['password'],
            ]);

            session([
                'tenant_organization_id' => $organization->id,
                'tenant' => [
                    'host'     => $organization->db_host,
                    'port'     => $organization->db_port,
                    'database' => $organization->db_database,
                    'username' => $organization->db_username,
                    'password' => $organization->db_password,
                ]
            ]);

            config([
                "database.connections.{$tenantConnection}" => $tenantConfig,
                'database.default' => $tenantConnection,
            ]);

            \DB::purge($tenantConnection);

        } else {
            // Root: usar DB central, limpiar cualquier tenant de sesión anterior
            session()->forget(['tenant', 'tenant_organization_id']);
            config(['database.default' => config('tenancy.central_connection', 'mysql')]);
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}