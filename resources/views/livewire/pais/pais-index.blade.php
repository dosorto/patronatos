<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Países</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Administra el registro de países y sus datos básicos</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @can('pais.export')
                <a href="{{ route('pais.export') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Exportar a Excel
                </a>

            @endcan
            @can('pais.create')
                <a href="{{ route('pais.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
                    Nuevo País
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

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left w-40">ID</th>
                    <th class="px-6 py-3 text-left w-40">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($paises as $pais)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer group">
                        <td class="px-6 py-4">
                            {{ $pais->nombre }}
                        </td>
                        <td class="px-6 py-4">{{ $pais->id }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            @can('pais.view')
                                <a href="{{ route('pais.show', $pais) }}" class="text-gray-600 hover:text-gray-800">👁️</a>
                            @endcan
                            @can('pais.edit')
                                <a href="{{ route('pais.edit', $pais) }}" class="text-blue-600 hover:text-blue-800">✏️</a>
                            @endcan
                            @can('pais.delete')
                                <form action="{{ route('pais.destroy', $pais) }}" method="POST" 
                                    onsubmit="return confirm('¿Seguro que quieres eliminar {{ $pais->nombre }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">🗑️</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                            No hay países registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $paises->links() }}
    </div>
</div>
