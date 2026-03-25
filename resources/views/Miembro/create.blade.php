@extends(request()->boolean('wizard') ? 'layouts.app' : 'layouts.app')

@section('title', 'Nuevo Miembro')

@section('content')
<div class="container-fluid max-w-5xl mx-auto pb-12 px-4">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">Registro de Miembros</h1>
        <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">Siga los pasos para dar de alta a un nuevo miembro en la organización.</p>
    </div>

    <div class="flex items-center justify-center gap-4 mb-12 max-w-2xl mx-auto">
        <div class="flex items-center gap-3">
            <span id="badge-1" class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-200 dark:shadow-none font-bold transition-all duration-500">1</span>
            <span id="text-1" class="hidden md:block font-bold text-gray-900 dark:text-white uppercase text-xs tracking-widest">Buscar o Crear a la Persona</span>
        </div>
        <div id="line-1" class="h-1 flex-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div id="progress-line" class="h-full bg-blue-600 w-0 transition-all duration-500"></div>
        </div>
        <div class="flex items-center gap-3">
            <span id="badge-2" class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 font-bold transition-all duration-500">2</span>
            <span id="text-2" class="hidden md:block font-bold text-gray-400 dark:text-gray-500 uppercase text-xs tracking-widest">Datos del Miembro</span>
        </div>
    </div>

    {{-- 🔴 BLOQUE DE ERROR MEJORADO --}}
    @if($errors->has('persona_id'))
        <div id="errorDiv" class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-6 py-4 rounded-xl shadow-sm animate-in fade-in slide-in-from-top-2 duration-300">
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

    <form action="{{ route('miembro.store') }}" method="POST" id="memberWizard">
        @csrf
        @if($isWizard)
            <input type="hidden" name="wizard" value="1">
        @endif
        <input type="hidden" name="persona_id" id="persona_id" value="{{ old('persona_id') }}">
        <input type="hidden" name="crear_persona" id="crear_persona" value="{{ old('crear_persona', '0') }}">

        <div id="step-1" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 rounded-2xl p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">Buscar Persona Existente</label>
                        <div class="relative group">
                            <input type="text" id="searchInput" 
                                class="w-full pl-4 pr-14 py-4 bg-gray-50 dark:bg-gray-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all shadow-inner text-lg" 
                                placeholder="Ingrese nombre o DNI para buscar...">
                            <button type="button" id="btnSearch" class="absolute right-2 top-2 bottom-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all shadow-md active:scale-95">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                        <div id="resultsListContainer" class="hidden mt-4 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gray-50 dark:bg-gray-800 px-4 py-2 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Coincidencias</div>
                            <ul id="resultsList" class="divide-y divide-gray-100 dark:divide-gray-800 max-h-56 overflow-y-auto"></ul>
                        </div>
                    </div>
                </div>

                <div id="personaFields" class="{{ old('crear_persona') == '1' ? '' : 'hidden' }} mt-10 pt-10 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-8">
                        <h3 id="formTitle" class="text-blue-600 dark:text-blue-400 font-black uppercase text-sm tracking-[0.15em]">Datos de la Persona</h3>
                        <button type="button" id="resetStep1" class="text-sm font-bold text-red-500 hover:bg-red-50 px-3 py-1 rounded-full transition-colors">Limpiar selección</button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-500 uppercase ml-1">Nombres *</label>
                            <input type="text" name="nueva_nombre" id="nueva_nombre" value="{{ old('nueva_nombre') }}" class="p-valida w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 transition-all shadow-sm">
                            <x-input-error :messages="$errors->get('nueva_nombre')" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-500 uppercase ml-1">Apellidos *</label>
                            <input type="text" name="nueva_apellido" id="nueva_apellido" value="{{ old('nueva_apellido') }}" class="p-valida w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 transition-all shadow-sm">
                            <x-input-error :messages="$errors->get('nueva_apellido')" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-500 uppercase ml-1">DNI *</label>
                            <input type="text" name="nueva_dni" id="nueva_dni" value="{{ old('nueva_dni') }}" placeholder="" class="p-valida dni-input w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 transition-all shadow-sm">
                            <small class="text-gray-400 text-xs">Solo se aceptan números</small>
                            <x-input-error :messages="$errors->get('nueva_dni')" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-500 uppercase ml-1">Fecha Nacimiento *</label>
                            <input type="date" name="nueva_fecha_nacimiento" id="nueva_fecha_nacimiento" value="{{ old('nueva_fecha_nacimiento') }}" class="p-valida w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 transition-all shadow-sm">
                            <x-input-error :messages="$errors->get('nueva_fecha_nacimiento')" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-500 uppercase ml-1">Género *</label>
                            <select name="nueva_sexo" id="nueva_sexo" class="p-valida w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 transition-all shadow-sm">
                                <option value="">Seleccione...</option>
                                <option value="M" {{ old('nueva_sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('nueva_sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            <x-input-error :messages="$errors->get('nueva_sexo')" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-500 uppercase ml-1">Teléfono</label>
                            <input type="text" name="nueva_telefono" id="nueva_telefono" value="{{ old('nueva_telefono') }}" placeholder="" class="phone-input w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 transition-all shadow-sm">
                            <small class="text-gray-400 text-xs">Solo se aceptan números</small>
                        </div>
                        <div class="lg:col-span-3 space-y-2">
                            <label class="text-sm font-bold text-gray-500 uppercase ml-1">Email</label>
                            <input type="email" name="nueva_email" id="nueva_email" value="{{ old('nueva_email') }}" placeholder="ejemplo@correo.com" class="email-input w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 transition-all shadow-sm">
                            <small class="text-gray-400 text-xs">Formato válido de correo</small>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-end mt-10 pt-10 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" id="btnNewPersona" class="group flex items-center justify-center gap-3 w-fit px-8 py-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-gray-500 dark:text-gray-400 hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50/50 transition-all duration-300">
                        <div class="p-1 bg-gray-100 dark:bg-gray-700 rounded-full group-hover:bg-blue-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="font-bold uppercase text-xs tracking-widest">Registrar Nueva Persona</span>
                    </button>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="button" id="btnToStep2" disabled class="group flex items-center gap-3 px-10 py-4 bg-gray-400 text-white rounded-2xl font-bold uppercase text-xs tracking-widest cursor-not-allowed transition-all shadow-lg active:scale-95">
                    Siguiente Paso
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </div>
        </div>

        <div id="step-2" class="hidden space-y-8 animate-in fade-in slide-in-from-right-4 duration-500">
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-xl shadow-blue-200/50 dark:shadow-none">
                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="p-4 bg-white/20 rounded-2xl backdrop-blur-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-blue-100 text-[10px] font-black uppercase tracking-[0.2em]">Registrando a:</p>
                            <p id="resumenPersona" class="text-2xl font-bold italic tracking-tight"></p>
                        </div>
                    </div>
                    <button type="button" onclick="changeStep(1)" class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/30 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">Cambiar</button>
                </div>
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 rounded-2xl p-8">
                <h3 class="text-gray-800 dark:text-white font-black uppercase text-sm tracking-[0.15em] mb-8">Información del Miembro</h3>
                <div class="grid grid-cols-1 gap-8">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1 tracking-wider">Dirección *</label>
                        <input type="text" name="direccion" value="{{ old('direccion') }}" required class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 transition-all shadow-sm" placeholder="Calle, número y referencias">
                        <x-input-error :messages="$errors->get('direccion')" />
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <button type="button" onclick="changeStep(1)" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">
                    Volver al inicio</button>
                <button type="submit" class="group flex items-center gap-3 px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold uppercase text-xs tracking-widest transition-all shadow-lg active:scale-95">
                    Guardar Miembro
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
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
    const btnNext = document.getElementById('btnToStep2');

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

        btnNext.disabled = !isValid;
        if(isValid) {
            btnNext.classList.replace('bg-gray-400', 'bg-blue-600');
            btnNext.classList.remove('cursor-not-allowed');
        } else {
            btnNext.classList.replace('bg-blue-600', 'bg-gray-400');
            btnNext.classList.add('cursor-not-allowed');
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
                        <p class="text-[16px] text-gray-400 uppercase font-bold tracking-tighter">DNI: ${p.dni}</p>
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

    window.changeStep = function(n) {
        if(n === 2) {
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
            document.getElementById('badge-2').classList.replace('bg-gray-200', 'bg-blue-600');
            document.getElementById('badge-2').classList.replace('text-gray-500', 'text-white');
            document.getElementById('progress-line').style.width = '100%';
            document.getElementById('resumenPersona').textContent = `${document.getElementById('nueva_nombre').value} ${document.getElementById('nueva_apellido').value}`;
        } else {
            document.getElementById('step-2').classList.add('hidden');
            document.getElementById('step-1').classList.remove('hidden');
            document.getElementById('badge-2').classList.replace('bg-blue-600', 'bg-gray-200');
            document.getElementById('badge-2').classList.replace('text-white', 'text-gray-500');
            document.getElementById('progress-line').style.width = '0%';
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    btnNext.onclick = () => changeStep(2);
    inputsValida.forEach(i => i.addEventListener('input', validarStep1));
    document.getElementById('resetStep1').onclick = () => {
        document.getElementById('personaFields').classList.add('hidden');
        document.getElementById('persona_id').value = '';
        validarStep1();
    };

    if(document.getElementById('persona_id').value || document.getElementById('crear_persona').value === '1') {
        validarStep1();
    }
});
</script>

<style>
    .animate-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endpush