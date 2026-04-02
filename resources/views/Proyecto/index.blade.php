@extends('layouts.app')

@section('title', 'Proyectos')

@section('content')
    <livewire:proyecto.proyecto-index />

    @if(isset($hasCooperantes) && !$hasCooperantes)
        <div id="no-cooperantes-modal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 transform transition-all">
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-yellow-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">¡Aviso Importante!</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">
                        Se ha detectado que su organización no cuenta con ningún <strong>Cooperante</strong> registrado. Le recomendamos agregar al menos uno antes de continuar, ya que lo necesitará si tiene presupuestos con donaciones externas.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-3">
                        <a href="{{ route('cooperantes.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm inline-flex items-center justify-center px-5 py-2.5 text-center">
                            Agregar Cooperante
                        </a>
                        <button onclick="document.getElementById('no-cooperantes-modal').remove()" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 w-full sm:w-auto">
                            Continuar sin Cooperante
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection