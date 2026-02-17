<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Departamentos</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Administra el registro de departamentos y sus datos básicos</p>
        </div>

        <div class="flex flex-wrap gap-2">
            {{-- Botón de Exportar --}}
            <button wire:click="export"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 shadow-sm transition-colors duration-200">
                <span>Exportar a Excel</span>
            </button>

            @can('departamento.create')
                <a href="{{ route('departamento.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
                    Nuevo Departamento
                </a>
            @endcan
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Search and Filters --}}
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-2">
            <input wire:model.live="search" type="text" placeholder="Buscar por nombre..." 
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">País</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($departamentos as $departamento)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $departamento->nombre }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $departamento->pais->nombre ?? 'N/A' }}</span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $departamento->id }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                @can('departamento.view')
                                    <a href="{{ route('departamento.show', $departamento) }}" class="text-gray-600 hover:text-gray-800" title="Ver">👁️</a>
                                @endcan
                                @can('departamento.edit')
                                    <a href="{{ route('departamento.edit', $departamento) }}" class="text-blue-600 hover:text-blue-800" title="Editar">✏️</a>
                                @endcan
                                @can('departamento.delete')
                                    <button wire:click="confirmDepartamentoDeletion({{ $departamento->id }}, '{{ $departamento->nombre }}')" 
                                        class="text-red-600 hover:text-red-800" title="Eliminar">🗑️</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                No hay departamentos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($departamentos->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                {{ $departamentos->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Confirmar Eliminación</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    ¿Estás seguro de que deseas eliminar el departamento <strong>{{ $departamentoNameBeingDeleted }}</strong>? Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)" 
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button wire:click="delete" 
                        class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
