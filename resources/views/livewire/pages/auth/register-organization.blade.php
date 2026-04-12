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

new #[Layout('layouts.auth')] class extends Component
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
<div class="min-h-screen w-full relative flex flex-col items-center overflow-auto py-12 px-4">
    {{-- Fondo Fijo --}}
    <div class="fixed inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-900/90 via-sky-800/95 to-slate-900 z-10 backdrop-blur-sm"></div>
        <img alt="Fondo agua" class="w-full h-full object-cover opacity-30 scale-105" src="https://images.unsplash.com/photo-1549467657-30c8ff0e199d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" />
    </div>

    {{-- Logo / Header --}}
    <div class="relative z-20 flex flex-col items-center mb-8 text-center mt-6">
        <div class="w-16 h-16 bg-gradient-to-tr from-sky-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-[0_0_20px_rgba(56,189,248,0.4)] mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight mb-1">SISGAP</h1>
        <p class="text-sky-300 text-sm font-semibold tracking-wider uppercase mb-2">Registro de Organización</p>
        <p class="text-white/60 text-xs">Completa 2 pasos para iniciar con tu cuenta administradora.</p>
    </div>

    {{-- Stepper Progress --}}
    <div class="relative z-20 w-full max-w-[800px] mb-6">
        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 shadow-lg flex items-center">
            <div class="flex items-center flex-1 gap-3 px-2 sm:px-4 transition-all">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-inner transition-colors duration-300 {{ $step >= 1 ? 'bg-gradient-to-br from-sky-400 to-blue-600 text-white shadow-[0_0_15px_rgba(56,189,248,0.4)]' : 'bg-white/5 text-white/40' }}">1</div>
                <div>
                    <p class="text-sm font-bold text-white">Datos de la Organización</p>
                    <p class="text-[11px] text-sky-200/70">Nombre, contacto, tipo</p>
                </div>
            </div>
            <div class="h-[1px] flex-1 mx-2 sm:mx-4 transition-colors duration-300 {{ $step >= 2 ? 'bg-sky-400/50' : 'bg-white/10' }}"></div>
            <div class="flex items-center flex-1 gap-3 px-2 sm:px-4 transition-all">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-inner transition-colors duration-300 {{ $step >= 2 ? 'bg-gradient-to-br from-sky-400 to-blue-600 text-white shadow-[0_0_15px_rgba(56,189,248,0.4)]' : 'bg-white/5 text-white/40' }}">2</div>
                <div>
                    <p class="text-sm font-bold transition-colors duration-300 {{ $step >= 2 ? 'text-white' : 'text-white/40' }}">Cuenta Administradora</p>
                    <p class="text-[11px] transition-colors duration-300 {{ $step >= 2 ? 'text-sky-200/70' : 'text-white/30' }}">Acceso y credenciales</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjeta Principal Glassmorphism --}}
    <div class="relative z-20 w-full max-w-[800px] bg-white/10 backdrop-blur-2xl border border-white/20 rounded-[2.5rem] shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] p-6 sm:p-10 mb-10">
        
        <style>
            .glass-input {
                background-color: rgba(255, 255, 255, 0.05) !important;
                border: 1px solid rgba(255, 255, 255, 0.2) !important;
                border-radius: 1rem !important;
                color: white !important;
                height: 3.5rem !important;
                transition: all 0.3s ease !important;
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .glass-input:focus {
                border-color: #38bdf8 !important;
                box-shadow: 0 0 0 2px rgba(56,189,248,0.3) !important;
                background-color: rgba(255, 255, 255, 0.1) !important;
            }
            .glass-input::placeholder {
                color: rgba(186, 230, 253, 0.4) !important;
            }
            select.glass-input option {
                color: #1e293b !important;
                background-color: white !important;
            }
            .glass-label {
                display: block;
                font-size: 0.75rem;
                font-weight: 700;
                color: #bae6fd;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 0.5rem;
            }
            .glass-label-req {
                color: #fca5a5;
                margin-left: 0.25rem;
            }
        </style>

        @if($step == 1)
        <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            {{-- Bloque: Información Básica --}}
            <div class="bg-white/5 border border-white/10 rounded-[20px] p-6 relative">
                <div class="absolute -top-3 left-6 bg-gradient-to-r from-sky-500 to-blue-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-2 shadow-[0_2px_10px_rgba(56,189,248,0.5)]">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    Información Básica
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                    <div class="md:col-span-1 relative">
                        <label class="glass-label">Nombre de la organización<span class="glass-label-req">*</span></label>
                        <div class="relative">
                            <input 
                                wire:model.live.debounce.400ms="organization_name"
                                type="text"
                                placeholder="Ej. Patronato Los Pinos"
                                class="w-full glass-input pr-10
                                    {{ $this->checkOrganizationNameTaken() ? 'border-red-400 focus:border-red-400 focus:shadow-[0_0_0_2px_rgba(248,113,113,0.3)]' : '' }}
                                    {{ strlen($organization_name) >= 3 && !$this->checkOrganizationNameTaken() ? 'border-emerald-400 focus:border-emerald-400 focus:shadow-[0_0_0_2px_rgba(52,211,153,0.3)]' : '' }}"
                            >
                            @if(strlen($organization_name) >= 3)
                                @if($this->checkOrganizationNameTaken())
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </div>
                                @else
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                @endif
                            @endif
                        </div>
                        {{-- Mensaje de estado --}}
                        @if(strlen($organization_name) >= 3)
                            @if($this->checkOrganizationNameTaken())
                                <p class="text-red-300 text-[11px] mt-2 flex items-center gap-1 font-medium">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/></svg>
                                    Este nombre ya existe
                                </p>
                            @else
                                <p class="text-emerald-400 text-[11px] mt-2 flex items-center gap-1 font-medium">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                    Nombre disponible
                                </p>
                            @endif
                        @endif
                        <x-input-error :messages="$errors->get('organization_name')" class="mt-1 text-red-300" />
                    </div>

                    <div>
                        <label class="glass-label">RTN</label>
                        <input wire:model="rtn" type="text" placeholder="0000-0000-000000" class="w-full glass-input" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div>
                        <label class="glass-label">Correo electrónico</label>
                        <input wire:model="organization_email" type="email" placeholder="org@ejemplo.com" class="w-full glass-input">
                    </div>
                    <div>
                        <label class="glass-label">Teléfono</label>
                        <input wire:model="organization_phone" type="text" placeholder="Ej. 12345678" class="w-full glass-input" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div>
                        <label class="glass-label">Tipo de Org.<span class="glass-label-req">*</span></label>
                        <div class="relative">
                            <select wire:model="id_tipo_organizacion" class="w-full glass-input appearance-none">
                                <option value="">-- Selecciona --</option>
                                @foreach(TipoOrganizacion::all() as $tipo)
                                    <option value="{{ $tipo->id_tipo_organizacion }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sky-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="glass-label">Fecha de creación</label>
                        <input wire:model="fecha_creacion" type="date" class="w-full glass-input [color-scheme:dark]">
                    </div>
                </div>
            </div>

            {{-- Bloque: Ubicación --}}
            <div class="bg-white/5 border border-white/10 rounded-[24px] p-6 relative mt-10">
                <div class="absolute -top-3 left-6 bg-gradient-to-r from-sky-400 to-indigo-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-2 shadow-[0_2px_10px_rgba(56,189,248,0.5)]">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Ubicación
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-4">
                    <div>
                        <label class="glass-label">País<span class="glass-label-req">*</span></label>
                        <div class="relative">
                            <select wire:model.live="pais_id" class="w-full glass-input appearance-none">
                                <option value="">-- Selecciona --</option>
                                @foreach(Pais::all() as $p) <option value="{{ $p->id }}">{{ $p->nombre }}</option> @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sky-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="glass-label">Departamento<span class="glass-label-req">*</span></label>
                        <div class="relative">
                            <select wire:model.live="id_departamento" class="w-full glass-input appearance-none">
                                <option value="">-- Selecciona --</option>
                                @foreach($this->departamentos as $d) <option value="{{ $d->id }}">{{ $d->nombre }}</option> @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sky-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="glass-label">Municipio<span class="glass-label-req">*</span></label>
                        <div class="relative">
                            <select wire:model="id_municipio" class="w-full glass-input appearance-none">
                                <option value="">-- Selecciona --</option>
                                @foreach($this->municipios as $m) <option value="{{ $m->id }}">{{ $m->nombre }}</option> @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sky-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <label class="glass-label">Dirección exacta</label>
                        <input wire:model="direccion" type="text" placeholder="Colonia, calle, bloque..." class="w-full glass-input">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-8 border-t border-white/10 pt-6">
                <a href="{{ route('login') }}" class="text-sm text-sky-300 hover:text-white font-medium hover:underline underline-offset-4 transition-all">Regresar a Login</a>
                <button wire:click="nextStep" class="bg-gradient-to-r from-sky-400 to-blue-600 hover:from-sky-300 hover:to-blue-500 text-white font-extrabold py-3.5 px-10 rounded-2xl flex items-center gap-2 shadow-[0_4px_15px_rgba(56,189,248,0.3)] hover:shadow-[0_4px_25px_rgba(56,189,248,0.5)] transition-all active:scale-95 group">
                    Siguiente
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </div>
        @endif

        @if($step == 2)
        <div class="space-y-8 animate-in fade-in slide-in-from-right-8 duration-500">
             {{-- Resumen Org --}}
             <div class="flex items-center gap-4 bg-white/5 p-5 rounded-[1.5rem] border border-white/10 shadow-inner">
                <div class="w-14 h-14 bg-gradient-to-tr from-sky-400 to-blue-500 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg">
                    {{ strtoupper(substr($organization_name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-[10px] uppercase font-black text-sky-300 tracking-widest mb-1">Organización configurada</p>
                    <p class="text-xl font-bold text-white">{{ $organization_name }}</p>
                </div>
            </div>

            {{-- Bloque: Datos Administrador --}}
            <div class="bg-white/5 border border-white/10 rounded-[24px] p-6 relative">
                <div class="absolute -top-3 left-6 bg-gradient-to-r from-sky-500 to-indigo-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-2 shadow-[0_2px_10px_rgba(56,189,248,0.5)]">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Cuenta Administradora
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                    <div class="md:col-span-2">
                        <label class="glass-label">Nombre completo del admin<span class="glass-label-req">*</span></label>
                        <input wire:model="name" type="text" placeholder="Ej. Juan Pérez" class="w-full glass-input" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')">
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-300" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="glass-label">Correo electrónico (Usuario)<span class="glass-label-req">*</span></label>
                        <input wire:model="email" type="email" placeholder="admin@correo.com" class="w-full glass-input">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-300" />
                    </div>
                    <div>
                        <label class="glass-label">Contraseña<span class="glass-label-req">*</span></label>
                        <input wire:model="password" type="password" placeholder="••••••••" class="w-full glass-input">
                    </div>
                    <div>
                        <label class="glass-label">Confirmar Contraseña<span class="glass-label-req">*</span></label>
                        <input wire:model="password_confirmation" type="password" placeholder="••••••••" class="w-full glass-input">
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 mt-8 border-t border-white/10 pt-6">
                <button wire:click="previousStep" class="text-sky-300 hover:text-white font-bold flex items-center gap-2 group transition-colors">
                    <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Volver y Editar
                </button>
                <button wire:click="registerOrganization"
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto bg-gradient-to-r from-sky-400 to-blue-600 hover:from-sky-300 hover:to-blue-500 disabled:opacity-50 text-white font-extrabold py-3.5 px-10 rounded-2xl flex items-center justify-center gap-2 shadow-[0_4px_15px_rgba(56,189,248,0.3)] transition-all group active:scale-95">

                    <span wire:loading.remove wire:target="registerOrganization" class="flex items-center gap-2">
                        Completar Registro
                        <svg class="w-5 h-5 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>

                    <span wire:loading wire:target="registerOrganization" class="flex items-center gap-3">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Instalando Sistema...
                    </span>
                </button>
            </div>
        </div>
        @endif
    </div>
</div>