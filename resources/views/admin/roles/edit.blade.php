<x-app-layout>
    <x-slot name="header">
        {{-- Usamos padding lateral mínimo en el header --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Editar Rol') . ': ' }} <span class="text-fuchsia-600 dark:text-fuchsia-400">{{ $role->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        {{-- Contenedor Full-Width (max-w-full) con márgenes laterales mínimos --}}
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6">
            
            {{-- Mensajes de Notificación Global (Error de Nivel/Seguridad) --}}
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg shadow-md dark:bg-red-900/30 dark:border-red-600 dark:text-red-300" role="alert">
                    <span class="font-semibold">{{ __('¡Error de Seguridad!') }}</span> {{ session('error') }}
                </div>
            @endif

            {{-- Contenedor del Formulario --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
                <div class="p-4 sm:p-6 lg:p-8">
                    
                    <header class="mb-6 border-b pb-3 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ __('Información del Rol') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Modifica los datos y permisos asociados a este rol.') }}
                        </p>
                    </header>

                    <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-8">
                        @csrf
                        @method('PUT')

                        {{-- GRID PRINCIPAL: 3 COLUMNAS EN ESCRITORIO PARA SEPARAR DATOS BÁSICOS Y PERMISOS --}}
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            {{-- COLUMNA 1 (lg:col-span-1): DATOS BÁSICOS --}}
                            <div class="lg:col-span-1 space-y-6 bg-gray-50 dark:bg-gray-700/30 p-4 sm:p-6 rounded-lg shadow-inner">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b pb-2 dark:border-gray-600">{{ __('Detalles Principales') }}</h3>
                                
                                {{-- Grupo: Nombre y Nivel --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    
                                    {{-- Campo: Nombre del Rol --}}
                                    <div>
                                        <x-input-label for="nombre" :value="__('Nombre del Rol')" />
                                        {{-- El campo de nombre se deshabilita para el rol 'super_administrador' --}}
                                        <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" 
                                            :value="old('nombre', $role->nombre)" required autocomplete="nombre" 
                                            @if ($role->nombre === 'super_administrador') disabled @endif
                                        />
                                        @if ($role->nombre === 'super_administrador')
                                            <p class="text-xs text-red-500 dark:text-red-400 mt-1">
                                                {{ __('El nombre de este rol maestro no puede ser cambiado.') }}
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Usar minúsculas y guiones bajos.') }}</p>
                                        @endif
                                        <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
                                    </div>

                                    {{-- Campo: Nivel de Jerarquía --}}
                                    <div>
                                        <x-input-label for="nivel" :value="__('Nivel de Jerarquía (1-100)')" />
                                        <x-text-input id="nivel" name="nivel" type="number" min="1" max="100" class="mt-1 block w-full" 
                                            :value="old('nivel', $role->nivel)" required autocomplete="nivel" />
                                        <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ __('Nivel 100 es Super Admin.') }}</p>
                                        <x-input-error class="mt-2" :messages="$errors->get('nivel')" />
                                    </div>
                                </div>

                                {{-- Campo: Descripción --}}
                                <div>
                                    <x-input-label for="descripcion" :value="__('Descripción del Rol')" />
                                    <textarea id="descripcion" name="descripcion" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-lg shadow-sm" autocomplete="descripcion" placeholder="Rol encargado de la gestión de artículos...">{{ old('descripcion', $role->descripcion) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('descripcion')" />
                                </div>
                                
                            </div> {{-- Fin COLUMNA 1 --}}

                            {{-- COLUMNA 2 (lg:col-span-2): PERMISOS --}}
                            <div class="lg:col-span-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">{{ __('Asignación de Permisos') }}</h3>
                                
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-6">
                                    {{ __('Marca las casillas para otorgar las capacidades específicas a este rol.') }}
                                </p>

                                {{-- Contenedor de Permisos: Agrupado y en 4 columnas en desktop --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-x-6 gap-y-4">
                                    @forelse ($permissions->groupBy(fn($p) => explode('.', $p->nombre)[0] ?? 'general') as $resource => $perms)
                                        <div class="space-y-2 border-l-4 border-fuchsia-400 pl-3">
                                            {{-- Título del grupo de permisos --}}
                                            <h4 class="text-sm font-bold uppercase tracking-wider text-fuchsia-600 dark:text-fuchsia-400 mt-0">
                                                {{ __(ucwords(str_replace('_', ' ', $resource))) }}
                                            </h4>
                                            
                                            {{-- Checkboxes para el grupo --}}
                                            @foreach ($perms as $permission)
                                                @php
                                                    // Determinar si el permiso debe estar marcado
                                                    $isChecked = in_array($permission->id, old('permissions', $rolePermissions));
                                                @endphp
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5 mt-1">
                                                        <input id="permission-{{ $permission->id }}" name="permissions[]" type="checkbox" value="{{ $permission->id }}" 
                                                            class="rounded border-gray-300 text-fuchsia-600 shadow-sm focus:ring-fuchsia-500 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-fuchsia-600 dark:checked:border-fuchsia-600"
                                                            {{ $isChecked ? 'checked' : '' }}
                                                        >
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        {{-- Nombre legible (ej: Ver Usuario) --}}
                                                        <x-input-label for="permission-{{ $permission->id }}" class="text-sm font-medium cursor-pointer" :value="__(ucwords(str_replace(['.', '_'], ' ', explode('.', $permission->nombre)[1] ?? $permission->nombre)))" />
                                                        {{-- Descripción/nombre técnico (ej: users.view) --}}
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $permission->nombre }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @empty
                                        <p class="col-span-full text-sm text-yellow-600 dark:text-yellow-400">
                                            {{ __('No hay permisos registrados.') }}
                                        </p>
                                    @endforelse
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
                            </div> {{-- Fin COLUMNA 2 --}}
                        </div> {{-- Fin GRID PRINCIPAL --}}
                        
                        {{-- Botones de Acción --}}
                        <div class="flex items-center gap-4 pt-6 border-t dark:border-gray-700 mt-6">
                            <x-primary-button class="bg-fuchsia-600 hover:bg-fuchsia-700 active:bg-fuchsia-900 focus:ring-fuchsia-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-6 4l2 2m0 0l2-2m-2 2V3"></path></svg>
                                {{ __('Guardar Cambios') }}
                            </x-primary-button>
                            
                            <a href="{{ route('admin.roles.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 ease-in-out">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>