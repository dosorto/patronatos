@extends('layouts.app')

@section('title', 'Nuevo Proyecto')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Proyecto</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">Registra un nuevo proyecto en el sistema</p>
    </div>

    {{-- Errores de validación del servidor --}}
    @if($errors->any())
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

    <form action="{{ route('proyecto.store') }}" method="POST" id="proyectoForm">
        @csrf

        {{-- Input hidden para el responsable --}}
        <input type="hidden" name="directiva_id" value="{{ $directivas->first()?->id }}">

        {{-- Steps Indicator --}}
        <div class="mb-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 right-0 top-4 h-0.5 bg-gray-200 dark:bg-gray-700 z-0"></div>
                <div class="absolute left-0 top-4 h-0.5 bg-blue-600 z-0 transition-all duration-500" id="progressBar" style="width: 0%"></div>

                @foreach([1 => 'Información General', 2 => 'Beneficiarios'] as $num => $label)
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

            {{-- Step 1: Información General --}}
            <div id="step-1">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Información General
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nombre del Proyecto --}}
                    <div class="md:col-span-2">
                        <label for="nombre_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre del Proyecto *</label>
                        <input type="text" name="nombre_proyecto" id="nombre_proyecto" value="{{ old('nombre_proyecto') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nombre_proyecto') border-red-500 @enderror">
                        @error('nombre_proyecto')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tipo de Proyecto --}}
                    <div>
                        <label for="tipo_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Proyecto</label>
                        <input type="text" name="tipo_proyecto" id="tipo_proyecto" value="{{ old('tipo_proyecto') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('tipo_proyecto') border-red-500 @enderror">
                        @error('tipo_proyecto')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Número de Acta --}}
                    <div>
                        <label for="numero_acta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Número de Acta</label>
                        <input type="text" name="numero_acta" id="numero_acta" value="{{ old('numero_acta') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('numero_acta') border-red-500 @enderror">
                        @error('numero_acta')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha Aprobación Asamblea --}}
                    <div>
                        <label for="fecha_aprobacion_asamblea" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Aprobación Asamblea</label>
                        <input type="date" name="fecha_aprobacion_asamblea" id="fecha_aprobacion_asamblea" value="{{ old('fecha_aprobacion_asamblea') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_aprobacion_asamblea') border-red-500 @enderror">
                        @error('fecha_aprobacion_asamblea')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha Inicio --}}
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_inicio') border-red-500 @enderror">
                        @error('fecha_inicio')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha Fin --}}
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_fin') border-red-500 @enderror">
                        @error('fecha_fin')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Responsable (solo lectura) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Responsable del Proyecto</label>
                        <div class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            {{ $directivas->first()?->miembro->persona->nombre_completo ?? 'N/A' }}
                            @if($directivas->first()?->cargo)
                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $directivas->first()->cargo }})</span>
                            @endif
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Justificación --}}
                    <div class="md:col-span-2">
                        <label for="justificacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Justificación</label>
                        <textarea name="justificacion" id="justificacion" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('justificacion') border-red-500 @enderror">{{ old('justificacion') }}</textarea>
                        @error('justificacion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="goToStep(2)"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                        Siguiente
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Step 2: Beneficiarios --}}
            <div id="step-2" class="hidden">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Beneficiarios
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Descripción Beneficiarios --}}
                    <div class="md:col-span-2">
                        <label for="descripcion_beneficiarios" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción de Beneficiarios</label>
                        <textarea name="descripcion_beneficiarios" id="descripcion_beneficiarios" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descripcion_beneficiarios') border-red-500 @enderror">{{ old('descripcion_beneficiarios') }}</textarea>
                        @error('descripcion_beneficiarios')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hombres --}}
                    <div>
                        <label for="benef_hombres" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hombres</label>
                        <input type="number" name="benef_hombres" id="benef_hombres" value="{{ old('benef_hombres', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_hombres') border-red-500 @enderror">
                        @error('benef_hombres')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mujeres --}}
                    <div>
                        <label for="benef_mujeres" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mujeres</label>
                        <input type="number" name="benef_mujeres" id="benef_mujeres" value="{{ old('benef_mujeres', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_mujeres') border-red-500 @enderror">
                        @error('benef_mujeres')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Niños --}}
                    <div>
                        <label for="benef_ninos" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Niños</label>
                        <input type="number" name="benef_ninos" id="benef_ninos" value="{{ old('benef_ninos', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_ninos') border-red-500 @enderror">
                        @error('benef_ninos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Familias --}}
                    <div>
                        <label for="benef_familias" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Familias</label>
                        <input type="number" name="benef_familias" id="benef_familias" value="{{ old('benef_familias', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('benef_familias') border-red-500 @enderror">
                        @error('benef_familias')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="goToStep(1)"
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Anterior
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Guardar Proyecto
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    let currentStep = 1;

    // Si hay errores del servidor, volver al step 1
    @if($errors->has('nombre_proyecto'))
        currentStep = 1;
    @endif

    function goToStep(step) {
        if (step === 2 && currentStep === 1) {
            const nombre = document.getElementById('nombre_proyecto').value.trim();
            if (!nombre) {
                const input = document.getElementById('nombre_proyecto');
                input.classList.add('border-red-500');
                input.focus();

                let msg = document.getElementById('nombre-error-msg');
                if (!msg) {
                    msg = document.createElement('p');
                    msg.id = 'nombre-error-msg';
                    msg.className = 'mt-1 text-sm text-red-600 dark:text-red-400';
                    msg.textContent = 'El nombre del proyecto es obligatorio.';
                    input.parentNode.appendChild(msg);
                }
                return;
            }
            document.getElementById('nombre_proyecto').classList.remove('border-red-500');
            const msg = document.getElementById('nombre-error-msg');
            if (msg) msg.remove();
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
    }

    // Si hay errores del servidor mostrar step 1
    @if($errors->any())
        document.getElementById('step-1').classList.remove('hidden');
        document.getElementById('step-2').classList.add('hidden');
    @endif
</script>
@endsection