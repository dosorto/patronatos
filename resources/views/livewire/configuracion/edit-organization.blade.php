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
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- RTN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">RTN</label>
                    <input type="text" name="rtn" value="{{ old('rtn', $org->rtn) }}"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                
                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Correo</label>
                    <input type="email" name="email" value="{{ old('email', $org->email) }}"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                {{-- Teléfono --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone', $org->phone) }}"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                {{-- Fecha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha creación</label>
                    <input type="date" name="fecha_creacion"
                        value="{{ old('fecha_creacion', $org->fecha_creacion) }}"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meses de atraso para Mora</label>
                        <input type="number" name="meses_mora" value="{{ old('meses_mora', $org->meses_mora ?? 1) }}" min="0" max="12"
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Coloque 0 si entra en mora en el mismo mes.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Día límite de pago del mes</label>
                        <input type="number" name="dias_pago" value="{{ old('dias_pago', $org->dias_pago ?? 30) }}" min="1" max="31"
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Ej. 5 (el socio entra en mora después del día 5).</p>
                    </div>
                </div>

                {{-- Logo --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo</label>
                    @if($org->logo)
                        <div class="mb-3 flex items-center gap-4">
                            <img src="{{ asset('storage/'.$org->logo) }}" class="w-24 rounded">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="ml-2 text-sm text-red-600 font-medium">Eliminar logo actual</span>
                            </label>
                        </div>
                    @endif
                    <input type="file" name="logo" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('settings.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 dark:text-gray-200 rounded-lg">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Actualizar</button>
            </div>
        </form>
    </div>
</div>

@endsection