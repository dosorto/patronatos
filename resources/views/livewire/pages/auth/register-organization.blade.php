<?php

use App\Models\Organization;
use App\Models\User;
use App\Models\Pais;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\TipoOrganizacion;
use App\Services\Tenancy\TenantProvisioner;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

new #[Layout('layouts.guest')] class extends Component
{
    public int $step = 1;

    // Paso 1 - Organización
    public string $organization_name = '';
    public string $organization_email = '';
    public string $organization_phone = '';
    public string $rtn = '';
    public string $direccion = '';
    public string $fecha_creacion = '';
    public string $estado = 'Activo';
    public ?int $id_tipo_organizacion = null;
    public ?int $pais_id = null;
    public ?int $id_departamento = null;
    public ?int $id_municipio = null;

    // Paso 2 - Administrador
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatedPaisId(): void
    {
        $this->id_departamento = null;
        $this->id_municipio = null;
    }

    public function updatedIdDepartamento(): void
    {
        $this->id_municipio = null;
    }

    public function getDepartamentosProperty()
    {
        if (!$this->pais_id) return collect();
        return Departamento::where('pais_id', $this->pais_id)->get();
    }

    public function getMunicipiosProperty()
    {
        if (!$this->id_departamento) return collect();
        return Municipio::where('departamento_id', $this->id_departamento)->get();
    }

    public function nextStep(): void
    {
        $this->validateStepOne();
        $this->step = 2;
    }

    public function previousStep(): void
    {
        $this->step = 1;
    }

    public function registerOrganization(): void
    {
        $this->validateStepOne();
        $validated = $this->validate([
            'name'     => ['required', 'string', 'min:3', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            DB::beginTransaction();

            $provisioner = app(TenantProvisioner::class);

            $organization = Organization::create([
                'name'               => $this->organization_name,
                'slug'               => $this->generateOrganizationSlug($this->organization_name),
                'email'              => $this->organization_email ?: null,
                'phone'              => $this->organization_phone ?: null,
                'rtn'                => $this->rtn ?: null,
                'direccion'          => $this->direccion ?: null,
                'fecha_creacion'     => $this->fecha_creacion ?: null,
                'estado'             => $this->estado,
                'id_tipo_organizacion' => $this->id_tipo_organizacion ?: null,
                'id_departamento'    => $this->id_departamento ?: null,
                'id_municipio'       => $this->id_municipio ?: null,
            ]);

            $tenant = $provisioner->provisionDatabase($organization);

            $organization->update([
                'db_connection' => $tenant['connection'],
                'db_host'       => $tenant['host'],
                'db_port'       => $tenant['port'],
                'db_database'   => $tenant['database'],
                'db_username'   => $tenant['username'],
                'db_password'   => $tenant['password'],
            ]);

            $tenantConnection = config('tenancy.tenant_connection', 'tenant');
            $baseConfig = config('database.connections.' . config('tenancy.central_connection', 'mysql'));

            config([
                "database.connections.{$tenantConnection}" => array_merge($baseConfig, [
                    'host'     => $organization->db_host     ?? $baseConfig['host'],
                    'port'     => $organization->db_port     ?? $baseConfig['port'],
                    'database' => $organization->db_database,
                    'username' => $organization->db_username ?? $baseConfig['username'],
                    'password' => $organization->db_password ?? $baseConfig['password'],
                ]),
                'database.default' => $tenantConnection,
            ]);

            DB::purge($tenantConnection);

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

            $adminRole = Role::firstOrCreate([
                'name'       => 'admin',
                'guard_name' => 'web',
            ]);

            $user = User::create([
                'organization_id'    => null,
                'name'               => $validated['name'],
                'email'              => strtolower($validated['email']),
                'email_verified_at'  => now(),
                'password'           => Hash::make($validated['password']),
            ]);

            $user->assignRole($adminRole);

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            event(new Registered($user));
            Auth::login($user);

            DB::commit();

            $this->redirect(route('dashboard', absolute: false), navigate: true);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    private function validateStepOne(): void
    {
        $this->validate([
            'organization_name'    => ['required', 'string', 'min:3', 'max:255', 'unique:organizations,name'],
            'organization_email'   => ['nullable', 'email', 'max:255'],
            'organization_phone'   => ['nullable', 'string', 'max:30'],
            'rtn'                  => ['nullable', 'string', 'max:20'],
            'direccion'            => ['nullable', 'string', 'max:255'],
            'fecha_creacion'       => ['nullable', 'date'],
            'estado'               => ['required', 'string'],
            'id_tipo_organizacion' => ['nullable', 'exists:tipo_organizacion,id_tipo_organizacion'],
            'id_departamento'      => ['nullable', 'exists:departamentos,id'],
            'id_municipio'         => ['nullable', 'exists:municipios,id'],
        ]);
    }

    private function generateOrganizationSlug(string $name): string
    {
        $base  = Str::slug($name);
        $seed  = $base !== '' ? $base : 'organizacion';
        $slug  = $seed;
        $counter = 1;

        while (Organization::where('slug', $slug)->exists()) {
            $slug = "{$seed}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}; ?>

<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900">Crear Cuenta de Organización</h1>
        <p class="mt-2 text-sm text-gray-600">Completa 2 pasos para iniciar con tu cuenta administradora.</p>
    </div>

    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">1</span>
            <span class="text-sm {{ $step >= 1 ? 'text-gray-900 font-medium' : 'text-gray-500' }}">Organización</span>
        </div>
        <div class="h-px flex-1 bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">2</span>
            <span class="text-sm {{ $step >= 2 ? 'text-gray-900 font-medium' : 'text-gray-500' }}">Administrador</span>
        </div>
    </div>

    @if ($step === 1)
        <form wire:submit="nextStep" class="space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <x-input-label for="organization_name" value="Nombre de la organización *" />
                    <x-text-input wire:model="organization_name" id="organization_name" class="mt-1 block w-full" type="text" required autofocus />
                    <x-input-error :messages="$errors->get('organization_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="rtn" value="RTN" />
                    <x-text-input wire:model="rtn" id="rtn" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('rtn')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="organization_email" value="Correo de la organización" />
                    <x-text-input wire:model="organization_email" id="organization_email" class="mt-1 block w-full" type="email" />
                    <x-input-error :messages="$errors->get('organization_email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="organization_phone" value="Teléfono" />
                    <x-text-input wire:model="organization_phone" id="organization_phone" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('organization_phone')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="id_tipo_organizacion" value="Tipo de Organización" />
                    <select wire:model="id_tipo_organizacion" id="id_tipo_organizacion"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Selecciona --</option>
                        @foreach(TipoOrganizacion::all() as $tipo)
                            <option value="{{ $tipo->id_tipo_organizacion }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('id_tipo_organizacion')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="fecha_creacion" value="Fecha de creación" />
                    <x-text-input wire:model="fecha_creacion" id="fecha_creacion" class="mt-1 block w-full" type="date" />
                    <x-input-error :messages="$errors->get('fecha_creacion')" class="mt-2" />
                </div>

                {{-- País (solo para filtrar departamentos, no se guarda) --}}
                <div>
                    <x-input-label for="pais_id" value="País" />
                    <select wire:model.live="pais_id" id="pais_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Selecciona un país --</option>
                        @foreach(Pais::all() as $pais)
                            <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="id_departamento" value="Departamento" />
                    <select wire:model.live="id_departamento" id="id_departamento"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Selecciona un departamento --</option>
                        @foreach($this->departamentos as $depto)
                            <option value="{{ $depto->id }}">{{ $depto->nombre }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('id_departamento')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="id_municipio" value="Municipio" />
                    <select wire:model="id_municipio" id="id_municipio"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Selecciona un municipio --</option>
                        @foreach($this->municipios as $municipio)
                            <option value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('id_municipio')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="direccion" value="Dirección" />
                    <x-text-input wire:model="direccion" id="direccion" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                </div>

            </div>

            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('login') }}" wire:navigate class="text-sm text-gray-600 underline">Ya tengo cuenta</a>
                <x-primary-button>Siguiente</x-primary-button>
            </div>
        </form>
    @endif

    @if ($step === 2)
        <form wire:submit="registerOrganization" class="space-y-4">
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700">
                Organización: <span class="font-semibold">{{ $organization_name }}</span>
            </div>

            <div>
                <x-input-label for="name" value="Nombre del administrador" />
                <x-text-input wire:model="name" id="name" class="mt-1 block w-full" type="text" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="Correo del administrador" />
                <x-text-input wire:model="email" id="email" class="mt-1 block w-full" type="email" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Contraseña" />
                <x-text-input wire:model="password" id="password" class="mt-1 block w-full" type="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Confirmar contraseña" />
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="mt-1 block w-full" type="password" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <x-secondary-button type="button" wire:click="previousStep">Volver</x-secondary-button>
                <x-primary-button>Crear organización e ingresar</x-primary-button>
            </div>
        </form>
    @endif
</div>