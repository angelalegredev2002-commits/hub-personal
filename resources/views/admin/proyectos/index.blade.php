@extends('layouts.admin')

@section('title', 'Gestión de Proyectos')

@section('content')
<div class="py-4 sm:py-6">
    {{-- CONTENEDOR PRINCIPAL: Full-width para maximizar espacio de tabla --}}
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

        {{-- 2. ENCABEZADO, BÚSQUEDA Y BOTÓN CREAR (Estilo fuchsia consistente) --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3 px-2 sm:px-0">
            {{-- Título con SVG para Proyectos/Archivo --}}
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
                {{ __('Proyectos del Sistema') }}
            </h1>
            
            {{-- Botón Crear Nuevo (Fuchsia) --}}
            <a href="{{ route('admin.proyectos.create') }}" class="inline-flex items-center px-4 py-2 bg-fuchsia-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                {{ __('Crear Proyecto') }}
            </a>
        </div>

        {{-- 3. FILTRO Y CONTENEDOR DE TABLA/TARJETA --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
            
            {{-- Controles de Búsqueda y Filtro --}}
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <input type="text" placeholder="{{ __('Buscar por nombre, creador o estado...') }}" class="w-full sm:w-1/3 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fuchsia-500 dark:focus:border-fuchsia-600 focus:ring-fuchsia-500 dark:focus:ring-fuchsia-600 rounded-lg shadow-sm">
            </div>
            
            {{-- TABLA DE PROYECTOS --}}
            <div class="relative overflow-x-auto">
                @if ($proyectos->isEmpty())
                    <div class="p-6 text-gray-500 dark:text-gray-400 text-center">
                        {{ __('No hay proyectos registrados en el sistema.') }}
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[25%]">Proyecto</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%] hidden sm:table-cell">Creador</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%]">Estado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[20%] hidden md:table-cell">Progreso</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[10%] hidden lg:table-cell">Límite</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300 w-[15%]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($proyectos as $proyecto)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                {{-- PROYECTO --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('admin.proyectos.show', $proyecto) }}" class="hover:text-fuchsia-600 dark:hover:text-fuchsia-400">{{ $proyecto->nombre }}</a>
                                </td>
                                
                                {{-- CREADOR --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                                    {{ $proyecto->creador->nombre }}
                                </td>
                                
                                {{-- ESTADO (Colores dinámicos) --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $color = match ($proyecto->estado) {
                                            'finalizado' => 'green',
                                            'pendiente' => 'yellow',
                                            'en_progreso' => 'blue',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-800 dark:text-{{ $color }}-100">
                                        {{ ucfirst(str_replace('_', ' ', $proyecto->estado)) }}
                                    </span>
                                </td>
                                
                                {{-- PROGRESO --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-1">
                                        <div class="bg-fuchsia-600 h-1.5 rounded-full" style="width: {{ $proyecto->progreso_porcentaje }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $proyecto->progreso_porcentaje }}%</span>
                                </td>
                                
                                {{-- LÍMITE --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell">
                                    {{ $proyecto->fecha_limite_estimada ? $proyecto->fecha_limite_estimada->format('d/m/Y') : 'N/A' }}
                                </td>
                                
                                {{-- ACCIONES (SVGs) --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        {{-- Botón Ver (Ojo) --}}
                                        <a href="{{ route('admin.proyectos.show', $proyecto) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Ver Detalles') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>
                                        
                                        {{-- Botón Editar (Lápiz) --}}
                                        <a href="{{ route('admin.proyectos.edit', $proyecto) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Editar Proyecto') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                        </a>
                                        
                                        {{-- Botón Eliminar (Bote de basura) --}}
                                        <form action="{{ route('admin.proyectos.destroy', $proyecto) }}" method="POST" onsubmit="return confirm('{{ __('¿Estás seguro de que deseas eliminar este proyecto y todos sus datos?') }}');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" title="{{ __('Eliminar Proyecto') }}">
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
            @if ($proyectos->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $proyectos->links() }}
                </div>
            @endif
            
        </div>
        
    </div>
</div>
@endsection