<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        {{-- CONTENEDOR PRINCIPAL: Eliminación de max-width y padding para hacerlo casi 'full-width' --}}
        <div class="max-w-full mx-auto space-y-4 px-2 sm:px-4 lg:px-6"> 
            
            {{-- 1. NOTIFICACIONES (ÉXITO/ERROR) --}}
            @if (session('success'))
                {{-- Mantenemos un poco de padding interno para las notificaciones --}}
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md mx-2 sm:mx-0" role="alert">
                    <span class="font-semibold">{{ __('¡Éxito!') }}</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md mx-2 sm:mx-0" role="alert">
                    <span class="font-semibold">{{ __('¡Error!') }}</span> {{ session('error') }}
                </div>
            @endif

            {{-- 2. ENCABEZADO, BÚSQUEDA Y BOTÓN CREAR --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3 px-2 sm:px-0">
                <p class="text-gray-600 dark:text-gray-400 hidden sm:block">
                    {{ __('Lista completa de usuarios registrados en el sistema.') }}
                </p>
                <a href="{{ route('admin.usuarios.create') }}" class="inline-flex items-center px-4 py-2 bg-fuchsia-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="16" x2="22" y1="11" y2="11"/>
                    </svg>
                    {{ __('Crear Nuevo Usuario') }}
                </a>
            </div>

            {{-- 3. FILTRO Y CONTENEDOR DE TABLA/TARJETA --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                
                {{-- Controles de Búsqueda y Filtro (Simulado) --}}
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <input type="text" placeholder="{{ __('Buscar por nombre o email...') }}" class="w-full sm:w-1/3 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-lg shadow-sm">
                </div>
                
                {{-- TABLA DE USUARIOS --}}
                <div class="relative overflow-x-auto">
                    @if ($usuarios->isEmpty())
                        <div class="p-6 text-gray-500 dark:text-gray-400 text-center">
                            {{ __('No se encontraron usuarios en la base de datos.') }}
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    {{-- AVATAR --}}
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[5%]">
                                        {{ __('Avatar') }}
                                    </th>
                                    {{-- Nombre | Correo --}}
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[20%]">
                                        {{ __('Nombre | Correo') }}
                                    </th>
                                    {{-- Teléfono --}}
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[10%] hidden sm:table-cell">
                                        {{ __('Teléfono') }}
                                    </th>
                                    {{-- Roles --}}
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%]">
                                        {{ __('Roles') }}
                                    </th>
                                    {{-- CREADO EN --}}
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[10%] hidden lg:table-cell">
                                        {{ __('Creación') }}
                                    </th>
                                    {{-- Zona Horaria --}}
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%] hidden xl:table-cell">
                                        {{ __('Zona H.') }}
                                    </th>
                                    {{-- Acciones --}}
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%]">
                                        {{ __('Acciones') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($usuarios as $usuario)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                        
                                        {{-- COLUMNA AVATAR --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @php
                                                // Generar un hash MD5 del correo para Gravatar o placeholder
                                                $hash = md5(strtolower(trim($usuario->email)));
                                                $avatarUrl = "https://www.gravatar.com/avatar/{$hash}?s=40&d=mp";
                                            @endphp
                                            {{-- Usar el path de foto_perfil si existe, sino Gravatar --}}
                                            @if ($usuario->foto_perfil_ruta)
                                                <img class="h-10 w-10 rounded-full object-cover border border-fuchsia-300 dark:border-fuchsia-700" 
                                                    src="{{ Storage::url($usuario->foto_perfil_ruta) }}" 
                                                    alt="{{ $usuario->nombre }}">
                                            @else
                                                <img class="h-10 w-10 rounded-full object-cover" 
                                                    src="{{ $avatarUrl }}" 
                                                    alt="{{ $usuario->nombre }}"
                                                    onerror="this.onerror=null;this.src='https://placehold.co/40x40/7c3aed/ffffff?text={{ strtoupper(substr($usuario->nombre, 0, 1)) }}';">
                                            @endif
                                        </td>
                                        
                                        {{-- COLUMNA NOMBRE + EMAIL --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $usuario->nombre }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $usuario->email }}</div>
                                        </td>
                                        
                                        {{-- TELÉFONO --}}
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                                            {{ $usuario->telefono_principal ?? 'N/A' }}
                                        </td>

                                        {{-- ROLES (ACTUALIZADO PARA M:N) --}}
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse ($usuario->roles as $role)
                                                    @php
                                                        // Define colores basados en el nombre del rol (o nivel si prefieres)
                                                        $color = match ($role->nombre) {
                                                            'super_administrador' => 'red',
                                                            'administrador' => 'pink',
                                                            'usuario_estandar' => 'blue',
                                                            default => 'gray',
                                                        };
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-800 dark:text-{{ $color }}-100"
                                                          title="{{ $role->descripcion }}">
                                                        {{ ucwords(str_replace('_', ' ', $role->nombre)) }}
                                                    </span>
                                                @empty
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                        Sin Rol
                                                    </span>
                                                @endforelse
                                            </div>
                                        </td>
                                        
                                        {{-- CREADO EN --}}
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell">
                                            {{ $usuario->created_at->format('Y-m-d') }}
                                        </td>

                                        {{-- ZONA HORARIA --}}
                                        <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400 hidden xl:table-cell" title="{{ $usuario->zona_horaria ?? 'N/A' }}">
                                            {{ Str::limit($usuario->zona_horaria, 10, '...') ?? 'N/A' }}
                                        </td>
                                        
                                        {{-- ACCIONES --}}
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                {{-- Botón Ver --}}
                                                <a href="{{ route('admin.usuarios.show', $usuario) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Ver Detalles') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                                </a>
                                                
                                                {{-- Botón Editar --}}
                                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Editar Roles y Datos') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                                </a>
                                                
                                                {{-- Botón Eliminar (Formulario) --}}
                                                <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('{{ __('¿Estás seguro de que deseas eliminar a este usuario?') }}');" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed" title="{{ $usuario->id === Auth::id() ? __('No puedes eliminarte a ti mismo') : __('Eliminar Usuario') }}"
                                                        @if ($usuario->id === Auth::id()) disabled @endif>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- 4. Paginación --}}
                @if ($usuarios->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $usuarios->links() }}
                    </div>
                @endif
                
            </div>
            
        </div>
    </div>
</x-app-layout>