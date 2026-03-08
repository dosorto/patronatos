<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">

    @if(!request()->has('wizard'))
    {{-- Main Layout Container --}}
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        @livewire('layouts.sidebar-toggle')

        {{-- Content Area --}}
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden bg-gray-100 dark:bg-gray-900">
            {{-- Navbar --}}
            @include('layouts.navbar')

            {{-- Main Content --}}
            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @else
        {{-- Sin sidebar ni navbar para el wizard --}}
        @yield('content')
    @endif

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.3.0/dist/flowbite.min.js"></script>

    @if(!request()->has('wizard'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const arrow = document.getElementById('sidebar-arrow');

            function updateArrow() {
                const isCollapsed = sidebar.getAttribute('data-collapsed') === 'true';
                if (isCollapsed) {
                    arrow.classList.remove('rotate-180');
                } else {
                    arrow.classList.add('rotate-180');
                }
            }

            updateArrow();

            const observer = new MutationObserver(updateArrow);
            observer.observe(sidebar, {
                attributes: true,
                attributeFilter: ['data-collapsed']
            });
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>