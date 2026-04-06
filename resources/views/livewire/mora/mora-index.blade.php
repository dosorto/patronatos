<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Moras</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Control manual de deudas y morosidad de los miembros</p>
        </div>
 
        <button 
            wire:click="openCreateModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Registrar Mora
        </button>
    </div>
 
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif
 
    {{-- Table Container --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white" placeholder="Buscar por miembro o periodo...">
            </div>
            
            <select wire:model.live="perPage" class="border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-800 dark:text-white">
                <option value="10">10 registros</option>
                <option value="25">25 registros</option>
                <option value="50">50 registros</option>
            </select>
        </div>
 
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 dark:bg-gray-900 text-gray-500 dark:text-gray-400 uppercase font-medium">
                    <tr>
                        <th class="px-6 py-3">Miembro</th>
                        <th class="px-6 py-3">Periodo</th>
                        <th class="px-6 py-3">Monto Original</th>
                        <th class="px-6 py-3">Pendiente</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($moras as $mora)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $mora->miembro->persona->nombre_completo ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $mora->periodo }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                ${{ number_format($mora->monto_original, 2) }}
                            </td>
                            <td class="px-6 py-4 font-bold text-red-600 dark:text-red-400">
                                ${{ number_format($mora->monto_pendiente, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $mora->estado === 'Pendiente' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                    {{ $mora->estado === 'Abonado' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                    {{ $mora->estado === 'Cancelado' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                ">
                                    {{ $mora->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="confirmMoraDeletion({{ $mora->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                No se encontraron moras registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
 
        @if($moras->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $moras->links() }}
            </div>
        @endif
    </div>
 
    {{-- Create Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-[100] p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-200 dark:border-gray-700">
                <form wire:submit.prevent="save">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Registrar Nueva Mora</h3>
                        <button type="button" wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miembro</label>
                            <select wire:model="miembro_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Seleccione un miembro...</option>
                                @foreach($miembros as $miembro)
                                    <option value="{{ $miembro->id }}">{{ $miembro->persona->nombre_completo ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                            @error('miembro_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
 
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Periodo (Mes/Año)</label>
                            <input type="text" wire:model="periodo" placeholder="Ej: Enero 2024" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('periodo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
 
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto Original</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" wire:model="monto_original" class="block w-full rounded-md border-gray-300 pl-7 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500" placeholder="0.00">
                            </div>
                            @error('monto_original') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
 
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado Inicial</label>
                            <select wire:model="estado" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="Pendiente">Pendiente</option>
                                <option value="Abonado">Abonado</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                            @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300">Cancelar</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm">Guardar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
 
    {{-- Delete Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-[100] p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">¿Eliminar esta mora?</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Esta acción eliminará el registro de deuda permanentemente.</p>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300">Cancelar</button>
                    <button wire:click="delete" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm">Eliminar</button>
                </div>
            </div>
        </div>
    @endif
</div>
