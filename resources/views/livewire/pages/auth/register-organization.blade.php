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
    public function updatedOrganizationName(): void
    {
        $this->validateOnly('organization_name', 
            [
                'organization_name' => ['required', 'string', 'min:3', 'max:255'],
            ],
            [
                'organization_name.required' => 'El nombre de la organización es obligatorio.',
                'organization_name.min'      => 'El nombre debe tener al menos 3 caracteres.',
                'organization_name.max'      => 'El nombre no puede superar los 255 caracteres.',
            ]
        );
    }

    public function getSuggestionsProperty(): \Illuminate\Support\Collection
    {
        if (strlen($this->organization_name) < 3) return collect();
        return Organization::where('name', 'like', '%' . $this->organization_name . '%')
            ->limit(5)
            ->pluck('name');
    }

    public function checkOrganizationNameTaken(): bool
    {
        if (strlen($this->organization_name) < 3) return false;
        return Organization::where('name', $this->organization_name)->exists();
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
    public function edit()
    {
        $org = Organization::find(session('tenant_organization_id'));

        return view('settings.organizacion.edit', [
            'org' => $org,
            'tipos' => TipoOrganizacion::all(),
            'paises' => Pais::all(),
            'departamentos' => Departamento::all(),
            'municipios' => Municipio::all(),
            'pais_id' => optional($org->departamento)->pais_id
        ]);
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

            // 🔴 AGREGAR AQUÍ: Ejecutar seeders del tenant
            \Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\TenantDatabaseSeeder',
                '--database' => $tenantConnection
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

            $this->redirect(route('configuracioninicial', absolute: false), navigate: true);
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
        $seed  = $base !== '' ? $base : 'organization';
        $slug  = $seed;
        $counter = 1;
        while (Organization::where('slug', $slug)->exists()) {
            $slug = "{$seed}-{$counter}";
            $counter++;
        }
        return $slug;
    }
}; ?>

<div class="min-h-screen bg-gray-300 flex flex-col items-center py-12 px-4">
    
    <style>
        /* Estilos personalizados para inputs y select */
        .custom-input {
            border: 1px solid #c8ca69 !important;
            border-radius: 12px !important;
            height: 44px !important;
            background-color: white !important;
            font-size: 0.875rem !important;
        }
        .custom-input:focus {
            border-color: #F59E42 !important;
            ring-color: #F59E42 !important;
        }
        .custom-label {
            font-size: 12px !important;
            font-weight: 700 !important;
            color: #7C4A00 !important;
            margin-bottom: 4px !important;
        }
        .location-input {
            border: 1px solid #B9D4F0 !important;
        }
        .location-label {
            color: #1E4D8C !important;
        }
    </style>

    {{-- Logo Laravel --}}
    <div class="mb-8">
        <svg class="w-16 h-16 text-gray-400" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M61.8548 14.6253L29.7533 0.138246C29.1171 -0.14613 28.3829 -0.14613 27.7467 0.138246L1.26168 12.0864C0.485961 12.4231 0 13.1797 0 14.0085V50.9915C0 51.8203 0.485961 52.5769 1.26168 52.9136L27.7467 64.8618C28.3829 65.1461 29.1171 65.1461 29.7533 64.8618L61.8548 50.3747C62.6305 50.0381 63.1165 49.2815 63.1165 48.4527V16.5473C63.1165 15.7185 62.6305 14.9619 61.8548 14.6253Z" fill="currentColor"/>
        </svg>
    </div>

    {{-- Título --}}
    <div class="text-center mb-8">
        <h1 class="text-[32px] font-extrabold text-[#1E3A5F] leading-tight">Crear Cuenta de Organización</h1>
        <p class="text-gray-500 text-sm mt-2">Completa 2 pasos para iniciar con tu cuenta administradora.</p>
    </div>

    {{-- Stepper --}}
    <div class="w-full max-w-[800px] bg-white rounded-2xl p-4 shadow-sm border border-blue-100 flex items-center mb-6">
        <div class="flex items-center flex-1 gap-3 px-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $step >= 1 ? 'bg-[#2563EB] text-white' : 'bg-gray-100 text-gray-400' }}">1</div>
            <div>
                <p class="text-sm font-bold text-[#1E3A5F]">Datos de la Organización</p>
                <p class="text-[11px] text-gray-400">Nombre, contacto, tipo</p>
            </div>
        </div>
        <div class="h-[1px] bg-gray-200 flex-1 mx-4"></div>
        <div class="flex items-center flex-1 gap-3 px-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $step >= 2 ? 'bg-[#2563EB] text-white' : 'bg-gray-100 text-gray-400' }}">2</div>
            <div>
                <p class="text-sm font-bold {{ $step >= 2 ? 'text-[#1E3A5F]' : 'text-gray-400' }}">Cuenta Administradora</p>
                <p class="text-[11px] text-gray-400">Acceso y credenciales</p>
            </div>
        </div>
    </div>

    {{-- Tarjeta Principal --}}
    <div class="w-full max-w-[800px] bg-white rounded-[32px] shadow-xl p-8 border border-blue-50">
        
        @if($step == 1)
        <div class="space-y-6">
            {{-- Bloque Naranja: Información Básica --}}
            <div class="bg-[#FFF9F2] border border-[#FDE9C7] rounded-[24px] p-6 relative">
                <div class="absolute -top-3 left-6 bg-[#F59E42] text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    Información Básica
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div class="md:col-span-1 relative">
                    <label class="custom-label">
                        Nombre de la organización <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            wire:model.live.debounce.400ms="organization_name"
                            type="text"
                            placeholder="Ej. Fundación Horizonte"
                            class="w-full custom-input focus:ring-[#F59E42] placeholder:text-gray-300 placeholder:opacity-40
                                {{ $this->checkOrganizationNameTaken() ? 'border-red-400 !border-red-400' : '' }}
                                {{ strlen($organization_name) >= 3 && !$this->checkOrganizationNameTaken() ? '!border-green-400' : '' }}"
                        >
                        {{-- Ícono dentro del input --}}
                        @if(strlen($organization_name) >= 3)
                            @if($this->checkOrganizationNameTaken())
                                <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                            @else
                                <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- Mensaje de estado --}}
                    @if(strlen($organization_name) >= 3)
                        @if($this->checkOrganizationNameTaken())
                            <p class="text-red-500 text-[11px] mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                Este nombre ya está en uso
                            </p>
                        @else
                            <p class="text-green-600 text-[11px] mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                Nombre disponible ✓
                            </p>
                        @endif
                    @endif

                    {{-- Dropdown sugerencias --}}
                    @if($this->suggestions->isNotEmpty() && $this->checkOrganizationNameTaken())
                        <div class="absolute z-10 w-full mt-1 bg-white border border-orange-200 rounded-xl shadow-lg overflow-hidden">
                            <p class="text-[10px] font-black text-gray-400 uppercase px-3 pt-2 pb-1 tracking-wider">
                                Nombres similares registrados
                            </p>
                            @foreach($this->suggestions as $suggestion)
                                <div class="px-3 py-2 text-sm text-gray-600 flex items-center gap-2 hover:bg-orange-50">
                                    <svg class="w-3 h-3 text-orange-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/>
                                    </svg>
                                    {{ $suggestion }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <x-input-error :messages="$errors->get('organization_name')" class="mt-1" />
                </div>
                    <div>
                        <label class="custom-label">RTN</label>
                        <input wire:model="rtn" type="text" placeholder="0000-0000-000000" class="w-full custom-input focus:ring-[#F59E42] placeholder:text-gray-700 placeholder:opacity-40 "oninput="this.value = this.value.replace(/[^0-9]/g, '') ">
                    </div>
                    <div>
                        <label class="custom-label">Correo electrónico</label>
                        <input wire:model="organization_email" type="email" placeholder="org@ejemplo.com" class="w-full custom-input focus:ring-[#F59E42]placeholder:text-gray-300 placeholder:opacity-40">
                    </div>
                    <div>
                        <label class="custom-label">Teléfono</label>
                        <input wire:model="organization_phone" type="text" placeholder="+504 0000-0000" class="w-full custom-input focus:ring-[#F59E42] placeholder:text-gray-800 placeholder:opacity-40 "oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div>
                        <label class="custom-label">Tipo de Organización <span class="text-red-500"> *</span></label>
                        <select wire:model="id_tipo_organizacion" class="w-full custom-input focus:ring-[#F59E42]">
                            <option value="">-- Selecciona --</option>
                            @foreach(TipoOrganizacion::all() as $tipo)
                                <option value="{{ $tipo->id_tipo_organizacion }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="custom-label">Fecha de creación</label>
                        <input wire:model="fecha_creacion" type="date" class="w-full custom-input focus:ring-[#F59E42]">
                    </div>
                </div>
            </div>

            {{-- Bloque Azul: Ubicación --}}
            <div class="bg-[#F0F7FF] border border-[#C7DFF7] rounded-[24px] p-6 relative">
                <div class="absolute -top-3 left-6 bg-[#4B8BF5] text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-blue-100">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Ubicación
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                    <div>
                        <label class="custom-label location-label">País<span class="text-red-500"> *</span></label>
                        <select wire:model.live="pais_id" class="w-full custom-input location-input focus:ring-blue-500">
                            <option value="">-- Selecciona --</option>
                            @foreach(Pais::all() as $p) <option value="{{ $p->id }}">{{ $p->nombre }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="custom-label location-label">Departamento<span class="text-red-500"> *</span></label>
                        <select wire:model.live="id_departamento" class="w-full custom-input location-input focus:ring-blue-500">
                            <option value="">-- Selecciona --</option>
                            @foreach($this->departamentos as $d) <option value="{{ $d->id }}">{{ $d->nombre }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="custom-label location-label">Municipio<span class="text-red-500"> *</span></label>
                        <select wire:model="id_municipio" class="w-full custom-input location-input focus:ring-blue-500">
                            <option value="">-- Selecciona --</option>
                            @foreach($this->municipios as $m) <option value="{{ $m->id }}">{{ $m->nombre }}</option> @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="custom-label location-label">Dirección</label>
                        <input wire:model="direccion" type="text" placeholder="Colonia, calle, número..." class="w-full custom-input location-input focus:ring-blue-500 placeholder:text-gray-900 placeholder:opacity-40">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-gray-600 underline">Ya tengo cuenta</a>
                <button wire:click="nextStep" class="bg-[#2563EB] hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-xl flex items-center gap-2 shadow-lg shadow-blue-100 transition-all">
                    Siguiente
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </div>
        @endif

        @if($step == 2)
        <div class="space-y-6">
             {{-- Resumen Org --}}
             <div class="flex items-center gap-4 bg-blue-50 p-4 rounded-2xl border border-blue-100">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                    {{ substr($organization_name, 0, 1) }}
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black text-blue-400 tracking-widest">Organización Seleccionada</p>
                    <p class="text-lg font-bold text-[#1E3A5F]">{{ $organization_name }}</p>
                </div>
            </div>

            {{-- Bloque: Datos Administrador --}}
            <div class="bg-[#FFF9F2] border border-[#FDE9C7] rounded-[24px] p-6 relative">
                <div class="absolute -top-3 left-6 bg-[#F59E42] text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Cuenta Administradora
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div class="md:col-span-2">
                        <label class="custom-label">Nombre completo <span class="text-red-500"> *</span></label>
                        <input wire:model="name" type="text" placeholder="Ej. Juan Pérez " class="w-full custom-input placeholder:text-gray-900 placeholder:opacity-40"oninput="this.value = this.value.replace(/[^aA-zZ ]/g, '') ">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="custom-label">Correo electrónico <span class="text-red-500"> *</span></label>
                        <input wire:model="email" type="email" placeholder="admin@correo.com" class="w-full custom-input placeholder:text-gray-900 placeholder:opacity-40">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div>
                        <label class="custom-label">Contraseña <span class="text-red-500"> *</span></label>
                        <input wire:model="password" type="password" placeholder="ingrese contraseña" class="w-full custom-input placeholder:text-gray-900 placeholder:opacity-40">
                    </div>
                    <div>
                        <label class="custom-label">Confirmar Contraseña <span class="text-red-500"> *</span></label>
                        <input wire:model="password_confirmation" type="password" placeholder="ingrese contraseña" class="w-full custom-input placeholder:text-gray-900 placeholder:opacity-40">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <button wire:click="previousStep" class="text-gray-400 hover:text-gray-600 font-bold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Volver
                </button>
                <button wire:click="registerOrganization"
                        wire:loading.attr="disabled"
                        class="bg-[#2563EB] hover:bg-blue-700 disabled:opacity-60 text-white font-bold py-3 px-10 rounded-xl flex items-center gap-2 shadow-lg shadow-blue-100 transition-all">

                    {{-- Texto normal --}}
                    <span wire:loading.remove wire:target="registerOrganization">
                        Finalizar Registro
                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>

                    {{-- Texto mientras carga --}}
                    <span wire:loading wire:target="registerOrganization" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Configurando organización...
                    </span>
                </button>
            </div>
        </div>
        @endif
    </div>
</div>