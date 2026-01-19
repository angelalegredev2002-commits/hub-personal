<x-app-layout>
    <x-slot name="header">
        {{-- Usamos padding lateral mínimo en el header --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Detalles del Rol') . ': ' }} <span class="text-fuchsia-600 dark:text-fuchsia-400">{{ $role->nombre }}</span>
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        {{-- Contenedor Full-Width (max-w-full) con márgenes laterales mínimos --}}
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6">
            
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
                <div class="p-4 sm:p-6 lg:p-8">
                    
                    <header class="mb-6 border-b pb-4 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ __('Información General') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Vista detallada del rol y sus permisos asociados.') }}
                        </p>
                    </header>

                    {{-- GRID PRINCIPAL: Datos Básicos y Descripción --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pb-6 border-b dark:border-gray-700">
                            
                            {{-- Nombre del Rol --}}
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Nombre') }}</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $role->nombre }}</dd>
                            </div>

                            {{-- Nivel de Jerarquía --}}
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Nivel') }}</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-md 
                                        @if ($role->nivel >= 90) bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                        @elseif ($role->nivel >= 50) bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                        @else bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 @endif">
                                        {{ $role->nivel }}
                                    </span>
                                </dd>
                            </div>
                            
                            {{-- ID del Rol --}}
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('ID Único') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $role->id }}</dd>
                            </div>

                            {{-- Creado/Actualizado (Información adicional) --}}
                             <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Última Actualización') }}</dt>
                                <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $role->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </div>
                        
                        {{-- Descripción (fila completa) --}}
                        <div class="pb-6 border-b dark:border-gray-700">
                            <dt class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('Descripción') }}</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $role->descripcion ?? 'N/A' }}</dd>
                        </div>
                        
                        {{-- SECCIÓN 2: PERMISOS ASIGNADOS --}}
                        <div class="pt-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Permisos Asignados') }} ({{ $role->permissions->count() }})</h3>
                            
                            @if ($role->permissions->isEmpty())
                                <p class="text-sm text-yellow-600 dark:text-yellow-400">
                                    {{ __('Este rol no tiene permisos asignados actualmente.') }}
                                </p>
                            @else
                                {{-- Grid optimizado para mostrar permisos en el máximo ancho --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                                    @foreach ($role->permissions->sortBy('nombre')->groupBy(fn($p) => explode('.', $p->nombre)[0] ?? 'general') as $resource => $perms)
                                        
                                        <div class="space-y-1 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg shadow-sm">
                                            {{-- Título del grupo --}}
                                            <h4 class="text-xs font-bold uppercase tracking-wider text-fuchsia-600 dark:text-fuchsia-400 mb-2 border-b pb-1 border-fuchsia-200 dark:border-fuchsia-800">
                                                {{ __(ucwords(str_replace('_', ' ', $resource))) }}
                                            </h4>
                                            
                                            {{-- Lista de permisos del grupo --}}
                                            @foreach ($perms as $permission)
                                                <div class="flex items-center text-xs space-x-2 text-gray-800 dark:text-gray-200">
                                                    <svg class="w-3 h-3 text-fuchsia-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                    {{ ucwords(str_replace(['.', '_'], ' ', explode('.', $permission->nombre)[1] ?? $permission->nombre)) }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- BOTONES DE ACCIÓN --}}
                    <div class="flex justify-start gap-3 pt-6 mt-6 border-t dark:border-gray-700">
                        
                        {{-- Botón Editar --}}
                        <a href="{{ route('admin.roles.edit', $role) }}" 
                           class="inline-flex items-center px-4 py-2 bg-fuchsia-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            {{ __('Editar Rol') }}
                        </a>

                        {{-- Botón Volver --}}
                        <a href="{{ route('admin.roles.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Volver a la Lista') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>