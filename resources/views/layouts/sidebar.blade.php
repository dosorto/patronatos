<aside id="sidebar"
       class="fixed top-16 left-0 z-40 w-64 h-full
              bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700
              transition-transform -translate-x-full sm:translate-x-0
              shadow-xl dark:shadow-gray-900">

    <div class="h-full px-3 py-4 overflow-y-auto bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">
        <!-- Logo/Header -->
        <div class="flex items-center mb-6 px-2">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
            <span class="text-xl font-bold text-gray-900 dark:text-white">SISGAP</span>
        </div>

        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            

            @can('users.view')
                <li>
                    <a href="{{ route('users.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                        <span class="ml-3">Usuarios</span>
                    </a>
                </li>
            @endcan

            <li>
                <a href="{{ route('roles.index') }}"
                   class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Roles</span>
                </a>
            </li>

            @can('audit.view')
                <li>
                    <a href="{{ route('pais.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-3">Países</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('departamento.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="ml-3">Departamentos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('municipio.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="ml-3">Municipios</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('audit.index') }}"

                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="ml-3">Auditoría</span>
                    </a>
                </li>
            @endcan
            @can('directiva.view')
                <li>
                    <a href="{{ route('directiva.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="ml-3">Directiva</span>
                    </a>
                </li>
            @endcan
        </ul>

        <!-- Footer -->
        <div class="absolute bottom-4 left-3 right-3">
            <div class="p-3 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-600 dark:text-gray-400 text-center font-bold">GIC SOLUTIONS</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-500 text-center mt-1">Gestión Integral Comunitaria</p>
            </div>
        </div>
    </div>
</aside>
