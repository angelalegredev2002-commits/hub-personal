<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Gestión de Permisos') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6">
            
            {{-- Mensajes de Sesión --}}
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
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ __('Lista de Permisos del Sistema') }}</h3>
                    @if (Auth::user()->isSuperAdmin())
                        <a href="{{ route('admin.permissions.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-fuchsia-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-fuchsia-700 active:bg-fuchsia-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            {{ __('Crear Permiso') }}
                        </a>
                    @endif
                </div>

                {{-- Tabla de Permisos --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-2/5">
                                    {{ __('Nombre del Permiso') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-2/5">
                                    {{ __('Descripción') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/5">
                                    {{ __('Roles Asignados') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-right">
                                    <span class="sr-only">{{ __('Acciones') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($permissions as $permission)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 font-mono">
                                            {{ $permission->nombre }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 max-w-lg text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Illuminate\Support\Str::limit($permission->descripcion ?? 'N/A', 80) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">
                                            {{ $permission->roles->count() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('admin.permissions.show', $permission) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600 p-1" title="{{ __('Ver Detalles') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            @if (Auth::user()->isSuperAdmin())
                                                <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-fuchsia-600 hover:text-fuchsia-900 dark:text-fuchsia-400 dark:hover:text-fuchsia-600 p-1" title="{{ __('Editar Permiso') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Esta acción es irreversible.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 p-1" title="{{ __('Eliminar Permiso') }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('No hay permisos registrados en el sistema.') }}
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