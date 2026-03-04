@extends('layouts.app')

@section('title', 'Nuevo Miembro Directiva')

@section('content')
<div class="container-fluid max-w-5xl mx-auto pb-12 px-4">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">Asignación de Directiva</h1>
        <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">Busque una persona para asignarle un cargo en la directiva de la organización.</p>
    </div>

    <div class="flex items-center justify-center gap-4 mb-12 max-w-2xl mx-auto">
        <div class="flex items-center gap-3">
            <span id="badge-1" class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-200 dark:shadow-none font-bold transition-all duration-500">1</span>
            <span id="text-1" class="hidden md:block font-bold text-gray-900 dark:text-white uppercase text-xs tracking-widest">Buscar Persona</span>
        </div>
        <div id="line-1" class="h-1 flex-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div id="progress-line" class="h-full bg-blue-600 w-0 transition-all duration-500"></div>
        </div>
        <div class="flex items-center gap-3">
            <span id="badge-2" class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 font-bold transition-all duration-500">2</span>
            <span id="text-2" class="hidden md:block font-bold text-gray-400 dark:text-gray-500 uppercase text-xs tracking-widest">Asignar Cargo</span>
        </div>
    </div>

    <form action="{{ route('directiva.store') }}" method="POST" id="directivaWizard">
        @csrf
        <input type="hidden" name="persona_id" id="persona_id" value="{{ old('persona_id') }}">

        {{-- Paso 1: Búsqueda de Persona --}}
        <div id="step-1" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 rounded-2xl p-8">
                <div class="grid grid-cols-1 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">Buscar Persona</label>
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

                <div id="resumenSeleccion" class="hidden mt-10 pt-10 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-blue-600 dark:text-blue-400 font-black uppercase text-sm tracking-[0.15em]">Persona Seleccionada</h3>
                        <button type="button" id="resetSelection" class="text-sm font-bold text-red-500 hover:bg-red-50 px-3 py-1 rounded-full transition-colors">Cambiar persona</button>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <p id="selectedName" class="text-xl font-bold text-gray-900 dark:text-white"></p>
                                <p id="selectedDni" class="text-sm text-gray-500 dark:text-gray-400"></p>
                                <div id="statusBadge" class="mt-2 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md w-fit"></div>
                            </div>
                        </div>
                        <div id="cargoExistenteAlert" class="hidden mt-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    <strong>No se puede continuar:</strong> Esta persona ya posee un cargo en la directiva activa.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="button" id="btnToStep2" disabled class="group flex items-center gap-3 px-10 py-4 bg-gray-400 text-white rounded-2xl font-bold uppercase text-xs tracking-widest cursor-not-allowed transition-all shadow-lg active:scale-95">
                    Siguiente Paso
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </div>
        </div>

        {{-- Paso 2: Asignación de Cargo --}}
        <div id="step-2" class="hidden space-y-8 animate-in fade-in slide-in-from-right-4 duration-500">
            <div class="bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 rounded-2xl p-8">
                <h3 class="text-gray-800 dark:text-white font-black uppercase text-sm tracking-[0.15em] mb-8">Información del Cargo</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="cargo" class="text-xs font-bold text-gray-500 uppercase ml-1 tracking-wider">Seleccione el Cargo *</label>
                        <select name="cargo" id="cargo" required
                                class="w-full mt-2 px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 focus:ring-blue-500 transition-all shadow-sm @error('cargo') border-red-500 @enderror">
                            <option value="">Seleccione un cargo...</option>
                            <option value="Presidente(a)" {{ old('cargo') == 'Presidente(a)' ? 'selected' : '' }}>Presidente(a)</option>
                            <option value="Vicepresidente(a)" {{ old('cargo') == 'Vicepresidente(a)' ? 'selected' : '' }}>Vicepresidente(a)</option>
                            <option value="Secretario(a)" {{ old('cargo') == 'Secretario(a)' ? 'selected' : '' }}>Secretario(a)</option>
                            <option value="Tesorero(a)" {{ old('cargo') == 'Tesorero(a)' ? 'selected' : '' }}>Tesorero(a)</option>
                            <option value="Vocal 1" {{ old('cargo') == 'Vocal 1' ? 'selected' : '' }}>Vocal 1</option>
                            <option value="Vocal 2" {{ old('cargo') == 'Vocal 2' ? 'selected' : '' }}>Vocal 2</option>
                            <option value="Vocal 3" {{ old('cargo') == 'Vocal 3' ? 'selected' : '' }}>Vocal 3</option>
                        </select>
                        @error('cargo')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="miembroNotice" class="hidden p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-amber-700 dark:text-amber-300">
                                <strong>Nota:</strong> Esta persona no está registrada como miembro. Al guardar, se le registrará automáticamente como miembro activo para poder asignarle el cargo.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <button type="button" onclick="changeStep(1)" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">Volver a buscar</button>
                <button type="submit" class="group flex items-center gap-3 px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold uppercase text-xs tracking-widest transition-all shadow-lg active:scale-95">
                    Asignar Cargo
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const personas = @json($personas);
    const miembrosPersonaIds = @json($miembrosPersonaIds);
    const personasConCargoIds = @json($personasConCargoIds);
    const btnNext = document.getElementById('btnToStep2');

    function validarStep1() {
        const pId = document.getElementById('persona_id').value;
        const hasCargo = personasConCargoIds.includes(parseInt(pId));
        const isValid = pId !== '' && !hasCargo;

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
                        <p class="text-[14px] text-gray-400 uppercase font-bold tracking-tighter">DNI: ${p.dni}</p>
                        <p class="font-bold text-gray-800 dark:text-white group-hover:text-blue-600 transition-colors">${p.nombre} ${p.apellido}</p>                        
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 opacity-0 group-hover:opacity-100 transition-all" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293l-4 4a1 1 0 01-1.414 0l-2-2a1 1 0 111.414-1.414L9 10.586l3.293-3.293a1 1 0 111.414 1.414z"/></svg>
                `;
                li.onclick = () => {
                    document.getElementById('persona_id').value = p.id;
                    showResumen(p);
                    container.classList.add('hidden');
                    validarStep1();
                };
                list.appendChild(li);
            });
        } else {
            list.innerHTML = `<li class="px-6 py-4 text-sm text-gray-400 italic">No se encontró ninguna persona con "${query}"</li>`;
        }
        container.classList.remove('hidden');
    };

    function showResumen(p) {
        document.getElementById('resumenSeleccion').classList.remove('hidden');
        document.getElementById('selectedName').textContent = `${p.nombre} ${p.apellido}`;
        document.getElementById('selectedDni').textContent = `DNI: ${p.dni}`;
        
        const badge = document.getElementById('statusBadge');
        const notice = document.getElementById('miembroNotice');
        const alertCargo = document.getElementById('cargoExistenteAlert');
        
        const isMiembro = miembrosPersonaIds.includes(parseInt(p.id));
        const hasCargo = personasConCargoIds.includes(parseInt(p.id));

        alertCargo.classList.add('hidden');

        if (hasCargo) {
            badge.textContent = 'Ya tiene un cargo';
            badge.className = 'mt-2 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md w-fit bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
            alertCargo.classList.remove('hidden');
            notice.classList.add('hidden');
        } else if(isMiembro) {
            badge.textContent = 'Miembro Existente';
            badge.className = 'mt-2 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md w-fit bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
            notice.classList.add('hidden');
        } else {
            badge.textContent = 'Pendiente de Registro';
            badge.className = 'mt-2 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md w-fit bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';
            notice.classList.remove('hidden');
        }
    }

    window.changeStep = function(n) {
        if(n === 2) {
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
            document.getElementById('badge-2').classList.replace('bg-gray-200', 'bg-blue-600');
            document.getElementById('badge-2').classList.replace('text-gray-500', 'text-white');
            document.getElementById('progress-line').style.width = '100%';
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
    document.getElementById('resetSelection').onclick = () => {
        document.getElementById('resumenSeleccion').classList.add('hidden');
        document.getElementById('persona_id').value = '';
        document.getElementById('searchInput').value = '';
        validarStep1();
    };

    if(document.getElementById('persona_id').value) {
        const p = personas.find(pers => pers.id == document.getElementById('persona_id').value);
        if(p) showResumen(p);
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
</style>
@endpush
