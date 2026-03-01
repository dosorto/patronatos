<?php

use App\Models\Organization;
use App\Models\User;
use App\Models\Pais;
use App\Models\Departamento;
use App\Models\Municipio;
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

    public string $organization_name = '';
    public string $organization_email = '';
    public string $organization_phone = '';

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public string $id_pais = '';
    public string $id_departamento = '';
    public string $id_municipio = '';

    public $paises = [];
    public $departamentos = [];
    public $municipios = [];

    public function mount(): void
    {
        $this->paises = Pais::orderBy('nombre')->get();
    }

    public function updatedIdPais($value): void
    {
        $this->departamentos = Departamento::where('pais_id', $value)->orderBy('nombre')->get();
        $this->id_departamento = '';
        $this->municipios = [];
        $this->id_municipio = '';
    }

    public function updatedIdDepartamento($value): void
    {
        $this->municipios = Municipio::where('departamento_id', $value)->orderBy('nombre')->get();
        $this->id_municipio = 0;
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
            'id_pais'         => ['required', 'integer', 'min:1'],
            'id_departamento' => ['required', 'integer', 'min:1'],
            'id_municipio'    => ['required', 'integer', 'min:1'],
        ]);

        $provisioner = app(TenantProvisioner::class);

        $organization = Organization::create([
            'name'            => $this->organization_name,
            'slug'            => $this->generateOrganizationSlug($this->organization_name),
            'email'           => $this->organization_email ?: null,
            'phone'           => $this->organization_phone ?: null,
            'id_pais'         => $this->id_pais ?: null,
            'id_departamento' => $this->id_departamento ?: null,
            'id_municipio'    => $this->id_municipio ?: null,
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

        // Configurar conexión tenant
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

        // Guardar en sesión ANTES de crear el usuario
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

        $user = DB::transaction(function () use ($validated, $organization) {
            $adminRole = Role::firstOrCreate([
                'name'       => 'admin',
                'guard_name' => 'web',
            ]);

            $user = User::create([
                'organization_id'   => $organization->id, // ← ya no es null
                'name'              => $validated['name'],
                'email'             => strtolower($validated['email']),
                'email_verified_at' => now(),
                'password'          => Hash::make($validated['password']),
            ]);

            $user->assignRole($adminRole);

            return $user;
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        event(new Registered($user));
        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    private function validateStepOne(): void
    {
        $this->validate([
            'organization_name'  => ['required', 'string', 'min:3', 'max:255', 'unique:organizations,name'],
            'organization_email' => ['nullable', 'email', 'max:255'],
            'organization_phone' => ['nullable', 'string', 'max:30'],
            'id_pais'            => ['required', 'integer', 'min:1'],
            'id_departamento'    => ['required', 'integer', 'min:1'],
            'id_municipio'       => ['required', 'integer', 'min:1'],
        ]);
    }

    private function generateOrganizationSlug(string $name): string
    {
        $base = Str::slug($name);
        $seed = $base !== '' ? $base : 'organizacion';
        $slug = $seed;
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
            <div>
                <x-input-label for="organization_name" value="Nombre de la organización" />
                <x-text-input wire:model="organization_name" id="organization_name" class="mt-1 block w-full" type="text" required autofocus />
                <x-input-error :messages="$errors->get('organization_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="organization_email" value="Correo de la organización (opcional)" />
                <x-text-input wire:model="organization_email" id="organization_email" class="mt-1 block w-full" type="email" />
                <x-input-error :messages="$errors->get('organization_email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="organization_phone" value="Teléfono (opcional)" />
                <x-text-input wire:model="organization_phone" id="organization_phone" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('organization_phone')" class="mt-2" />
            </div>

            {{-- País --}}
            <div>
                <x-input-label for="pais_select" value="País" />
                <select id="pais_select"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Selecciona un país --</option>
                    @foreach ($paises as $pais)
                        <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('id_pais')" class="mt-2" />
            </div>

            {{-- Departamento --}}
            <div>
                <x-input-label for="departamento_select" value="Departamento" />
                <select id="departamento_select" disabled
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Selecciona un departamento --</option>
                </select>
                <x-input-error :messages="$errors->get('id_departamento')" class="mt-2" />
            </div>

            {{-- Municipio --}}
            <div>
                <x-input-label for="municipio_select" value="Municipio" />
                <select id="municipio_select" disabled
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Selecciona un municipio --</option>
                </select>
                <x-input-error :messages="$errors->get('id_municipio')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const paisSelect = document.getElementById('pais_select');
    const departamentoSelect = document.getElementById('departamento_select');
    const municipioSelect = document.getElementById('municipio_select');

    paisSelect.addEventListener('change', function () {
        const paisId = this.value;
        departamentoSelect.innerHTML = '<option value="">Cargando...</option>';
        municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
        departamentoSelect.disabled = true;
        municipioSelect.disabled = true;

        if (paisId) {
            fetch(`/departamentos-por-pais/${paisId}`)
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="">Seleccione un departamento</option>';
                    data.forEach(dept => options += `<option value="${dept.id}">${dept.nombre}</option>`);
                    departamentoSelect.innerHTML = options;
                    departamentoSelect.disabled = false;

                    // Sincronizar con Livewire
                    @this.set('id_pais', paisId);
                });
        } else {
            departamentoSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
            @this.set('id_pais', '');
        }
    });

    departamentoSelect.addEventListener('change', function () {
        const deptoId = this.value;
        municipioSelect.innerHTML = '<option value="">Cargando...</option>';
        municipioSelect.disabled = true;

        if (deptoId) {
            fetch(`/municipios-por-departamento/${deptoId}`)
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="">Seleccione un municipio</option>';
                    data.forEach(mun => options += `<option value="${mun.id}">${mun.nombre}</option>`);
                    municipioSelect.innerHTML = options;
                    municipioSelect.disabled = false;

                    // Sincronizar con Livewire
                    @this.set('id_departamento', deptoId);
                });
        } else {
            municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
            @this.set('id_departamento', '');
        }
    });

    municipioSelect.addEventListener('change', function () {
        // Sincronizar con Livewire
        @this.set('id_municipio', this.value);
    });
});
</script>
