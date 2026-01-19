<x-guest-layout>
    {{-- Contenedor Principal: Diseño de Doble Columna (Grid) y Estilo General --}}
    <div class="w-full max-w-sm md:max-w-3xl mx-auto overflow-hidden rounded-xl shadow-xl border border-gray-100 bg-white">

        {{-- Estructura GRID: Dos columnas (oculta en móvil, visible en md) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 min-h-[500px]">
            
            {{-- === COLUMNA IZQUIERDA: Marca y Contraste Visual (Branding Conciso) === --}}
            <div class="hidden md:flex flex-col items-center justify-center p-8 bg-slate-800 text-white relative overflow-hidden">
                
                {{-- Logo o Icono Central --}}
                <div class="mb-4 z-10">
                    <i class="fas fa-sitemap fa-3x text-sky-400"></i>
                </div>
                
                <h2 class="text-2xl font-bold mb-2 z-10">Portal de Clientes</h2>
                
                {{-- Elemento Gráfico de Fondo (Patrón de Grid Sutil) --}}
                <div 
                    class="absolute inset-0 bg-repeat opacity-5" 
                    style="background-image: url('data:image/svg+xml;utf8,<svg width=\"100%\" height=\"100%\" xmlns=\"http://www.w3.org/2000/svg\"><defs><pattern id=\"p\" width=\"10\" height=\"10\" patternUnits=\"userSpaceOnUse\"><circle fill=\"#fff\" cx=\"0.5\" cy=\"0.5\" r=\"0.5\"/></pattern></defs><rect width=\"100%\" height=\"100%\" fill=\"url(%23p)\" /></svg>')"
                >
                </div>
            </div>
            
            {{-- === COLUMNA DERECHA: Formulario de Login (Foco y UX) === --}}
            <div class="p-8 sm:p-10 bg-white flex flex-col justify-center">
                
                {{-- ICONO DE REGRESO AL PORTAL (Organización limpia) --}}
                <div class="mb-6 text-left">
                    <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-slate-700 transition duration-150">
                        {{-- Icono de flecha para regresar sin texto --}}
                        <i class="fas fa-arrow-left text-base"></i> 
                    </a>
                </div>

                {{-- Título y subtítulo principal (Formal) --}}
                <div class="mb-4 text-center">
                    <h1 class="text-xl font-extrabold tracking-tight text-gray-900">Acceso al Portal</h1>
                    <p class="text-sm font-medium text-gray-500 mt-1">Por favor, ingrese sus credenciales de acceso.</p>
                </div>
                
                <x-auth-session-status class="mb-4" :status="session('status')" />

                {{-- Formulario de Autenticación --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Campo de Correo Electrónico --}}
                    <div class="relative">
                        <x-input-label for="email" :value="__('Correo Electrónico')" class="text-xs font-semibold text-gray-700 mb-1" />
                        <div class="relative">
                            <i class="fas fa-envelope text-gray-400 text-sm absolute left-3 top-1/2 transform -translate-y-1/2 z-10"></i>
                            <x-text-input 
                                id="email" 
                                class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500 transition duration-150 shadow-sm pl-10" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autofocus 
                                autocomplete="username" 
                            />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- Campo de Contraseña --}}
                    <div class="pt-1 relative">
                        <x-input-label for="password" :value="__('Contraseña')" class="text-xs font-semibold text-gray-700 mb-1" />
                        <div class="relative">
                            <i class="fas fa-lock text-gray-400 text-sm absolute left-3 top-1/2 transform -translate-y-1/2 z-10"></i>
                            <x-text-input 
                                id="password" 
                                class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500 transition duration-150 shadow-sm pl-10"
                                type="password"
                                name="password"
                                required 
                                autocomplete="current-password" 
                            />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Opciones: Mantener Sesión y Recuperar Contraseña --}}
                    <div class="flex items-center justify-between pt-2">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-sky-600 shadow-sm focus:ring-sky-500" name="remember">
                            <span class="ms-2 text-xs text-gray-600 font-medium">{{ __('Mantener Sesión') }}</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a class="text-xs font-semibold text-sky-600 hover:text-sky-800 transition duration-150" href="{{ route('password.request') }}">
                                {{ __('Recuperar Contraseña') }}
                            </a>
                        @endif
                    </div>

                    {{-- Botón de Acceder --}}
                    <div class="pt-4">
                        <x-primary-button class="w-full justify-center py-2.5 text-sm font-bold text-gray-50 bg-sky-700 hover:bg-sky-600 rounded-lg shadow-md transition duration-300 focus:ring-sky-500 focus:ring-offset-2">
                            {{ __('Acceder al Portal') }}
                        </x-primary-button>
                    </div>
                </form>

                {{-- Separador y Opción de Login Social --}}
                <div class="mt-4 flex items-center justify-center">
                    <hr class="w-full border-gray-200">
                    <span class="px-2 text-xs text-gray-500 bg-white">o</span>
                    <hr class="w-full border-gray-200">
                </div>

                <a href="#" class="mt-4 inline-flex items-center justify-center w-full py-2.5 text-sm font-bold rounded-lg text-slate-700 bg-white border border-gray-300 hover:bg-gray-50 transition duration-150 shadow-sm">
                    <i class="fab fa-google mr-2 text-base"></i>
                    Autenticación Única (SSO) con Google
                </a>

                {{-- Enlace para Registrarse --}}
                @if (Route::has('register'))
                    <div class="mt-6 text-center">
                        <p class="text-xs text-gray-600">
                            ¿Necesita una cuenta? 
                            <a class="font-bold text-sky-600 hover:text-sky-800 transition duration-150" href="{{ route('register') }}">
                                Solicitar Registro
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>