<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Usuario') . ': ' . $usuario->nombre }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6"> 
        {{-- Contenedor principal centrado, ajustado para ser más compacto --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            
            {{-- Mensajes de Notificación Global (Success/Error) --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded-lg shadow-md dark:bg-green-900/30 dark:border-green-600 dark:text-green-300 text-sm" role="alert">
                    <span class="font-semibold">{{ __('¡Éxito!') }}</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded-lg shadow-md dark:bg-red-900/30 dark:border-red-600 dark:text-red-300 text-sm" role="alert">
                    <span class="font-semibold">{{ __('¡Error!') }}</span> {{ session('error') }}
                </div>
            @endif
            
            {{-- Formulario Único de Edición (Cubre toda la data, incluyendo la contraseña) --}}
            <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" class="space-y-6">
                @csrf
                @method('PUT')
            
                {{-- GRID PRINCIPAL: 2 columnas para compactar --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                    {{-- COLUMNA IZQUIERDA: INFORMACIÓN BÁSICA Y DE CONTACTO --}}
                    <div class="p-4 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg lg:col-span-2 space-y-4">
                        
                        <header class="mb-3 border-b pb-2 dark:border-gray-700">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                {{ __('Información de Contacto y Preferencias') }}
                            </h2>
                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                {{ __('Datos personales, de contacto y configuración regional del usuario.') }}
                            </p>
                        </header>

                        {{-- Grid de 3 columnas para Datos Básicos --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-3">
                            
                            {{-- Campo: Nombre --}}
                            <div>
                                <x-input-label for="nombre" :value="__('Nombre Completo')" class="text-xs" />
                                <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full text-sm" :value="old('nombre', $usuario->nombre)" required autofocus autocomplete="nombre" />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('nombre')" />
                            </div>

                            {{-- Campo: Email --}}
                            <div>
                                <x-input-label for="email" :value="__('Email')" class="text-xs" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full text-sm" :value="old('email', $usuario->email)" required autocomplete="username" />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('email')" />
                            </div>
                            
                            {{-- Campo: Teléfono Principal --}}
                            <div>
                                <x-input-label for="telefono_principal" :value="__('Teléfono Principal (Opcional)')" class="text-xs" />
                                <x-text-input id="telefono_principal" name="telefono_principal" type="text" class="mt-1 block w-full text-sm" :value="old('telefono_principal', $usuario->telefono_principal)" autocomplete="tel" />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('telefono_principal')" />
                            </div>

                            {{-- Campo: Zona Horaria --}}
                            <div>
                                <x-input-label for="zona_horaria" :value="__('Zona Horaria')" class="text-xs" />
                                <select id="zona_horaria" name="zona_horaria" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-lg shadow-sm text-sm h-10" required>
                                    {{-- Se asume que $timezones está disponible en el controlador si usas una lista completa --}}
                                    <option value="America/Lima" {{ old('zona_horaria', $usuario->zona_horaria) == 'America/Lima' ? 'selected' : '' }}>America/Lima</option>
                                    <option value="Europe/Madrid" {{ old('zona_horaria', $usuario->zona_horaria) == 'Europe/Madrid' ? 'selected' : '' }}>Europe/Madrid</option>
                                    <option value="America/New_York" {{ old('zona_horaria', $usuario->zona_horaria) == 'America/New_York' ? 'selected' : '' }}>America/New York</option>
                                </select>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('zona_horaria')" />
                            </div>
                            
                            {{-- Campo: Idioma Preferido --}}
                            <div>
                                <x-input-label for="idioma_preferido" :value="__('Idioma')" class="text-xs" />
                                <select id="idioma_preferido" name="idioma_preferido" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-lg shadow-sm text-sm h-10" required>
                                    <option value="es" {{ old('idioma_preferido', $usuario->idioma_preferido) == 'es' ? 'selected' : '' }}>Español (es)</option>
                                    <option value="en" {{ old('idioma_preferido', $usuario->idioma_preferido) == 'en' ? 'selected' : '' }}>Inglés (en)</option>
                                </select>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('idioma_preferido')" />
                            </div>
                        </div>

                        {{-- Sección: Datos Laborales y Estado --}}
                        <h3 class="text-base font-semibold pt-4 border-t dark:border-gray-700 text-gray-900 dark:text-gray-100">{{ __('Datos Laborales/Estado') }}</h3>

                        {{-- Grid de 3 columnas para Datos Laborales --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-3">
                            
                            {{-- Campo: Departamento --}}
                            <div>
                                <x-input-label for="departamento" :value="__('Departamento')" class="text-xs" />
                                <x-text-input id="departamento" name="departamento" type="text" class="mt-1 block w-full text-sm" :value="old('departamento', $usuario->departamento)" autocomplete="off" />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('departamento')" />
                            </div>
                            
                            {{-- Campo: Posición Laboral --}}
                            <div>
                                <x-input-label for="posicion_laboral" :value="__('Posición Laboral')" class="text-xs" />
                                <x-text-input id="posicion_laboral" name="posicion_laboral" type="text" class="mt-1 block w-full text-sm" :value="old('posicion_laboral', $usuario->posicion_laboral)" autocomplete="off" />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('posicion_laboral')" />
                            </div>

                            {{-- Campo: Es Supervisor (Checkbox) --}}
                            <div class="flex items-center pt-2">
                                <input type="hidden" name="es_supervisor" value="0">
                                <input id="es_supervisor" name="es_supervisor" type="checkbox" value="1" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-indigo-600 dark:checked:border-indigo-600 w-4 h-4"
                                    {{ old('es_supervisor', $usuario->es_supervisor) == 1 ? 'checked' : '' }}
                                >
                                <x-input-label for="es_supervisor" class="ml-2 font-medium text-sm" :value="__('Es Supervisor/Manager')" />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('es_supervisor')" />
                            </div>

                            {{-- Campo: Estado Cuenta (ocupa 2 columnas en lg para balancear el layout) --}}
                            <div class="lg:col-span-2">
                                <x-input-label for="estado_cuenta" :value="__('Estado de Cuenta')" class="text-xs" />
                                <select id="estado_cuenta" name="estado_cuenta" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-lg shadow-sm text-sm h-10" required>
                                    <option value="activo" {{ old('estado_cuenta', $usuario->estado_cuenta) == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ old('estado_cuenta', $usuario->estado_cuenta) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    <option value="suspendido" {{ old('estado_cuenta', $usuario->estado_cuenta) == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                                    <option value="pendiente" {{ old('estado_cuenta', $usuario->estado_cuenta) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                </select>
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('estado_cuenta')" />
                            </div>

                            {{-- Campo: Razón de Estado (ancho completo) --}}
                            <div class="lg:col-span-3">
                                <x-input-label for="razon_estado" :value="__('Razón del Estado (si no está Activo)')" class="text-xs" />
                                <x-text-input id="razon_estado" name="razon_estado" type="text" class="mt-1 block w-full text-sm" :value="old('razon_estado', $usuario->razon_estado)" />
                                <x-input-error class="mt-1 text-xs" :messages="$errors->get('razon_estado')" />
                            </div>
                        </div>

                    </div>

                    {{-- COLUMNA DERECHA: ROLES Y CONTRASEÑA --}}
                    <div class="lg:col-span-1 space-y-4">
                        
                        {{-- BLOQUE 1: ASIGNACIÓN DE ROLES (ACTUALIZADO PARA M:N) --}}
                        <div class="p-4 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                            <h3 class="text-lg font-bold border-b pb-2 dark:border-gray-700 text-gray-900 dark:text-gray-100 mb-3">{{ __('Roles de Seguridad') }}</h3>
                            <x-input-error class="mt-1 text-xs" :messages="$errors->get('roles')" />

                            {{-- Lista de Roles como Checkboxes --}}
                            <div class="space-y-3">
                                @foreach ($roles as $role)
                                    @php
                                        // Determinar color basado en el rol
                                        $color = match ($role->nombre) {
                                            'super_administrador' => 'red',
                                            'administrador' => 'pink',
                                            default => 'indigo',
                                        };
                                        // Revisar si el usuario tiene este rol (usando la relación cargada)
                                        $hasRole = $usuario->roles->contains('id', $role->id);
                                        // Deshabilitar el checkbox si el usuario es un super_administrador y se está editando a sí mismo.
                                        $isDisabled = ($usuario->id === auth()->id() && $role->nombre === 'super_administrador');
                                    @endphp

                                    <div class="flex items-start bg-{{ $color }}-50 dark:bg-gray-700/50 p-2 rounded-lg border border-{{ $color }}-200 dark:border-{{ $color }}-900">
                                        
                                        <div class="flex items-center h-4 mr-2 pt-1">
                                            {{-- CRÍTICO: El nombre del input debe ser 'roles[]' y el valor el ID del rol --}}
                                            <input id="role_{{ $role->id }}" name="roles[]" type="checkbox" value="{{ $role->id }}" 
                                                class="rounded border-gray-300 text-{{ $color }}-600 shadow-sm focus:ring-{{ $color }}-500 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-{{ $color }}-600 dark:checked:border-{{ $color }}-600 w-4 h-4"
                                                {{ old('roles') ? (in_array($role->id, old('roles')) ? 'checked' : '') : ($hasRole ? 'checked' : '') }}
                                                {{ $isDisabled ? 'disabled' : '' }}
                                            >
                                        </div>
                                        <div class="text-xs">
                                            <x-input-label for="role_{{ $role->id }}" class="font-medium text-{{ $color }}-700 dark:text-{{ $color }}-400" :value="ucwords(str_replace('_', ' ', $role->nombre))" />
                                            <p class="text-[0.65rem] text-{{ $color }}-600 dark:text-{{ $color }}-300 mt-0.5">
                                                {{ $role->descripcion }}
                                                @if ($isDisabled)
                                                    <strong class="block text-red-500">{{ __('(No puedes eliminar tu propio rol de Admin.)') }}</strong>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- BLOQUE 2: CAMBIAR CONTRASEÑA --}}
                        <div class="p-4 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                            <h3 class="text-lg font-bold border-b pb-2 dark:border-gray-700 text-gray-900 dark:text-gray-100 mb-3">
                                {{ __('Cambiar Contraseña') }}
                            </h3>
                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400 mb-3">
                                {{ __('Dejar los campos vacíos si no deseas cambiarla.') }}
                            </p>

                            {{-- Campos de Contraseña --}}
                            <div class="space-y-3">
                                <div>
                                    <x-input-label for="password" :value="__('Nueva Contraseña')" class="text-xs" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full text-sm" autocomplete="new-password" />
                                    <x-input-error class="mt-1 text-xs" :messages="$errors->get('password')" />
                                </div>

                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-xs" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full text-sm" autocomplete="new-password" />
                                    <x-input-error class="mt-1 text-xs" :messages="$errors->get('password_confirmation')" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div> {{-- Fin del Grid Principal --}}

                {{-- Botones de Acción Global (Al final del formulario único) --}}
                <div class="flex items-center gap-3 pt-4 border-t dark:border-gray-700">
                    <x-primary-button class="inline-flex items-center px-4 py-2 bg-fuchsia-600 hover:bg-fuchsia-700 active:bg-fuchsia-800 focus:ring-fuchsia-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/>
                        </svg>
                        {{ __('Guardar Todos los Cambios') }}
                    </x-primary-button>
                    
                    <a href="{{ route('admin.usuarios.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 ease-in-out">
                        {{ __('Cancelar y Volver') }}
                    </a>
                </div>
            </form>
            
        </div>
    </div>
</x-app-layout>