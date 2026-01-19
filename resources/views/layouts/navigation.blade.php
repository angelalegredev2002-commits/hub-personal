<nav x-data="{ open: false }" class="bg-gray-950 shadow-xl fixed top-0 w-full z-50">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- Lado Izquierdo: Botón Hamburguesa (para el menú off-canvas) y Logo --}}
            <div class="flex items-center">
                
                {{-- BOTÓN HAMBURGER para abrir el Off-Canvas Menu (sm:block para visibilidad permanente) --}}
                <button @click="isMenuOpen = true" class="text-rose-600 hover:text-rose-500 focus:outline-none me-4 sm:block" title="Abrir Menú Lateral">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                {{-- Enlaces de navegación (Desktop) --}}
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-gray-300 active:border-gray-500 tracking-wider font-semibold border-transparent">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    {{-- AGREGUE AQUÍ OTROS ENLACES DE ESCRITORIO SI ES NECESARIO --}}
                </div>
            </div>

            {{-- Lado Derecho: Dropdown de Usuario (Desktop) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center p-2 text-sm leading-4 font-semibold rounded-lg text-white bg-gray-800 hover:bg-gray-700 transition ease-in-out duration-150">
                            
                            {{-- Iniciales del usuario --}}
                            <div class="h-8 w-8 rounded-full bg-white flex items-center justify-center text-gray-900 text-xs font-bold mr-2">
                                {{ substr(Auth::user()->nombre ?? 'U', 0, 1) }}
                            </div>

                            <div>{{ Auth::user()->nombre ?? 'Usuario' }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Mi Perfil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            
            {{-- LADO DERECHO (MÓVIL) - USANDO ICONO DE PERFIL EN LUGAR DE HAMBURGUESA --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-300 hover:bg-gray-800 focus:outline-none focus:bg-gray-800 focus:text-gray-300 transition duration-150 ease-in-out" title="Abrir Menú de Usuario">
                    {{-- Icono de usuario (Chevron Down desaparece, el usuario espera ver opciones de Perfil) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.492 7.492 0 0 0-5.982 2.975m11.963 0a7.5 7.5 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
    
    {{-- Menú Responsive (Móvil) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-gray-700">
                {{ __('Panel de Control') }}
            </x-responsive-nav-link>
            {{-- AGREGUE AQUÍ OTROS ENLACES RESPONSIVE SI ES NECESARIO --}}
        </div>

        <div class="pt-4 pb-1 border-t border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->nombre ?? 'Usuario' }}</div>
                <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:bg-gray-700">
                    {{ __('Mi Perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-white hover:bg-gray-700">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>