@extends('layouts.app') 

@section('title', $proyecto->nombre)

@section('content')
{{-- 1. CONTENEDOR PRINCIPAL CON MÁRGENES REDUCIDOS --}}
<div class="py-4 px-2 sm:px-4"> 
    
    {{-- 2. MARCO PRINCIPAL (max-w-7xl para mantener una buena lectura del texto largo) --}}
    <div class="max-w-8xl mx-auto space-y-4 bg-white p-4 sm:p-6 rounded-lg shadow-xl">

        {{-- Encabezado y Botones de Acción --}}
        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
            <a href="{{ route('proyectos.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Volver a Proyectos') }}
            </a>
        </div>

        {{-- Título Principal del Proyecto --}}
        <h1 class="text-1xl font-extrabold text-gray-900 mb-1">{{ $proyecto->nombre }}</h1>
        <p class="text-xs text-gray-500 mb-4">
            {{ __('Creado por') }} 
            <span class="font-semibold text-gray-700">{{ $proyecto->creador->nombre ?? 'N/A' }}</span> 
            {{ $proyecto->created_at->translatedFormat('el j F Y') }}
        </p>
        
        {{-- Sección para Solicitud de Unión (compactada). Ahora $isMember viene del controlador. --}}
        @if (!$isMember && !$proyecto->es_privado)
            <div class="p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg flex justify-between items-center text-sm">
                <p class="font-medium text-yellow-800">
                    {{ __('Proyecto público. Puedes solicitar unirte al equipo.') }}
                </p>
                <a href="{{ route('chat.admin') }}" class="inline-flex items-center px-3 py-1 bg-yellow-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.669A9.956 9.956 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    {{ __('Solicitar acceso') }}
                </a>
            </div>
        @elseif (!$isMember && $proyecto->es_privado)
             <div class="p-3 bg-red-50 border-l-4 border-red-500 rounded-lg text-sm">
                <p class="font-medium text-red-800">
                    {{ __('Proyecto privado. Contacta al administrador si requieres acceso.') }}
                </p>
            </div>
        @endif

        
        {{-- Grid de Información --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pt-2"> 
            
            {{-- Columna Principal: Descripción, Progreso y Tareas --}}
            <div class="lg:col-span-2 space-y-4"> 
                
                {{-- Tarjeta de Progreso --}}
                <div class="bg-white p-4 rounded-lg shadow-md border"> 
                    <h2 class="text-lg font-bold text-gray-800 mb-3">{{ __('Progreso General') }}</h2>
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl font-extrabold text-indigo-600">{{ $proyecto->progreso_porcentaje }}%</span>
                        <div class="flex-grow">
                            <div class="w-full bg-gray-200 rounded-full h-2"> 
                                <div class="h-2 rounded-full bg-indigo-500" style="width: {{ $proyecto->progreso_porcentaje }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta de Descripción --}}
                <div class="bg-white p-4 rounded-lg shadow-md border">
                    <h2 class="text-lg font-bold text-gray-800 mb-3">{{ __('Descripción') }}</h2>
                    <div class="prose max-w-none text-sm text-gray-700"> 
                        <p>{!! nl2br(e($proyecto->descripcion)) !!}</p>
                    </div>
                </div>

                {{-- Tareas Importantes --}}
                <div class="bg-white p-4 rounded-lg shadow-md border">
                    <h2 class="text-lg font-bold text-gray-800 mb-3 flex justify-between items-center">
                        {{ __('Próximas Tareas') }}
                        <a href="{{ route('tareas.index', ['proyecto' => $proyecto->id]) }}" class="text-xs font-normal text-indigo-600 hover:text-indigo-800">{{ __('Ver todas') }}</a> 
                    </h2>
                    
                    @if ($proyecto->tareas->isNotEmpty())
                        <ul class="divide-y divide-gray-200 text-sm"> 
                            @foreach ($proyecto->tareas as $tarea)
                                <li class="py-2 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-2 w-2 rounded-full mr-2 
                                            @if($tarea->estado === 'finalizada') bg-green-500 
                                            @else bg-yellow-500 @endif"></div> 
                                        <span class="text-gray-700 font-medium truncate">{{ $tarea->nombre }}</span>
                                    </div>
                                    <span class="text-xs font-semibold px-1.5 py-0.5 rounded-full capitalize 
                                        @if($tarea->prioridad === 'critica') bg-red-100 text-red-800 
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ ucfirst($tarea->prioridad) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic text-sm">{{ __('No hay tareas importantes o próximas añadidas a este proyecto.') }}</p>
                    @endif
                </div>

            </div>

            {{-- Columna Lateral: Detalles Clave, Equipo y Reuniones --}}
            <div class="lg:col-span-1 space-y-4">
                {{-- Tarjeta de Resumen Clave --}}
                <div class="bg-white p-4 rounded-lg shadow-md border">
                    <h2 class="text-lg font-bold text-gray-800 mb-3">{{ __('Detalles Clave') }}</h2>
                    <dl class="space-y-2 text-xs"> 
                        <div class="flex justify-between border-b pb-1"> 
                            <dt class="font-medium text-gray-500">{{ __('Estado') }}:</dt>
                            <dd>
                                <span class="px-1 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full capitalize
                                    @if($proyecto->estado === 'finalizado') bg-green-100 text-green-800
                                    @elseif($proyecto->estado === 'en_progreso') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $proyecto->estado)) }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <dt class="font-medium text-gray-500">{{ __('Prioridad') }}:</dt>
                            <dd class="text-gray-700 capitalize">{{ ucfirst($proyecto->prioridad) }}</dd>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <dt class="font-medium text-gray-500">{{ __('Visibilidad') }}:</dt>
                            <dd class="text-gray-700 font-medium">
                                <span class="px-1 py-0.5 rounded-full text-xs font-medium 
                                    @if($proyecto->es_privado) bg-red-100 text-red-800 @else bg-green-100 text-green-800 @endif">
                                    {{ $proyecto->es_privado ? 'Privado' : 'Público' }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between border-b pb-1">
                            <dt class="font-medium text-gray-500">{{ __('Fecha Límite') }}:</dt>
                            <dd class="text-gray-700">
                                {{ $proyecto->fecha_limite_estimada ? \Carbon\Carbon::parse($proyecto->fecha_limite_estimada)->translatedFormat('j M Y') : 'N/A' }}
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium text-gray-500">{{ __('Presupuesto Estimado') }}:</dt>
                            <dd class="text-gray-700 font-semibold">{{ $proyecto->presupuesto_estimado ? '$' . number_format($proyecto->presupuesto_estimado, 2) : 'No especificado' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Tarjeta de Equipo --}}
                <div class="bg-white p-4 rounded-lg shadow-md border">
                    <h2 class="text-lg font-bold text-gray-800 mb-3">{{ __('Equipo del Proyecto') }}</h2>
                    
                    @if ($proyecto->miembros->isNotEmpty())
                        <ul class="space-y-2">
                            @foreach ($proyecto->miembros as $miembro)
                                <li class="flex items-center justify-between text-xs border-b pb-1 last:border-b-0 last:pb-0"> 
                                    <div class="font-medium text-gray-700">
                                        {{ $miembro->nombre }}
                                    </div>
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full capitalize
                                        @if($miembro->pivot->rol === 'lider') bg-indigo-100 text-indigo-800
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ $miembro->pivot->rol }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic text-xs">{{ __('Solo el creador está asignado a este proyecto.') }}</p>
                    @endif
                </div>

                {{-- Próximas Reuniones --}}
                <div class="bg-white p-4 rounded-lg shadow-md border">
                    <h2 class="text-lg font-bold text-gray-800 mb-3">{{ __('Próximas Reuniones') }}</h2>

                    @if ($proyecto->reuniones->isNotEmpty())
                        <ul class="space-y-2">
                            @foreach ($proyecto->reuniones as $reunion)
                                <li class="text-xs">
                                    <p class="font-medium text-gray-700">{{ $reunion->tema }}</p>
                                    <p class="text-xs text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($reunion->fecha_hora_inicio)->translatedFormat('j M Y, H:i') }} hrs
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic text-xs">{{ __('No hay reuniones programadas.') }}</p>
                    @endif
                </div>
            </div>
        </div>
        
    </div> {{-- Cierre del marco principal --}}
</div>
@endsection