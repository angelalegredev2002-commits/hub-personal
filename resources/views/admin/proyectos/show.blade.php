@extends('layouts.admin')

@section('title', 'Detalles del Proyecto: ' . $proyecto->nombre)

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
        {{ __('Proyecto: ') . $proyecto->nombre }}
    </h2>
@endsection

@section('content')
<div class="py-4 sm:py-6">
    <div class="max-w-full mx-auto space-y-6 px-2 sm:px-4 lg:px-6"> 
        
        {{-- Enlaces de navegación y Acciones --}}
        <div class="mb-4 flex justify-between items-center px-2 sm:px-0">
            <a href="{{ route('admin.proyectos.index') }}" class="text-fuchsia-600 hover:text-fuchsia-700 dark:text-fuchsia-400 dark:hover:text-fuchsia-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Volver a Proyectos') }}
            </a>

            {{-- Botones de Acción --}}
            <div class="flex space-x-3">
                <a href="{{ route('admin.proyectos.edit', $proyecto) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('Editar') }}
                </a>

                {{-- Formulario para Eliminación --}}
                <form action="{{ route('admin.proyectos.destroy', $proyecto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este proyecto de forma permanente? Esta acción no se puede deshacer.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Eliminar') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Alertas (ej. de éxito después de una actualización) --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Contenedor de Detalles --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Columna Principal de Detalles (2/3 del ancho en pantallas grandes) --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 lg:p-8 space-y-6">
                
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">{{ __('Información General') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    {{-- Creador --}}
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Creado por') }}</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100 font-semibold">{{ $proyecto->creador->nombre ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $proyecto->creador->email ?? '' }}</p>
                    </div>

                    {{-- Fecha de Creación --}}
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Fecha de Creación') }}</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100">{{ $proyecto->created_at->translatedFormat('j M Y') }}</p>
                    </div>

                    {{-- Estado --}}
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Estado') }}</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100 capitalize font-semibold">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($proyecto->estado === 'finalizado') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($proyecto->estado === 'en_progreso') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                @elseif($proyecto->estado === 'cancelado') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                {{ ucfirst(str_replace('_', ' ', $proyecto->estado)) }}
                            </span>
                        </p>
                    </div>

                    {{-- Prioridad --}}
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Prioridad') }}</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100 capitalize">
                            <span class="font-semibold 
                                @if($proyecto->prioridad === 'critica') text-red-600 dark:text-red-400 
                                @elseif($proyecto->prioridad === 'alta') text-orange-600 dark:text-orange-400 
                                @else text-gray-600 dark:text-gray-300 @endif">
                                {{ ucfirst($proyecto->prioridad) }}
                            </span>
                        </p>
                    </div>
                    
                    {{-- Fecha Límite --}}
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Fecha Límite Estimada') }}</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100">
                            {{ $proyecto->fecha_limite_estimada ? \Carbon\Carbon::parse($proyecto->fecha_limite_estimada)->translatedFormat('j M Y') : 'N/A' }}
                        </p>
                    </div>
                    
                    {{-- Presupuesto --}}
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Presupuesto Estimado') }}</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100">{{ $proyecto->presupuesto_estimado ? '$' . number_format($proyecto->presupuesto_estimado, 2) : 'No especificado' }}</p>
                    </div>

                    {{-- Visibilidad --}}
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Visibilidad') }}</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100">
                            {{ $proyecto->es_privado ? 'Privado (Solo miembros)' : 'Público' }}
                        </p>
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Descripción') }}</p>
                    <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                        <p>{!! nl2br(e($proyecto->descripcion)) !!}</p>
                    </div>
                </div>

            </div>

            {{-- Columna Lateral (1/3 del ancho en pantallas grandes) --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Progreso --}}
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Progreso') }} ({{ $proyecto->progreso_porcentaje }}%)</h4>
                    <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                        <div class="bg-fuchsia-600 text-xs font-medium text-fuchsia-100 text-center p-0.5 leading-none rounded-full" style="width: {{ $proyecto->progreso_porcentaje }}%"> 
                            {{ $proyecto->progreso_porcentaje }}%
                        </div>
                    </div>
                </div>

                {{-- Miembros del Equipo --}}
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Miembros del Equipo') }} ({{ $proyecto->miembros->count() }})</h4>
                    
                    @if ($proyecto->miembros->isNotEmpty())
                        <ul class="space-y-3">
                            @foreach ($proyecto->miembros as $miembro)
                                <li class="flex items-center justify-between text-sm">
                                    <div class="font-medium text-gray-700 dark:text-gray-300">
                                        {{ $miembro->nombre }}
                                    </div>
                                    <span class="px-3 py-0.5 text-xs font-medium rounded-full 
                                        @if($miembro->pivot->rol === 'lider') bg-fuchsia-100 text-fuchsia-800 dark:bg-fuchsia-800 dark:text-fuchsia-100
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif
                                        capitalize">
                                        {{ $miembro->pivot->rol }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">Este proyecto aún no tiene miembros asignados.</p>
                    @endif
                </div>

                {{-- Contadores de Relaciones (Opcional, si has cargado estas relaciones en el controlador) --}}
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Resumen de Tareas/Archivos') }}</h4>
                    <ul class="space-y-3">
                        <li class="flex justify-between items-center text-gray-700 dark:text-gray-300">
                            <span>Tareas Asociadas:</span>
                            <span class="font-bold text-fuchsia-600 dark:text-fuchsia-400">{{ $proyecto->tareas->count() }}</span>
                        </li>
                        <li class="flex justify-between items-center text-gray-700 dark:text-gray-300">
                            <span>Reuniones Programadas:</span>
                            <span class="font-bold text-fuchsia-600 dark:text-fuchsia-400">{{ $proyecto->reuniones->count() }}</span>
                        </li>
                        <li class="flex justify-between items-center text-gray-700 dark:text-gray-300">
                            <span>Archivos Subidos:</span>
                            <span class="font-bold text-fuchsia-600 dark:text-fuchsia-400">{{ $proyecto->archivos->count() }}</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        
    </div>
</div>
@endsection