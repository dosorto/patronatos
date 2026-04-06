{{-- edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid max-w-5xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Organización</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Actualiza la información general de tu organización
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('organization.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nombre --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre *</label>
                    <input type="text" name="name" value="{{ old('name', $org->name) }}"
                        class="w-full px-3 py-2 border rounded-lg @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- RTN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">RTN</label>
                    <input type="text" name="rtn" value="{{ old('rtn', $org->rtn) }}"
                        class="w-full px-3 py-2 border rounded-lg">
                </div>

                {{-- Tipo Organización --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo Organización</label>
                    <select name="id_tipo_organizacion" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">-- Seleccionar --</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo_organizacion }}"
                                {{ old('id_tipo_organizacion', $org->id_tipo_organizacion) == $tipo->id_tipo_organizacion ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo</label>
                    <input type="email" name="email" value="{{ old('email', $org->email) }}"
                        class="w-full px-3 py-2 border rounded-lg">
                </div>

                {{-- Teléfono --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone', $org->phone) }}"
                        class="w-full px-3 py-2 border rounded-lg">
                </div>

                {{-- Fecha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha creación</label>
                    <input type="date" name="fecha_creacion"
                        value="{{ old('fecha_creacion', $org->fecha_creacion) }}"
                        class="w-full px-3 py-2 border rounded-lg">
                </div>

                {{-- País --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">País</label>
                    <select name="pais_id" id="select-pais" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">-- Seleccionar --</option>
                        @foreach($paises as $pais)
                            <option value="{{ $pais->id }}"
                                {{ old('pais_id', $pais_id) == $pais->id ? 'selected' : '' }}>
                                {{ $pais->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Departamento --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                    <select name="id_departamento" id="select-departamento" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">-- Seleccionar --</option>
                        {{-- Solo muestra departamentos del país actual --}}
                        @foreach($departamentos->where('pais_id', $pais_id) as $d)
                            <option value="{{ $d->id }}"
                                {{ old('id_departamento', $org->id_departamento) == $d->id ? 'selected' : '' }}>
                                {{ $d->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Municipio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Municipio</label>
                    <select name="id_municipio" id="select-municipio" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">-- Seleccionar --</option>
                        {{-- Solo muestra municipios del departamento actual --}}
                        @foreach($municipios->where('departamento_id', $org->id_departamento) as $m)
                            <option value="{{ $m->id }}"
                                {{ old('id_municipio', $org->id_municipio) == $m->id ? 'selected' : '' }}>
                                {{ $m->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                    <textarea name="direccion" rows="3"
                        class="w-full px-3 py-2 border rounded-lg">{{ old('direccion', $org->direccion) }}</textarea>
                </div>

                {{-- Logo --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                    @if($org->logo)
                        <img src="{{ asset('storage/'.$org->logo) }}" class="w-24 mb-3 rounded">
                    @endif
                    <input type="file" name="logo" class="w-full px-3 py-2 border rounded-lg">
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('settings.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Actualizar</button>
            </div>
        </form>
    </div>
</div>

{{-- JS para filtrado dinámico País → Departamento → Municipio --}}
@push('scripts')
<script>
    // Datos completos pasados desde el controlador
    const allDepartamentos = @json($departamentos);
    const allMunicipios    = @json($municipios);

    const selectPais  = document.getElementById('select-pais');
    const selectDepto = document.getElementById('select-departamento');
    const selectMuni  = document.getElementById('select-municipio');

    // Valores actuales (para preservar selección al cargar)
    const currentDepto = {{ $org->id_departamento ?? 'null' }};
    const currentMuni  = {{ $org->id_municipio    ?? 'null' }};

    function populateDepartamentos(paisId, selectedId = null) {
        selectDepto.innerHTML = '<option value="">-- Seleccionar --</option>';
        selectMuni.innerHTML  = '<option value="">-- Seleccionar --</option>';

        if (!paisId) return;

        const filtrados = allDepartamentos.filter(d => d.pais_id == paisId);
        filtrados.forEach(d => {
            const opt = new Option(d.nombre, d.id, false, d.id == selectedId);
            selectDepto.add(opt);
        });

        // Si había un depto seleccionado, cargar sus municipios
        if (selectedId) populateMunicipios(selectedId, currentMuni);
    }

    function populateMunicipios(deptoId, selectedId = null) {
        selectMuni.innerHTML = '<option value="">-- Seleccionar --</option>';

        if (!deptoId) return;

        const filtrados = allMunicipios.filter(m => m.departamento_id == deptoId);
        filtrados.forEach(m => {
            const opt = new Option(m.nombre, m.id, false, m.id == selectedId);
            selectMuni.add(opt);
        });
    }

    selectPais.addEventListener('change', () => {
        populateDepartamentos(selectPais.value);
    });

    selectDepto.addEventListener('change', () => {
        populateMunicipios(selectDepto.value);
    });

    // Al cargar la página, inicializar los selects con los valores guardados
    const initialPaisId = selectPais.value;
    if (initialPaisId) {
        populateDepartamentos(initialPaisId, currentDepto);
    }
</script>
@endpush

@endsection