<x-app-layout>
    <x-slot name="header">
        {{-- Quitamos padding extra aquí para usar el de la capa superior --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Gestión de Roles') }}
        </h2>
    </x-slot>

    {{-- Contenedor Principal: Se usa max-w-full y padding lateral mínimo --}}
    <div class="py-4 sm:py-6">
        {{-- Se cambia a max-w-full y se reduce el padding horizontal a px-2 --}}
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6">
            
            {{-- Mensajes de Flash (Ajustados al nuevo padding) --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 dark:bg-green-900/30 dark:border-green-600 dark:text-green-300" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 dark:bg-red-900/30 dark:border-red-600 dark:text-red-300" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
                
                {{-- Cabecera y Botón de Creación --}}
                <div class="p-4 sm:p-6 flex justify-between items-center border-b dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ __('Lista de Roles') }}</h3>
                    <a href="{{ route('admin.roles.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-fuchsia-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-fuchsia-700 active:bg-fuchsia-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        {{ __('Crear Nuevo Rol') }}
                    </a>
                </div>

                {{-- Tabla de Roles (Contenedor full-width) --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/5">
                                    {{ __('Nombre') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/12">
                                    {{ __('Nivel') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-5/12">
                                    {{ __('Descripción') }}
                                </th>
                                {{-- Visibilidad ajustada para usar mejor el espacio --}}
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-3/12 hidden lg:table-cell">
                                    {{ __('Permisos Asignados') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-right">
                                    <span class="sr-only">{{ __('Acciones') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($roles as $role)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $role->nombre }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if ($role->nivel >= 90) bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @elseif ($role->nivel >= 50) bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                            @else bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 @endif">
                                            {{ $role->nivel }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 max-w-lg text-sm text-gray-500 dark:text-gray-400">
                                        {{-- Truncate la descripción para no desperdiciar espacio --}}
                                        {{ \Illuminate\Support\Str::limit($role->descripcion ?? 'N/A', 80) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell">
                                        @if ($role->permissions->isEmpty())
                                            <span class="text-xs text-gray-400">{{ __('Sin permisos') }}</span>
                                        @else
                                            <div class="flex flex-wrap gap-1">
                                                {{-- Muestra hasta 4 permisos para aprovechar el ancho --}}
                                                @foreach ($role->permissions->take(4) as $permission)
                                                    <span class="px-2 py-0.5 text-xs font-medium bg-fuchsia-100 text-fuchsia-800 dark:bg-fuchsia-800 dark:text-fuchsia-100 rounded">
                                                        {{ str_replace(['.', '_'], ' ', \Illuminate\Support\Str::limit($permission->nombre, 15)) }}
                                                    </span>
                                                @endforeach
                                                @if ($role->permissions->count() > 4)
                                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                                        +{{ $role->permissions->count() - 4 }} más
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            
                                            {{-- Ver --}}
                                            <a href="{{ route('admin.roles.show', $role) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600 transition duration-150 p-1" 
                                               title="{{ __('Ver Detalles') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            {{-- Editar --}}
                                            <a href="{{ route('admin.roles.edit', $role) }}" 
                                               class="text-fuchsia-600 hover:text-fuchsia-900 dark:text-fuchsia-400 dark:hover:text-fuchsia-600 transition duration-150 p-1" 
                                               title="{{ __('Editar Rol') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>

                                            {{-- Eliminar (Solo si no es Super Admin) --}}
                                            @if ($role->nombre !== 'super_administrador')
                                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar el rol: {{ $role->nombre }}? Esta acción es irreversible.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 transition duration-150 p-1" 
                                                            title="{{ __('Eliminar Rol') }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-600 p-1 cursor-not-allowed" title="{{ __('Rol protegido: No se puede eliminar el Super Administrador.') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('No hay roles registrados en el sistema.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>