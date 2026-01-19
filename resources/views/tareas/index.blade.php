@extends('layouts.app')

@section('title', 'Mis Tareas')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📋 Mis Tareas</h1>
        <a href="{{ route('tareas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out">
            ➕ Crear Nueva Tarea
        </a>
    </div>
    
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Aquí iría un componente o formulario para filtros (estado, prioridad, proyecto) --}}
    {{-- <div class="mb-6">
        @include('tareas.partials.filters')
    </div> --}}

    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        @if ($tareas->isEmpty())
            <div class="p-6 text-center text-gray-500">
                <p>No tienes tareas asignadas o creadas que mostrar.</p>
                <p class="mt-2">¡Comienza creando una nueva!</p>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título / Proyecto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimiento</th>
                        <th class="relative px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($tareas as $tarea)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <a href="{{ route('tareas.show', $tarea) }}" class="hover:text-indigo-600 font-bold">{{ $tarea->titulo }}</a>
                            </div>
                            <div class="text-xs text-gray-500">
                                @if ($tarea->proyecto)
                                    Proyecto: {{ $tarea->proyecto->nombre }}
                                @else
                                    Tarea Personal
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($tarea->estado == 'completada') bg-green-100 text-green-800
                                @elseif ($tarea->estado == 'pendiente') bg-red-100 text-red-800
                                @elseif ($tarea->estado == 'en_progreso') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $tarea->estado)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ ucfirst($tarea->prioridad) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm 
                            @if ($tarea->fecha_vencimiento && $tarea->fecha_vencimiento->isPast() && $tarea->estado !== 'completada') text-red-600 font-semibold @endif">
                            {{ $tarea->fecha_vencimiento ? $tarea->fecha_vencimiento->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('tareas.toggle', $tarea) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" title="Marcar como Completada/Pendiente" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
                                    @if ($tarea->estado == 'completada')
                                        ❌
                                    @else
                                        ✅
                                    @endif
                                </button>
                            </form>
                            <a href="{{ route('tareas.show', $tarea) }}" class="text-gray-600 hover:text-gray-900 ml-4" title="Ver detalles">
                                👁️
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="p-4">
                {{ $tareas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection