@extends('layouts.admin')

@section('title', 'Gestión de Tareas')

@section('content')

{{-- TÍTULO: SECCIÓN FUERA DEL CONTENEDOR PRINCIPAL DE LA TABLA (SIMULANDO EL HEADER) --}}
<div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6 py-4">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-2 text-fuchsia-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>
        </svg>
        {{ __('Tareas del Sistema') }}
    </h1>
</div>

<div class="py-4 sm:py-6 -mt-8"> {{-- Se usa -mt-8 para compensar el espacio que dejó el título --}}
    {{-- CONTENEDOR PRINCIPAL: Eliminación de max-width y padding para hacerlo casi 'full-width' --}}
    <div class="max-w-full mx-auto space-y-4 px-2 sm:px-4 lg:px-6"> 
        
        {{-- 1. NOTIFICACIONES (ÉXITO/ERROR) --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md mx-2 sm:mx-0" role="alert">
                <span class="font-semibold">{{ __('¡Éxito!') }}</span> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md mx-2 sm:mx-0" role="alert">
                <span class="font-semibold">{{ __('¡Error!') }}</span> {{ session('error') }}
            </div>
        @endif

        {{-- 2. ENCABEZADO, BÚSQUEDA Y BOTÓN CREAR (Ahora solo contiene el botón y la búsqueda) --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3 px-2 sm:px-0">
            {{-- Párrafo de descripción (para rellenar el espacio donde antes estaba el título) --}}
            <p class="text-gray-600 dark:text-gray-400 hidden sm:block">
                {{ __('Listado completo de tareas registradas en el sistema.') }}
            </p>
            
            {{-- Botón Crear Nuevo (Fuchsia) --}}
            <a href="{{ route('admin.tareas.create') }}" class="inline-flex items-center px-4 py-2 bg-fuchsia-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                {{-- SVG para Añadir/Crear --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                {{ __('Crear Tarea') }}
            </a>
        </div>

        {{-- 3. FILTRO Y CONTENEDOR DE TABLA/TARJETA --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
            
            {{-- Controles de Búsqueda y Filtro --}}
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <input type="text" placeholder="{{ __('Buscar por título, proyecto o asignado...') }}" class="w-full sm:w-1/3 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-lg shadow-sm">
            </div>
            
            {{-- TABLA DE TAREAS --}}
            <div class="relative overflow-x-auto">
                @if ($tareas->isEmpty())
                    <div class="p-6 text-gray-500 dark:text-gray-400 text-center">
                        {{ __('No se encontraron tareas en la base de datos.') }}
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[20%]">
                                    {{ __('Tarea / Proyecto') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[20%] hidden sm:table-cell">
                                    {{ __('Asignado') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%] hidden md:table-cell">
                                    {{ __('Creador') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%]">
                                    {{ __('Estado') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%] hidden lg:table-cell">
                                    {{ __('Vencimiento') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%]">
                                    {{ __('Acciones') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($tareas as $tarea)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    
                                    {{-- TAREA / PROYECTO --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('admin.tareas.show', $tarea) }}" class="hover:text-fuchsia-600 dark:hover:text-fuchsia-400">{{ $tarea->titulo }}</a>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            @if ($tarea->proyecto)
                                                <span class="font-medium text-blue-500 dark:text-blue-400">P:</span> {{ $tarea->proyecto->nombre }}
                                            @else
                                                <span class="font-medium text-fuchsia-500 dark:text-fuchsia-400">Personal</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    {{-- ASIGNADO --}}
                                    <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $tarea->asignado->nombre }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $tarea->asignado->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- CREADOR --}}
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">
                                        {{ $tarea->creador->nombre }}
                                    </td>

                                    {{-- ESTADO (Colores dinámicos como los roles) --}}
                                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                                        @php
                                            // Define colores basados en el nombre del estado
                                            $color = match ($tarea->estado) {
                                                'completada' => 'green',
                                                'pendiente' => 'red', 
                                                'en_progreso' => 'blue',
                                                default => 'gray',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-800 dark:text-{{ $color }}-100">
                                            {{ ucfirst(str_replace('_', ' ', $tarea->estado)) }}
                                        </span>
                                    </td>
                                    
                                    {{-- VENCIMIENTO --}}
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell">
                                        @if($tarea->fecha_vencimiento && $tarea->fecha_vencimiento->isPast() && $tarea->estado !== 'completada')
                                            <span class="font-bold text-red-500">
                                                {{ $tarea->fecha_vencimiento->format('d/m/Y') }} (Vencida)
                                            </span>
                                        @else
                                            {{ $tarea->fecha_vencimiento ? $tarea->fecha_vencimiento->format('d/m/Y') : 'N/A' }}
                                        @endif
                                    </td>
                                    
                                    {{-- ACCIONES (SVGs) --}}
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            {{-- Botón Ver (Ojo) --}}
                                            <a href="{{ route('admin.tareas.show', $tarea) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Ver Detalles') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </a>
                                            
                                            {{-- Botón Editar (Lápiz) --}}
                                            <a href="{{ route('admin.tareas.edit', $tarea) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Editar Tarea') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                            </a>
                                            
                                            {{-- Botón Eliminar (Bote de basura) --}}
                                            <form action="{{ route('admin.tareas.destroy', $tarea) }}" method="POST" onsubmit="return confirm('{{ __('¿Estás seguro de que deseas eliminar esta tarea de forma permanente?') }}');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Eliminar Tarea') }}">
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
            @if ($tareas->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $tareas->links() }}
                </div>
            @endif
            
        </div>
        
    </div>
</div>
@endsection