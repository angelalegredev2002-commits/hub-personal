<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Administración de Reuniones') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        {{-- CONTENEDOR PRINCIPAL: Ancho completo y padding mínimo --}}
        <div class="max-w-full mx-auto space-y-4 px-2 sm:px-4 lg:px-6"> 
            
            {{-- 1. NOTIFICACIONES (ÉXITO/ERROR) --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md mx-2 sm:mx-0 dark:bg-green-900 dark:border-green-700 dark:text-green-200" role="alert">
                    <span class="font-semibold">{{ __('¡Éxito!') }}</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md mx-2 sm:mx-0 dark:bg-red-900 dark:border-red-700 dark:text-red-200" role="alert">
                    <span class="font-semibold">{{ __('¡Error!') }}</span> {{ session('error') }}
                </div>
            @endif

            {{-- 2. ENCABEZADO Y BOTÓN CREAR --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3 px-2 sm:px-0">
                <p class="text-gray-600 dark:text-gray-400 hidden sm:block">
                    {{ __('Administración de todas las reuniones programadas en el sistema.') }}
                </p>
                {{-- Botón Programar Nueva Reunión con estilo fucsia/índigo --}}
                <a href="{{ route('admin.reuniones.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    {{ __('Programar Nueva Reunión') }}
                </a>
            </div>

            {{-- 3. CONTENEDOR DE TABLA --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                
                <div class="relative overflow-x-auto">
                    @if ($reuniones->isEmpty())
                        <div class="p-6 text-gray-500 dark:text-gray-400 text-center">
                            {{ __('No hay reuniones programadas actualmente.') }}
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    {{-- TÍTULO --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[25%]">
                                        {{ __('Título') }}
                                    </th>
                                    {{-- CREADOR --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%] hidden md:table-cell">
                                        {{ __('Creador') }}
                                    </th>
                                    {{-- PROYECTO --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%] hidden lg:table-cell">
                                        {{ __('Proyecto') }}
                                    </th>
                                    {{-- FECHA Y HORA --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%]">
                                        {{ __('Fecha y Hora') }}
                                    </th>
                                    {{-- ESTADO --}}
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%]">
                                        {{ __('Estado') }}
                                    </th>
                                    {{-- ACCIONES --}}
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%]">
                                        {{ __('Acciones') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($reuniones as $reunion)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                        
                                        {{-- COLUMNA TÍTULO --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $reunion->titulo }}
                                        </td>

                                        {{-- COLUMNA CREADOR --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 hidden md:table-cell">
                                            {{-- Usamos $reunion->creador->nombre si está disponible, sino $reunion->creador->name (por si el modelo no está corregido) --}}
                                            {{ $reunion->creador ? ($reunion->creador->nombre ?? $reunion->creador->name) : 'N/A' }}
                                        </td>
                                        
                                        {{-- COLUMNA PROYECTO --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 hidden lg:table-cell">
                                            {{ $reunion->proyecto ? $reunion->proyecto->nombre : 'Personal' }}
                                        </td>
                                        
                                        {{-- COLUMNA FECHA Y HORA --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                            {{ $reunion->fecha_hora_inicio->format('d/m/Y H:i') }}
                                        </td>
                                        
                                        {{-- COLUMNA ESTADO (Con colores) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                // Lógica de colores condicional
                                                $color = match($reunion->estado) {
                                                    'finalizada' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                    'en_curso' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                    'cancelada' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                    default => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200', // por_iniciar
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                {{ ucfirst(str_replace('_', ' ', $reunion->estado)) }}
                                            </span>
                                        </td>

                                        {{-- COLUMNA ACCIONES --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">
                                                {{-- Botón Ver --}}
                                                <a href="{{ route('admin.reuniones.show', $reunion) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Ver Detalles') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                
                                                {{-- Botón Editar --}}
                                                <a href="{{ route('admin.reuniones.edit', $reunion) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Editar Reunión') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7 1l4-4m-9-1h.01M15 2l3 3m-3-3l-3 3"></path></svg>
                                                </a>
                                                
                                                {{-- Botón Eliminar (Formulario) --}}
                                                <form action="{{ route('admin.reuniones.destroy', $reunion) }}" method="POST" onsubmit="return confirm('{{ __('¿Estás seguro de que deseas eliminar permanentemente la reunión: ' . $reunion->titulo . '?') }}');" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Eliminar Reunión') }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                @if ($reuniones->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $reuniones->links() }}
                    </div>
                @endif
                
            </div>
            
        </div>
    </div>
</x-app-layout>