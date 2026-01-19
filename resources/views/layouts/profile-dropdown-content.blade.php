{{-- Código Original (Ideal para fondo BLANCO) --}}
<div class="hidden sm:flex sm:items-center sm:ms-6">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="flex items-center p-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-semibold rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition ease-in-out duration-150 shadow-sm">
                
                {{-- Opcional: Iniciales del usuario para un look moderno --}}
                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-white text-xs font-bold mr-2">
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