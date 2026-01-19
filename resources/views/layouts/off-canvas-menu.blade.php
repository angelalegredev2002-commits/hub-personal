{{-- 
    1. CONTENEDOR DEL MENÚ (w-64, z-50)
--}}
<div 
    x-show="isMenuOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    
    {{-- Fondo y Sombra Modernos --}}
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-2xl flex flex-col"
    x-cloak
>
    
    <div class="p-4 flex-col h-full overflow-y-auto">
        
        {{-- CABECERA Y BOTÓN DE CIERRE --}}
        <div class="shrink-0 flex items-center mb-8 justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 overflow-hidden">
                <x-application-logo class="block h-8 w-auto fill-current text-fuchsia-600 dark:text-fuchsia-400" />
                <span class="text-xl font-bold text-gray-800 dark:text-gray-200">
                    {{ config('app.name', 'hub-personal') }}
                </span>
            </a>

            {{-- Botón de Cierre --}}
            <button @click="isMenuOpen = false" class="p-1 text-gray-500 hover:text-fuchsia-600 focus:outline-none dark:hover:text-fuchsia-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- ENLACES PRINCIPALES --}}
        <div class="space-y-2">
            
            {{-- Enlace de Panel (Dashboard) - FUCSIA --}}
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" @click="isMenuOpen = false"
                :class="request()->routeIs('dashboard') ? 'bg-fuchsia-50 dark:bg-gray-700/50 text-fuchsia-600 dark:text-fuchsia-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <svg class="h-6 w-6 me-3" 
                     :class="request()->routeIs('dashboard') ? 'text-fuchsia-500 dark:text-fuchsia-400' : 'text-gray-500 group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400'" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ __('Dashboard') }}
            </x-nav-link>
            
            {{-- 🟢 Enlace Mensajería (Chat) - VERDE --}}
            <x-nav-link :href="route('chat.panel')" :active="request()->routeIs('chat.panel')" 
                class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" @click="isMenuOpen = false"
                :class="request()->routeIs('chat.panel') ? 'bg-green-50 dark:bg-gray-700/50 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <svg class="h-6 w-6 me-3" 
                     :class="request()->routeIs('chat.panel') ? 'text-green-500 dark:text-green-400' : 'text-green-500 group-hover:text-green-600 dark:group-hover:text-green-400'" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M12 21h-2l-2-2H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2h-4l-2 2z" />
                </svg>
                {{ __('Mensajería') }}
            </x-nav-link>

            {{-- 🟠 Enlace Reuniones (USUARIO) - NARANJA --}}
            <x-nav-link :href="route('reuniones.index')" 
                :active="request()->routeIs('reuniones.*')" 
                class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" 
                @click="isMenuOpen = false"
                {{-- CLASES ACTIVE: Fondo naranja claro, texto naranja --}}
                :class="request()->routeIs('reuniones.*') ? 'bg-orange-50 dark:bg-gray-700/50 text-orange-600 dark:text-orange-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <svg class="h-6 w-6 me-3" 
                     :class="request()->routeIs('reuniones.*') ? 'text-orange-500 dark:text-orange-400' : 'text-orange-500 group-hover:text-orange-600 dark:group-hover:text-orange-400'" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    {{-- Icono de Calendario --}}
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h.01M15 12h-6M18 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2z" />
                </svg>
                {{ __('Mis Reuniones') }}
            </x-nav-link>

            {{-- 🔵 Enlace Proyectos y Tareas (USUARIO) - AZUL --}}
            <x-nav-link :href="route('proyectos.index')" 
                :active="request()->routeIs(['proyectos.*', 'tareas.*'])" 
                class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" 
                @click="isMenuOpen = false"
                :class="request()->routeIs(['proyectos.*', 'tareas.*']) ? 'bg-blue-50 dark:bg-gray-700/50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <svg class="h-6 w-6 me-3" 
                     :class="request()->routeIs(['proyectos.*', 'tareas.*']) ? 'text-blue-500 dark:text-blue-400' : 'text-blue-500 group-hover:text-blue-600 dark:group-hover:text-blue-400'" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6a2 2 0 00-2-2H5a2 2 0 00-2 2v13m7 0H9m7 0h-7M9 19v-5a2 2 0 012-2h2a2 2 0 012 2v5m-7 0a2 2 0 002 2h2a2 2 0 002-2M15 19H9" />
                </svg>
                {{ __('Proyectos y Tareas') }}
            </x-nav-link>
            
            {{-- Enlace Clientes - NEUTRO --}}
            <a href="#" class="flex items-center px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out group" @click="isMenuOpen = false">
                <svg class="h-6 w-6 text-gray-500 group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 me-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ __('Clientes (Demo)') }}
            </a>
            
            {{-- ENLACES DE ADMINISTRACIÓN --}}
            @if(Auth::check() && Auth::user()->es_administrador)
                {{-- Separador y Título para la sección de ADMIN --}}
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <span class="block px-3 py-2 text-xs font-semibold uppercase text-fuchsia-600 dark:text-fuchsia-400">{{ __('ADMINISTRACIÓN') }}</span>
                
                    {{-- Bloque de Enlaces de Administración (ROJO) --}}
                    @php
                        // Definición centralizada de clases para la sección de ADMIN
                        $adminActiveClass = 'bg-red-50 dark:bg-gray-700/50 text-red-600 dark:text-red-400';
                        $adminInactiveClass = 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700';
                        $iconInactiveClass = 'text-red-500 group-hover:text-red-600 dark:group-hover:text-red-400';
                        $iconActiveClass = 'text-red-600 dark:text-red-400';
                    @endphp
                    
                    {{-- 🔴 Gestión de Proyectos (ADMIN) ⬅️ AÑADIDO --}}
                    <x-nav-link href="{{ route('admin.proyectos.index') }}" 
                       :active="request()->routeIs('admin.proyectos.*')"
                       class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" 
                       @click="isMenuOpen = false"
                       :class="request()->routeIs('admin.proyectos.*') ? $adminActiveClass : $adminInactiveClass">
                        <svg class="h-6 w-6 me-3" 
                             :class="request()->routeIs('admin.proyectos.*') ? $iconActiveClass : $iconInactiveClass" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2zM6 9h12M6 15h12" />
                        </svg>
                        {{ __('Gestión de Proyectos') }}
                    </x-nav-link>

                    {{-- 🔴 Gestión de Tareas (ADMIN) ⬅️ AÑADIDO --}}
                    <x-nav-link href="{{ route('admin.tareas.index') }}" 
                       :active="request()->routeIs('admin.tareas.*')"
                       class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" 
                       @click="isMenuOpen = false"
                       :class="request()->routeIs('admin.tareas.*') ? $adminActiveClass : $adminInactiveClass">
                        <svg class="h-6 w-6 me-3" 
                             :class="request()->routeIs('admin.tareas.*') ? $iconActiveClass : $iconInactiveClass" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        {{ __('Gestión de Tareas') }}
                    </x-nav-link>
                    
                    {{-- 🔴 Gestión de Reuniones (ADMIN) --}}
                    <x-nav-link href="{{ route('admin.reuniones.index') }}" 
                       :active="request()->routeIs('admin.reuniones.*')"
                       class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" 
                       @click="isMenuOpen = false"
                       :class="request()->routeIs('admin.reuniones.*') ? $adminActiveClass : $adminInactiveClass">
                        <svg class="h-6 w-6 me-3" 
                             :class="request()->routeIs('admin.reuniones.*') ? $iconActiveClass : $iconInactiveClass" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h.01M15 12h-6M18 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2z" />
                        </svg>
                        {{ __('Gestión de Reuniones') }}
                    </x-nav-link>
                    
                    {{-- Gestión de Roles --}}
                    <x-nav-link href="{{ route('admin.roles.index') }}" 
                       :active="request()->routeIs('admin.roles.*')"
                       class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" 
                       @click="isMenuOpen = false"
                       :class="request()->routeIs('admin.roles.*') ? $adminActiveClass : $adminInactiveClass">
                        <svg class="h-6 w-6 me-3" 
                             :class="request()->routeIs('admin.roles.*') ? $iconActiveClass : $iconInactiveClass" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2v5a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2h6zm0 0V4m-2 2h4M9 16h6m-3 0v4m0-4v-4m-3 4h6" />
                        </svg>
                        {{ __('Gestión de Roles') }}
                    </x-nav-link>

                    {{-- Gestión de Permisos --}}
                    <x-nav-link href="{{ route('admin.permissions.index') }}" 
                       :active="request()->routeIs('admin.permissions.*')"
                       class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" 
                       @click="isMenuOpen = false"
                       :class="request()->routeIs('admin.permissions.*') ? $adminActiveClass : $adminInactiveClass">
                        <svg class="h-6 w-6 me-3" 
                             :class="request()->routeIs('admin.permissions.*') ? $iconActiveClass : $iconInactiveClass" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.856a4.5 4.5 0 00-6.364-6.364L12 7.636l-.382-.382a4.5 4.5 0 00-6.364 6.364L12 21.636l6.364-6.364a4.5 4.5 0 000-6.364z" />
                        </svg>
                        {{ __('Gestión de Permisos') }}
                    </x-nav-link>
                    
                    {{-- Gestión de Usuarios --}}
                    <x-nav-link href="{{ route('admin.usuarios.index') }}" 
                       :active="request()->routeIs('admin.usuarios.index')"
                       class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" 
                       @click="isMenuOpen = false"
                       :class="request()->routeIs('admin.usuarios.index') ? $adminActiveClass : $adminInactiveClass">
                        <svg class="h-6 w-6 me-3" 
                             :class="request()->routeIs('admin.usuarios.index') ? $iconActiveClass : $iconInactiveClass" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2a3 3 0 015.356-1.857M7 20h5.356m7.443-13.856L15.357 5.214M17 8H7v-2a3 3 0 013-3h4a3 3 0 013 3v2M12 5v2m0 4h.01M17 14h-1.056M9 14h1.056" />
                        </svg>
                        {{ __('Gestión de Usuarios') }}
                    </x-nav-link>
                    
                    {{-- Gestión de Chat --}}
                    <x-nav-link href="{{ route('admin.conversaciones.index') }}" 
                       :active="request()->routeIs('admin.conversaciones.index')"
                       class="flex items-center px-3 py-2 rounded-lg transition duration-150 ease-in-out group" 
                       @click="isMenuOpen = false"
                       :class="request()->routeIs('admin.conversaciones.index') ? $adminActiveClass : $adminInactiveClass">
                        <svg class="h-6 w-6 me-3" 
                             :class="request()->routeIs('admin.conversaciones.index') ? $iconActiveClass : $iconInactiveClass" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0H4M20 13v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4" />
                        </svg>
                        {{ __('Gestión de Chat') }}
                    </x-nav-link>
                </div>
            @endif
            
            {{-- Enlace Perfil y Ajustes - NEUTRO (Siempre al final) --}}
            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out group mt-4" @click="isMenuOpen = false">
                <svg class="h-6 w-6 text-gray-500 group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 me-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.228.608 3.224 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('Perfil y Ajustes') }}
            </a>
        </div>
    </div>
    
</div>


{{-- 
    2. OVERLAY (Oculta el contenido y permite cerrar el menú al hacer clic). 
--}}
<div 
    x-show="isMenuOpen" 
    x-transition:opacity
    @click="isMenuOpen = false" 
    class="fixed inset-0 z-40 bg-black opacity-50"
    x-cloak
></div>