<div id="sidebar-component">
    {{-- Sidebar --}}
    <aside id="sidebar"
           class="block relative h-screen shrink-0
                  bg-gray-50 dark:bg-gray-900 border-r-2 border-gray-300 dark:border-gray-700
                  transition-all duration-300 ease-in-out shadow-lg dark:shadow-gray-900
                  {{ $isCollapsed ? 'w-16' : 'w-64' }}"
           data-collapsed="{{ $isCollapsed ? 'true' : 'false' }}">


        <div class="h-full px-3 py-4 overflow-y-auto bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex flex-col">
            {{-- Logo/Header --}}
            <div class="flex items-center mb-6 px-2 {{ $isCollapsed ? 'justify-center' : '' }}">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 {{ $isCollapsed ? '' : 'mr-3' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
                @if(!$isCollapsed)
                    <span class="text-xl font-bold text-gray-900 dark:text-white">DAO Admin</span>
                @endif
            </div>



            {{-- Contenido principal --}}
            <div class="flex-1">
                {{-- Sección Principal --}}
                @if(!$isCollapsed)
                    <div class="mb-4">
                        <h3 class="px-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Principal
                        </h3>
                    </div>
                @endif

                <ul class="space-y-2 font-medium mb-8">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                           title="{{ $isCollapsed ? 'Dashboard' : '' }}">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                            </svg>
                            @if(!$isCollapsed)
                                <span class="ml-3">Dashboard</span>
                            @endif
                        </a>
                    </li>

                    @can('personas.view')
                        <li>
                            <a href="{{ route('personas.index') }}"
                                class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                                title="{{ $isCollapsed ? 'Personas' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Personas</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('pais.view')
                        <li>
                            <a href="{{ route('pais.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'País' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">País</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('departamento.view')
                        <li>
                            <a href="{{ route('departamento.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Departamento' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Departamento</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('cooperantes.view')
                        <li>
                            <a href="{{ route('cooperantes.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Cooperante' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Cooperante</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('municipio.view')
                        <li>
                            <a href="{{ route('municipio.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Municipio' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Municipio</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('miembro.view')
                        <li>
                            <a href="{{ route('miembro.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Miembros' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 00-3-3.87M9 20H4v-1a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0zM22 12v1M2 12v1" />
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Miembros</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('empleado.view')
                        <li>
                            <a href="{{ route('empleado.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Empleados' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Empleados</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('tipoactivo.view')
                        <li>
                            <a href="{{ route('tipoactivo.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Tipo Activo' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Tipo Activo</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('activo.view')
                        <li>
                            <a href="{{ route('activo.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Activo' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Activo</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('servicios.view')
                    <li>
                        <a href="{{ route('servicios.index') }}"
                        class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                        title="{{ $isCollapsed ? 'Servicios' : '' }}">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            @if(!$isCollapsed)
                                <span class="ml-3">Servicios</span>
                            @endif
                        </a>
                    </li>
                @endcan

                    @can('directiva.view')
                        <li>
                            <a href="{{ route('directiva.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Directiva' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Directiva</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('proyecto.view')
                        <li>
                            <a href="{{ route('proyecto.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Proyectos' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Proyectos</span>
                                @endif
                            </a>
                        </li>
                    @endcan


                    @can('users.view')
                        <li>
                            <a href="{{ route('users.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Usuarios' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Usuarios</span>
                                @endif
                            </a>
                        </li>
                    @endcan
                </ul>

                {{-- Sección Configuración --}}
                @if(!$isCollapsed)
                    <div class="mb-4">
                        <h3 class="px-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Configuración
                        </h3>
                    </div>
                @endif

                <ul class="space-y-2 font-medium">
                    @can('roles.view')
                        <li>
                            <a href="{{ route('settings.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Configuración' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Configuración</span>
                                @endif
                            </a>
                        </li>
                    @endcan


                </ul>
            </div>

            {{-- Footer --}}
            <div class="mt-auto">
                @if(!$isCollapsed)
                    <div class="px-2">
                        <div class="p-3 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-600 dark:text-gray-400 text-center">Sistema de Gestión DAO</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </aside>


</div>
