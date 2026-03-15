@extends(request()->boolean('wizard') ? 'layouts.app' : 'layouts.app')

@section('title', 'Nuevo Miembro')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Miembro</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Registra un nuevo miembro en la organización</p>
    </div>

    {{-- 🔴 BLOQUE DE ERROR MEJORADO --}}
    @if($errors->has('persona_id'))
        <div id="errorDiv" class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-6 py-4 rounded-lg shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold uppercase text-xs tracking-widest mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        Esta persona ya es miembro
                    </p>
                    <p class="text-sm">{{ $errors->first('persona_id') }}</p>
                </div>
                <button type="button" onclick="document.getElementById('errorDiv').remove()" class="text-red-500 hover:text-red-700 font-bold text-2xl leading-none">×</button>
            </div>
        </div>
    @endif

    {{-- Errores de validación del servidor --}}
    @if($errors->any() && !$errors->has('persona_id'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <p class="text-red-800 dark:text-red-200 font-medium">Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('miembro.store') }}" method="POST" id="memberWizard">
        @csrf
        @if($isWizard)
            <input type="hidden" name="wizard" value="1">
        @endif
        <input type="hidden" name="persona_id" id="persona_id" value="{{ old('persona_id') }}">
        <input type="hidden" name="crear_persona" id="crear_persona" value="{{ old('crear_persona', '0') }}">

        {{-- Steps Indicator --}}
        <div class="mb-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 right-0 top-4 h-0.5 bg-gray-200 dark:bg-gray-700 z-0"></div>
                <div class="absolute left-0 top-4 h-0.5 bg-blue-600 z-0 transition-all duration-500" id="progressBar" style="width: 0%"></div>

                @foreach([1 => 'Datos de Persona', 2 => 'Información del Miembro'] as $num => $label)
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div id="step-circle-{{ $num }}"
                             class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300
                             {{ $num === 1 ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-400 dark:text-gray-500' }}">
                            <span id="step-icon-{{ $num }}">{{ $num }}</span>
                        </div>
                        <span id="step-label-{{ $num }}"
                              class="text-xs font-medium hidden sm:block transition-colors duration-300
                              {{ $num === 1 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500' }}">
                            {{ $label }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">

            {{-- Step 1: Datos de Persona --}}
            <div id="step-1">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Datos de Persona
                </h2>

                {{-- Búsqueda de Persona --}}
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar Persona Existente</label>
                    <div class="relative group">
                        <input type="text" id="searchInput" 
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm text-base" 
                            placeholder="Ingrese nombre o DNI para buscar...">
                        <button type="button" id="btnSearch" class="absolute right-2 top-2 bottom-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all shadow-md active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </div>
                    <div id="resultsListContainer" class="hidden mt-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-2 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Coincidencias</div>
                        <ul id="resultsList" class="divide-y divide-gray-200 dark:divide-gray-700 max-h-56 overflow-y-auto"></ul>
                    </div>
                </div>

                {{-- Botón Registrar Nueva Persona --}}
                <div class="flex justify-end mb-8">
                    <button type="button" id="btnNewPersona" class="text-sm font-bold text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 px-4 py-2 rounded-lg transition-colors">
                        + Registrar Nueva Persona
                    </button>
                </div>

                {{-- Campos de Persona --}}
                <div id="personaFields" class="{{ old('crear_persona') == '1' ? '' : 'hidden' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="nueva_nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombres *</label>
                            <input type="text" name="nueva_nombre" id="nueva_nombre" value="{{ old('nueva_nombre') }}" 
                                class="p-valida w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <x-input-error :messages="$errors->get('nueva_nombre')" />
                        </div>
                        <div class="space-y-2">
                            <label for="nueva_apellido" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Apellidos *</label>
                            <input type="text" name="nueva_apellido" id="nueva_apellido" value="{{ old('nueva_apellido') }}" 
                                class="p-valida w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <x-input-error :messages="$errors->get('nueva_apellido')" />
                        </div>
                        <div class="space-y-2">
                            <label for="nueva_dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">DNI *</label>
                            <input type="text" name="nueva_dni" id="nueva_dni" value="{{ old('nueva_dni') }}" 
                                class="p-valida dni-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <small class="text-gray-400 text-xs">Solo números</small>
                            <x-input-error :messages="$errors->get('nueva_dni')" />
                        </div>
                        <div class="space-y-2">
                            <label for="nueva_fecha_nacimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Nacimiento *</label>
                            <input type="date" name="nueva_fecha_nacimiento" id="nueva_fecha_nacimiento" value="{{ old('nueva_fecha_nacimiento') }}" 
                                class="p-valida w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <x-input-error :messages="$errors->get('nueva_fecha_nacimiento')" />
                        </div>
                        <div class="space-y-2">
                            <label for="nueva_sexo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Género *</label>
                            <select name="nueva_sexo" id="nueva_sexo" 
                                class="p-valida w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Seleccione...</option>
                                <option value="M" {{ old('nueva_sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('nueva_sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            <x-input-error :messages="$errors->get('nueva_sexo')" />
                        </div>
                        <div class="space-y-2">
                            <label for="nueva_telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
                            <input type="text" name="nueva_telefono" id="nueva_telefono" value="{{ old('nueva_telefono') }}" 
                                class="phone-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <small class="text-gray-400 text-xs">Solo números</small>
                        </div>
                        <div class="lg:col-span-3 space-y-2">
                            <label for="nueva_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" name="nueva_email" id="nueva_email" value="{{ old('nueva_email') }}" 
                                placeholder="ejemplo@correo.com" class="email-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <small class="text-gray-400 text-xs">Formato válido de correo</small>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" id="resetStep1" class="text-sm font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 px-4 py-2 rounded-lg transition-colors">
                            Limpiar selección
                        </button>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="button" onclick="goToStep(2)"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                        Siguiente
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Step 2: Información del Miembro --}}
            <div id="step-2" class="hidden">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Información del Miembro
                </h2>

                {{-- Resumen de Persona --}}
                <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">Registrando a:</p>
                    <p id="resumenPersona" class="text-lg font-bold text-gray-900 dark:text-white"></p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dirección *</label>
                        <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" required 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                            placeholder="Calle, número y referencias">
                        <x-input-error :messages="$errors->get('direccion')" />
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <button type="button" onclick="goToStep(1)"
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Anterior
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Guardar Miembro
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    let currentStep = 1;

    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->has('persona_id'))
            // Limpiar paso 1
            document.getElementById('persona_id').value = '';
            document.getElementById('crear_persona').value = '0';
            document.getElementById('personaFields').classList.add('hidden');
            
            // Limpiar paso 2
            document.querySelectorAll('#step-2 input').forEach(i => i.value = '');
            document.querySelectorAll('#step-2 textarea, #step-2 select').forEach(i => i.value = '');
        @endif
        
        const personas = @json($personas);
        const inputsValida = document.querySelectorAll('.p-valida');

        // Validación y filtrado de DNI (solo números)
        document.getElementById('nueva_dni').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            validarStep1();
        });

        // Validación y filtrado de teléfono (solo números)
        document.getElementById('nueva_telefono').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        // Validación de email en tiempo real
        document.getElementById('nueva_email').addEventListener('input', function(e) {
            const emailRegex = /^[^\s@]*@?[^\s@]*\.?[^\s@]*$/;
            const value = e.target.value;
            
            if(value === '' || emailRegex.test(value)) {
                e.target.classList.remove('border-red-500');
            } else {
                e.target.classList.add('border-red-500');
            }
        });

        function validarStep1() {
            const esNuevo = document.getElementById('crear_persona').value === '1';
            const pId = document.getElementById('persona_id').value;
            let isValid = false;

            if (esNuevo) {
                const n = document.getElementById('nueva_nombre').value.trim();
                const a = document.getElementById('nueva_apellido').value.trim();
                const d = document.getElementById('nueva_dni').value.trim();
                const f = document.getElementById('nueva_fecha_nacimiento').value.trim();
                const s = document.getElementById('nueva_sexo').value.trim();
                
                // Validar que DNI solo contenga números y tenga longitud válida
                const dniValido = /^\d+$/.test(d) && d.length > 0;
                
                isValid = n !== '' && a !== '' && dniValido && f !== '' && s !== '';
            } else {
                isValid = pId !== '';
            }
        }

        document.getElementById('btnSearch').onclick = function() {
            const query = document.getElementById('searchInput').value.toLowerCase().trim();
            const list = document.getElementById('resultsList');
            const container = document.getElementById('resultsListContainer');
            list.innerHTML = '';
            if (!query) return;

            const filtered = personas.filter(p => p.nombre.toLowerCase().includes(query) || p.dni.includes(query));

            if(filtered.length > 0) {
                filtered.forEach(p => {
                    const li = document.createElement('li');
                    li.className = "group px-6 py-4 hover:bg-blue-50 dark:hover:bg-gray-800 cursor-pointer transition-all flex justify-between items-center";
                    li.innerHTML = `
                        <div>
                            <p class="text-[12px] text-gray-400 uppercase font-bold tracking-tighter">DNI: ${p.dni}</p>
                            <p class="font-bold text-gray-800 dark:text-white group-hover:text-blue-600 transition-colors">${p.nombre} ${p.apellido}</p>                        
                        </div>
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 opacity-0 group-hover:opacity-100 transition-all" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293l-4 4a1 1 0 01-1.414 0l-2-2a1 1 0 111.414-1.414L9 10.586l3.293-3.293a1 1 0 111.414 1.414z"/></svg>
                    `;
                    li.onclick = () => {
                        document.getElementById('crear_persona').value = '0';
                        document.getElementById('persona_id').value = p.id;
                        fillPersonaFields(p, true);
                        container.classList.add('hidden');
                        validarStep1();
                    };
                    list.appendChild(li);
                });
            } else {
                list.innerHTML = `<li class="px-6 py-4 text-xs text-gray-400 italic">No hay resultados para "${query}"</li>`;
            }
            container.classList.remove('hidden');
        };

        document.getElementById('btnNewPersona').onclick = function() {
            document.getElementById('crear_persona').value = '1';
            document.getElementById('persona_id').value = '';
            fillPersonaFields({nombre:'', apellido:'', dni:'', fecha_nacimiento:'', sexo:'', telefono:'', email:''}, false);
            document.getElementById('personaFields').scrollIntoView({ behavior: 'smooth' });
            validarStep1();
        };

        function fillPersonaFields(data, readonly) {
            document.getElementById('personaFields').classList.remove('hidden');
            document.getElementById('nueva_nombre').value = data.nombre || '';
            document.getElementById('nueva_apellido').value = data.apellido || '';
            document.getElementById('nueva_dni').value = data.dni || '';
            document.getElementById('nueva_fecha_nacimiento').value = data.fecha_nacimiento || '';
            document.getElementById('nueva_sexo').value = data.sexo || '';
            document.getElementById('nueva_telefono').value = data.telefono || '';
            document.getElementById('nueva_email').value = data.email || '';

            document.querySelectorAll('#personaFields input').forEach(i => {
                i.readOnly = readonly;
                if(readonly) i.classList.add('bg-gray-100', 'dark:bg-gray-800/50', 'text-gray-500');
                else i.classList.remove('bg-gray-100', 'dark:bg-gray-800/50', 'text-gray-500');
            });
            document.getElementById('nueva_sexo').disabled = readonly;
        }

        inputsValida.forEach(i => i.addEventListener('input', validarStep1));
        
        document.getElementById('resetStep1').onclick = () => {
            document.getElementById('personaFields').classList.add('hidden');
            document.getElementById('persona_id').value = '';
            document.getElementById('searchInput').value = '';
            document.getElementById('resultsListContainer').classList.add('hidden');
            validarStep1();
        };

        if(document.getElementById('persona_id').value || document.getElementById('crear_persona').value === '1') {
            validarStep1();
        }
    });

    function goToStep(step) {
        const esNuevo = document.getElementById('crear_persona').value === '1';
        const pId = document.getElementById('persona_id').value;

        if (step === 2 && currentStep === 1) {
            // Validación para pasar al paso 2
            if (!esNuevo && !pId) {
                alert('Por favor selecciona una persona existente o registra una nueva.');
                return;
            }
            if (esNuevo) {
                const n = document.getElementById('nueva_nombre').value.trim();
                const a = document.getElementById('nueva_apellido').value.trim();
                const d = document.getElementById('nueva_dni').value.trim();
                const f = document.getElementById('nueva_fecha_nacimiento').value.trim();
                const s = document.getElementById('nueva_sexo').value.trim();
                
                const dniValido = /^\d+$/.test(d) && d.length > 0;
                
                if (!(n !== '' && a !== '' && dniValido && f !== '' && s !== '')) {
                    alert('Por favor completa todos los campos obligatorios.');
                    return;
                }
            }
            
            // Actualizar resumen de persona
            const nombre = document.getElementById('nueva_nombre').value.trim();
            const apellido = document.getElementById('nueva_apellido').value.trim();
            document.getElementById('resumenPersona').textContent = `${nombre} ${apellido}`;
        }

        document.getElementById(`step-${currentStep}`).classList.add('hidden');
        document.getElementById(`step-${step}`).classList.remove('hidden');

        [1, 2].forEach(n => {
            const circle = document.getElementById(`step-circle-${n}`);
            const label  = document.getElementById(`step-label-${n}`);

            if (n < step) {
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-green-500 border-green-500 text-white';
                document.getElementById(`step-icon-${n}`).innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                label.className = 'text-xs font-medium hidden sm:block transition-colors duration-300 text-green-500';
            } else if (n === step) {
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-blue-600 border-blue-600 text-white';
                document.getElementById(`step-icon-${n}`).innerHTML = n;
                label.className = 'text-xs font-medium hidden sm:block transition-colors duration-300 text-blue-600 dark:text-blue-400';
            } else {
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-400 dark:text-gray-500';
                document.getElementById(`step-icon-${n}`).innerHTML = n;
                label.className = 'text-xs font-medium hidden sm:block transition-colors duration-300 text-gray-400 dark:text-gray-500';
            }
        });

        const progress = ((step - 1) / 1) * 100;
        document.getElementById('progressBar').style.width = `${progress}%`;

        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Si hay errores del servidor mostrar step 1
    @if($errors->any())
        document.getElementById('step-1').classList.remove('hidden');
        document.getElementById('step-2').classList.add('hidden');
    @endif
</script>
@endsection