<x-app-layout>
    <x-slot name="header">
        {{-- Quitamos padding extra aquí, lo manejamos en el contenedor principal --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Crear Nuevo Usuario') }}
        </h2>
    </x-slot>

    {{-- Reducción de padding vertical general --}}
    <div class="py-2 sm:py-3"> 
        {{-- Contenedor principal: Max-width al 100%, márgenes laterales mínimos --}}
        <div class="max-w-full mx-auto px-2 sm:px-3 lg:px-4 space-y-3">
            
            {{-- Mensajes de Notificación Global --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-2 rounded-lg shadow-sm text-xs mx-1 sm:mx-0" role="alert">
                    <span class="font-semibold">{{ __('¡Éxito!') }}</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-2 rounded-lg shadow-sm text-xs mx-1 sm:mx-0" role="alert">
                    <span class="font-semibold">{{ __('¡Error!') }}</span> {{ session('error') }}
                </div>
            @endif
            
            {{-- Contenedor del Formulario (Reducción de padding interior) --}}
            <div class="p-3 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                
                {{-- Encabezado --}}
                <header class="mb-3 border-b pb-2 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        {{ __('Alta de Cuenta Nueva') }}
                    </h2>
                    {{-- Texto más pequeño --}}
                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                        {{ __('Rellena los campos obligatorios y define los permisos de acceso del nuevo usuario.') }}
                    </p>
                </header>

                {{-- FORMULARIO DE CREACIÓN --}}
                <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-4">
                    @csrf
                    
                    {{-- ========================================================== --}}
                    {{-- GRUPO 1: DATOS PERSONALES Y CONTACTO --}}
                    {{-- ========================================================== --}}
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Información Básica') }}</h3>

                    {{-- Usamos 4 columnas para comprimir la información --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-3">

                        {{-- Campo: Nombre --}}
                        <div>
                            <x-input-label for="nombre" :value="__('Nombre Completo')" class="text-xs" />
                            <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full text-sm" :value="old('nombre')" required autofocus autocomplete="nombre" />
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('nombre')" />
                        </div>

                        {{-- Campo: Email --}}
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-xs" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full text-sm" :value="old('email')" required autocomplete="email" />
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('email')" />
                        </div>

                        {{-- Campo: Teléfono (Opcional) --}}
                        <div>
                            <x-input-label for="telefono_principal" :value="__('Teléfono Principal (Opcional)')" class="text-xs" />
                            <x-text-input id="telefono_principal" name="telefono_principal" type="text" class="mt-1 block w-full text-sm" :value="old('telefono_principal')" autocomplete="tel" />
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('telefono_principal')" />
                        </div>

                        {{-- Campo: Idioma Preferido --}}
                        <div class="md:col-span-1">
                            <x-input-label for="idioma_preferido" :value="__('Idioma')" class="text-xs" />
                            <select id="idioma_preferido" name="idioma_preferido" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-lg shadow-sm text-sm h-10" required>
                                <option value="es" {{ old('idioma_preferido') == 'es' ? 'selected' : '' }}>Español (es)</option>
                                <option value="en" {{ old('idioma_preferido') == 'en' ? 'selected' : '' }}>Inglés (en)</option>
                            </select>
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('idioma_preferido')" />
                        </div>
                        
                        {{-- Campo: Zona Horaria --}}
                        <div class="lg:col-span-2">
                            <x-input-label for="zona_horaria" :value="__('Zona Horaria')" class="text-xs" />
                            <select id="zona_horaria" name="zona_horaria" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-lg shadow-sm text-sm h-10" required>
                                <option value="America/Lima" {{ old('zona_horaria', config('app.timezone')) == 'America/Lima' ? 'selected' : '' }}>America/Lima</option>
                                <option value="Europe/Madrid" {{ old('zona_horaria') == 'Europe/Madrid' ? 'selected' : '' }}>Europe/Madrid</option>
                                <option value="America/New_York" {{ old('zona_horaria') == 'America/New_York' ? 'selected' : '' }}>America/New York</option>
                                {{-- Añadir más opciones según sea necesario --}}
                            </select>
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('zona_horaria')" />
                        </div>
                    </div>
                    
                    {{-- ========================================================== --}}
                    {{-- GRUPO 2: CREDENCIALES DE ACCESO --}}
                    {{-- ========================================================== --}}
                    <h3 class="text-base font-semibold pt-3 border-t dark:border-gray-700 text-gray-900 dark:text-gray-100">{{ __('Credenciales de Acceso') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                        
                        {{-- Campo: Contraseña --}}
                        <div>
                            <x-input-label for="password" :value="__('Contraseña')" class="text-xs" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full text-sm" required autocomplete="new-password" />
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('password')" />
                        </div>

                        {{-- Campo: Confirmar Contraseña --}}
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-xs" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full text-sm" required autocomplete="new-password" />
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('password_confirmation')" />
                        </div>
                    </div>
                    
                    {{-- ========================================================== --}}
                    {{-- GRUPO 3: ASIGNACIÓN DE ROLES --}}
                    {{-- ========================================================== --}}
                    <h3 class="text-base font-semibold pt-3 border-t dark:border-gray-700 text-gray-900 dark:text-gray-100">{{ __('Asignación de Roles') }}</h3>
                    <x-input-error class="mt-1 text-xs" :messages="$errors->get('roles')" />

                    {{-- Lista de Roles como Checkboxes (Más compactos) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        @foreach ($roles as $role)
                            @php
                                // Determinar color basado en el rol
                                $color = match ($role->nombre) {
                                    'super_administrador' => 'red',
                                    'administrador' => 'pink',
                                    default => 'indigo',
                                };
                            @endphp

                            <div class="flex items-start bg-{{ $color }}-50 dark:bg-gray-700/50 p-2 rounded-lg border border-{{ $color }}-200 dark:border-{{ $color }}-900">
                                
                                <div class="flex items-center h-4 mr-2 pt-1">
                                    <input id="role_{{ $role->id }}" name="roles[]" type="checkbox" value="{{ $role->id }}" 
                                        class="rounded border-gray-300 text-{{ $color }}-600 shadow-sm focus:ring-{{ $color }}-500 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-{{ $color }}-600 dark:checked:border-{{ $color }}-600 w-4 h-4"
                                        {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                    >
                                </div>
                                <div class="text-xs">
                                    <x-input-label for="role_{{ $role->id }}" class="font-medium text-{{ $color }}-700 dark:text-{{ $color }}-400" :value="ucwords(str_replace('_', ' ', $role->nombre))" />
                                    <p class="text-[0.65rem] text-{{ $color }}-600 dark:text-{{ $color }}-300 mt-0.5">
                                        {{ Str::limit($role->descripcion, 50) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- ========================================================== --}}
                    {{-- GRUPO 4: DATOS DE RRHH --}}
                    {{-- ========================================================== --}}
                    <h3 class="text-base font-semibold pt-3 border-t dark:border-gray-700 text-gray-900 dark:text-gray-100">{{ __('Datos Laborales/Estado (Opcional)') }}</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-3">
                        
                        {{-- Campo: Departamento --}}
                        <div>
                            <x-input-label for="departamento" :value="__('Departamento')" class="text-xs" />
                            <x-text-input id="departamento" name="departamento" type="text" class="mt-1 block w-full text-sm" :value="old('departamento')" autocomplete="off" />
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('departamento')" />
                        </div>
                        
                        {{-- Campo: Posición Laboral --}}
                        <div>
                            <x-input-label for="posicion_laboral" :value="__('Posición Laboral')" class="text-xs" />
                            <x-text-input id="posicion_laboral" name="posicion_laboral" type="text" class="mt-1 block w-full text-sm" :value="old('posicion_laboral')" autocomplete="off" />
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('posicion_laboral')" />
                        </div>
                        
                        {{-- Campo: Estado Cuenta --}}
                        <div>
                            <x-input-label for="estado_cuenta" :value="__('Estado de Cuenta')" class="text-xs" />
                            <select id="estado_cuenta" name="estado_cuenta" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-lg shadow-sm text-sm h-10" required>
                                <option value="activo" {{ old('estado_cuenta') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado_cuenta') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="suspendido" {{ old('estado_cuenta') == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                                <option value="pendiente" {{ old('estado_cuenta') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            </select>
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('estado_cuenta')" />
                        </div>

                        {{-- Campo: Es Supervisor (Checkbox) --}}
                        <div class="flex items-center pt-2">
                             <input id="es_supervisor" name="es_supervisor" type="checkbox" value="1" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-indigo-600 dark:checked:border-indigo-600 w-4 h-4"
                                {{ old('es_supervisor') == 1 ? 'checked' : '' }}
                            >
                            <x-input-label for="es_supervisor" class="ml-2 font-medium text-sm" :value="__('Es Supervisor/Manager')" />
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('es_supervisor')" />
                        </div>

                        {{-- Campo: Razón de Estado (requerido si no está activo, ocupa ancho completo en móviles) --}}
                        <div class="sm:col-span-2 lg:col-span-4">
                            <x-input-label for="razon_estado" :value="__('Razón del Estado (si no está Activo)')" class="text-xs" />
                            <x-text-input id="razon_estado" name="razon_estado" type="text" class="mt-1 block w-full text-sm" :value="old('razon_estado')" />
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('razon_estado')" />
                        </div>

                    </div>

                    
                    {{-- Botones de Acción (Más compactos) --}}
                    <div class="flex items-center gap-3 pt-4 border-t dark:border-gray-700">
                        <x-primary-button class="inline-flex items-center px-3 py-1.5 bg-fuchsia-600 hover:bg-fuchsia-700 active:bg-fuchsia-800 focus:ring-fuchsia-300 text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="16" x2="22" y1="11" y2="11"/>
                            </svg>
                            {{ __('Crear Usuario') }}
                        </x-primary-button>
                        
                        <a href="{{ route('admin.usuarios.index') }}" class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 ease-in-out">
                            {{ __('Cancelar y Volver') }}
                        </a>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>