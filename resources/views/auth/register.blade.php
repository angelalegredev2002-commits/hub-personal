<x-guest-layout>
    {{-- Contenedor Principal: Diseño de Doble Columna (Grid) y Alpine.js --}}
    <div class="w-full max-w-sm md:max-w-4xl mx-auto overflow-hidden rounded-2xl shadow-2xl border border-gray-100 bg-white"
        x-data="{ 
            currentStep: 1, 
            totalSteps: 3,
            nextStep() {
                // Validación básica de ejemplo (Ajustar según tu lógica de Blade/Livewire)
                // Se asume que la validación del lado del servidor gestiona errores
                if (this.currentStep < this.totalSteps) {
                    this.currentStep++;
                }
            },
            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                }
            },
            get progressWidth() {
                return (this.currentStep / this.totalSteps) * 100;
            }
        }"
    >

        {{-- Estructura GRID: Dos columnas en escritorio --}}
        <div class="grid grid-cols-1 md:grid-cols-2 min-h-[550px]">
            
            {{-- === COLUMNA IZQUIERDA: Marca y CTA (Branding) === --}}
            <div class="hidden md:flex flex-col items-center justify-center p-8 bg-slate-800 text-white relative overflow-hidden">
                <div class="mb-4 z-10">
                    <i class="fas fa-user-plus fa-3x text-sky-400"></i>
                </div>
                
                <h2 class="text-2xl font-bold mb-3 z-10">¡Bienvenido a Bordo!</h2>
                <p class="text-center text-sm opacity-80 mb-8 z-10 px-4">
                    Completa tu perfil para acceder a tus proyectos y herramientas internas.
                </p>

                {{-- OPCIÓN PARA INICIAR SESIÓN (Botón limpio) --}}
                <div class="mt-auto pt-4 text-center z-10">
                    <p class="text-xs font-semibold mb-3 opacity-90">¿Ya tienes una cuenta?</p>
                    <a href="{{ route('login') }}" class="py-2.5 px-6 text-sm font-bold text-slate-800 bg-white hover:bg-gray-100 rounded-lg shadow-md transition duration-300">
                        Iniciar Sesión →
                    </a>
                </div>

                <div class="absolute inset-0 bg-repeat opacity-5" style="background-image: url('data:image/svg+xml;utf8,<svg width=\"100%\" height=\"100%\" xmlns=\"http://www.w3.org/2000/svg\"><defs><pattern id=\"p\" width=\"10\" height=\"10\" patternUnits=\"userSpaceOnUse\"><circle fill=\"#fff\" cx=\"0.5\" cy=\"0.5\" r=\"0.5\"/></pattern></defs><rect width=\"100%\" height=\"100%\" fill=\"url(%23p)\" /></svg>')">
                </div>
            </div>
            
            {{-- === COLUMNA DERECHA: Formulario MULTI-PASO (Grid y Organización) === --}}
            <div class="p-8 sm:p-10 bg-white overflow-y-auto">

                {{-- Barra de Progreso --}}
                <div class="w-full mb-8">
                    <div class="flex justify-between mb-1 text-sm font-medium text-gray-600">
                        <span x-text="'Paso ' + currentStep + ' de ' + totalSteps"></span>
                        <span x-text="Math.round(progressWidth) + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div 
                            class="bg-sky-500 h-2.5 rounded-full transition-all duration-500 ease-out" 
                            :style="`width: ${progressWidth}%`"
                        ></div>
                    </div>
                </div>

                {{-- Título y subtítulo --}}
                <div class="mb-6 text-center md:text-left">
                    <h1 class="text-2xl font-extrabold tracking-tight text-gray-950">Crear Cuenta</h1>
                    <p class="text-sm text-gray-700 mt-1">Configura tu acceso a la plataforma.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    {{-- === PASO 1: DATOS DE ACCESO (Estructura interna más compacta) === --}}
                    <div x-show="currentStep === 1" class="space-y-4">
                        <h3 class="text-xs font-bold text-sky-600 border-b border-sky-100 pb-1 uppercase tracking-wider">1. Datos de Acceso</h3>

                        <div class="relative">
                            <x-input-label for="name" :value="__('Nombre Completo')" class="text-xs font-semibold text-gray-700 mb-1" />
                            <i class="fas fa-user text-gray-400 text-sm absolute left-3 top-[32px] z-10"></i>
                            <x-text-input 
                                id="name" 
                                class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500 pl-10" 
                                type="text" 
                                name="name" 
                                :value="old('name')" 
                                required 
                                autofocus 
                                autocomplete="name" 
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div class="relative">
                            <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-gray-700 mb-1" />
                            <i class="fas fa-envelope text-gray-400 text-sm absolute left-3 top-[32px] z-10"></i>
                            <x-text-input 
                                id="email" 
                                class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500 pl-10" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autocomplete="username" 
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                        
                        {{-- Uso de Grid para Contraseñas --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative">
                                <x-input-label for="password" :value="__('Contraseña')" class="text-xs font-semibold text-gray-700 mb-1" />
                                <i class="fas fa-lock text-gray-400 text-sm absolute left-3 top-[32px] z-10"></i>
                                <x-text-input 
                                    id="password" 
                                    class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500 pl-10"
                                    type="password"
                                    name="password"
                                    required 
                                    autocomplete="new-password" 
                                />
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>
                            
                            <div class="relative">
                                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-xs font-semibold text-gray-700 mb-1" />
                                <i class="fas fa-lock text-gray-400 text-sm absolute left-3 top-[32px] z-10"></i>
                                <x-text-input 
                                    id="password_confirmation" 
                                    class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500 pl-10"
                                    type="password"
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password" 
                                />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    {{-- === PASO 2: INFORMACIÓN PERSONAL === --}}
                    <div x-show="currentStep === 2" class="space-y-4">
                        <h3 class="text-xs font-bold text-sky-600 border-b border-sky-100 pb-1 uppercase tracking-wider">2. Información Personal (Opcional)</h3>

                        {{-- Uso de Grid para campos duales --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative">
                                <x-input-label for="identificacion_dni" :value="__('Identificación (DNI/Cédula)')" class="text-xs font-semibold text-gray-700 mb-1" />
                                <i class="fas fa-id-card text-gray-400 text-sm absolute left-3 top-[32px] z-10"></i>
                                <x-text-input 
                                    id="identificacion_dni" 
                                    class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500 pl-10" 
                                    type="text" 
                                    name="identificacion_dni" 
                                    :value="old('identificacion_dni')" 
                                />
                                <x-input-error :messages="$errors->get('identificacion_dni')" class="mt-1" />
                            </div>

                            <div class="relative">
                                <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" class="text-xs font-semibold text-gray-700 mb-1" />
                                <i class="fas fa-calendar-alt text-gray-400 text-sm absolute left-3 top-[32px] z-10"></i>
                                <x-text-input 
                                    id="fecha_nacimiento" 
                                    class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500 pl-10" 
                                    type="date" 
                                    name="fecha_nacimiento" 
                                    :value="old('fecha_nacimiento')" 
                                />
                                <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-1" />
                            </div>
                        </div>
                        
                        <div class="relative">
                            <x-input-label for="telefono_principal" :value="__('Teléfono Principal')" class="text-xs font-semibold text-gray-700 mb-1" />
                            <i class="fas fa-phone text-gray-400 text-sm absolute left-3 top-[32px] z-10"></i>
                            <x-text-input 
                                id="telefono_principal" 
                                class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500 pl-10" 
                                type="text" 
                                name="telefono_principal" 
                                :value="old('telefono_principal')" 
                                autocomplete="tel" 
                            />
                            <x-input-error :messages="$errors->get('telefono_principal')" class="mt-1" />
                        </div>
                    </div>

                    {{-- === PASO 3: CONFIGURACIÓN INICIAL === --}}
                    <div x-show="currentStep === 3" class="space-y-4">
                        <h3 class="text-xs font-bold text-sky-600 border-b border-sky-100 pb-1 uppercase tracking-wider">3. Configuración Inicial</h3>

                        {{-- Uso de Grid para Selects --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="zona_horaria" :value="__('Zona Horaria')" class="text-xs font-semibold text-gray-700 mb-1" />
                                <select 
                                    id="zona_horaria" 
                                    name="zona_horaria"
                                    class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500"
                                    required
                                >
                                    <option value="" disabled {{ old('zona_horaria') == null ? 'selected' : '' }}>Selecciona</option>
                                    <option value="America/Lima" {{ old('zona_horaria') == 'America/Lima' ? 'selected' : '' }}>Lima (GMT-5)</option>
                                    <option value="America/Bogota" {{ old('zona_horaria') == 'America/Bogota' ? 'selected' : '' }}>Bogotá (GMT-5)</option>
                                    <option value="Europe/Madrid" {{ old('zona_horaria') == 'Europe/Madrid' ? 'selected' : '' }}>Madrid (GMT+2)</option>
                                    <option value="UTC" {{ old('zona_horaria', 'UTC') == 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
                                </select>
                                <x-input-error :messages="$errors->get('zona_horaria')" class="mt-1" />
                            </div>
                            
                            <div>
                                <x-input-label for="idioma_preferido" :value="__('Idioma')" class="text-xs font-semibold text-gray-700 mb-1" />
                                <select 
                                    id="idioma_preferido" 
                                    name="idioma_preferido"
                                    class="block w-full text-sm rounded-lg border-gray-300 focus:border-sky-500 focus:ring-sky-500"
                                    required
                                >
                                    <option value="es" {{ old('idioma_preferido', 'es') == 'es' ? 'selected' : '' }}>Español</option>
                                    <option value="en" {{ old('idioma_preferido') == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                                <x-input-error :messages="$errors->get('idioma_preferido')" class="mt-1" />
                            </div>
                        </div>
                    </div>


                    {{-- === CONTROLES DE NAVEGACIÓN (Grid de Botones) === --}}
                    <div class="pt-6 grid grid-cols-2 gap-4">
                        
                        {{-- Botón Atrás (Oculto en Paso 1) --}}
                        <button 
                            type="button" 
                            class="py-2 text-sm font-semibold text-gray-600 hover:text-gray-800 transition duration-150 border border-gray-300 rounded-lg" 
                            @click="prevStep()" 
                            x-show="currentStep > 1"
                        >
                            <i class="fas fa-arrow-left text-xs mr-2"></i>
                            Atrás
                        </button>
                        
                        {{-- Botón Siguiente (Ancho completo en móvil, mitad en escritorio si está el botón Atrás) --}}
                        <div :class="{'col-span-2': currentStep === 1, 'col-span-1': currentStep > 1}">
                            <button 
                                type="button" 
                                class="w-full justify-center py-2 text-sm font-bold text-white bg-sky-500 hover:bg-sky-600 rounded-lg shadow-md transition duration-300 focus:ring-sky-500 focus:ring-offset-2"
                                @click="nextStep()"
                                x-show="currentStep < totalSteps"
                            >
                                Siguiente Paso
                                <i class="fas fa-arrow-right text-xs ml-2"></i>
                            </button>
                        </div>

                        {{-- Botón de Enviar (Solo en el último paso) --}}
                        <div x-show="currentStep === totalSteps" class="col-span-2">
                            <x-primary-button class="w-full justify-center py-2.5 text-sm font-bold text-white bg-sky-600 hover:bg-sky-700 rounded-lg shadow-lg transition duration-300 focus:ring-sky-500 focus:ring-offset-2">
                                {{ __('Registrar Cuenta') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>

                {{-- Opción de Iniciar Sesión para móviles --}}
                @if (Route::has('login'))
                    <div class="mt-6 text-center md:hidden">
                        <p class="text-xs text-gray-600">
                            ¿Ya tienes cuenta? 
                            <a class="font-bold text-sky-600 hover:text-sky-800 underline transition duration-150" href="{{ route('login') }}">
                                Iniciar Sesión
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>