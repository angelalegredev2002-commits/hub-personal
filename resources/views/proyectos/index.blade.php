@extends('layouts.app')

@section('title', 'Lista de Proyectos')

@section('content')
{{-- 1. CONTENEDOR PRINCIPAL CON MÁRGENES REDUCIDOS (Solo un pequeño padding lateral en móviles) --}}
{{-- Eliminamos 'container mx-auto' para que ocupe el 100% del ancho del layout. --}}
<div class="py-8 px-2 sm:px-4"> 
    
    {{-- 2. MARCO PRINCIPAL PARA TODO EL CONTENIDO (Fondo, Sombra, Padding)--}}
    {{-- Usamos max-w-7xl o max-w-full para controlar el ancho interno, y el marco tiene un padding interno. --}}
    <div class="max-w-full mx-auto bg-white p-4 sm:p-6 rounded-lg shadow-xl"> 

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-1xl font-bold text-gray-800 flex items-center">
                {{-- Icono SVG para Proyectos/Portafolio --}}
                {{ __('Proyectos Disponibles') }} 
            </h1>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($proyectos->isEmpty())
            <div class="p-6 text-center bg-gray-50 rounded-lg border">
                <p class="text-lg text-gray-500">No hay proyectos visibles para ti en el sistema.</p>
                <p class="mt-2 text-gray-400">Contacta a un administrador para solicitar acceso a un proyecto.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 **xl:grid-cols-4** gap-6"> 
                {{-- Mantenemos 4 columnas para mejor organización con el ancho extra --}}
                @foreach ($proyectos as $proyecto)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 ease-in-out border-l-4 
                        @if ($proyecto->estado === 'finalizado') border-green-500 
                        @elseif ($proyecto->estado === 'en_progreso') border-blue-500
                        @elseif ($proyecto->estado === 'cancelado') border-red-500
                        @else border-yellow-500 @endif">
                        
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <a href="{{ route('proyectos.show', $proyecto) }}" class="text-xl font-semibold text-gray-900 hover:text-indigo-600 truncate block w-4/5" title="{{ $proyecto->nombre }}">
                                    {{ $proyecto->nombre }}
                                </a>
                                <span class="text-xs font-bold px-2 py-1 rounded-full uppercase 
                                    @if ($proyecto->prioridad == 'critica') bg-red-100 text-red-800
                                    @elseif ($proyecto->prioridad == 'alta') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ ucfirst($proyecto->prioridad) }}
                                </span>
                            </div>

                            <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $proyecto->descripcion ?? 'Sin descripción.' }}</p>

                            <div class="mt-4">
                                <div class="flex justify-between text-xs font-medium text-gray-500">
                                    <span>Progreso</span>
                                    <span>{{ $proyecto->progreso_porcentaje }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full 
                                        @if ($proyecto->progreso_porcentaje == 100) bg-green-500 
                                        @elseif ($proyecto->progreso_porcentaje > 75) bg-blue-500
                                        @else bg-indigo-500 @endif" style="width: {{ $proyecto->progreso_porcentaje }}%">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-sm text-gray-500">
                                Creador: <span class="font-medium text-gray-700">{{ $proyecto->creador->nombre }}</span> 
                            </div>
                            <div class="mt-1 text-sm text-gray-500">
                                Límite: <span class="font-medium text-gray-700">
                                    {{ $proyecto->fecha_limite_estimada ? $proyecto->fecha_limite_estimada->format('d/m/Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-3 flex justify-between items-center text-xs text-gray-500">
                            <span class="capitalize">{{ ucfirst(str_replace('_', ' ', $proyecto->estado)) }}</span>
                            <a href="{{ route('proyectos.show', $proyecto) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 font-medium">
                                {{ __('Ver Detalles') }} 
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $proyectos->links() }}
            </div>
        @endif
        
    </div> {{-- Cierre del marco principal --}}
</div>
@endsection