<x-app-layout>
    <x-slot name="header">
        {{-- Ícono en el Header --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center space-x-2">
            <span>{{ __('Administración de Conversaciones') }}</span>
        </h2>
    </x-slot>

    {{-- ELIMINAMOS el max-w-7xl y usamos py-6 para más espacio vertical --}}
    <div class="py-6">
        {{-- Se eliminó la restricción max-w-* --}}
        <div class="mx-auto sm:px-6 lg:px-8"> 
            {{-- Mensajes de Notificación --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-6 flex items-center space-x-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span>Listado de Conversaciones ({{ $conversaciones->total() }})</span>
                    </h3>
                    
                    <div class="overflow-x-auto border rounded-lg border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700/50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-40">
                                        <div class="flex items-center space-x-1">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2a3 3 0 015.356-1.857M7 20h5.356m7.443-13.856L15.357 5.214M17 8H7v-2a3 3 0 013-3h4a3 3 0 013 3v2M12 5v2m0 4h.01M17 14h-1.056M9 14h1.056" /></svg>
                                            <span>Participantes</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center space-x-1">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m-9 0L7 4h10l3 8m-2 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m-7 0L7 4h10l3 8" /></svg>
                                            <span>Último Mensaje</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center space-x-1">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span>Actualizado</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <div class="flex items-center space-x-1">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-3.414-3.414A1 1 0 0015.586 5H7a2 2 0 00-2 2v11a2 2 0 002 2z" /></svg>
                                            <span>Acciones</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($conversaciones as $conversacion)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $conversacion->id }}</td>
                                    
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                                        {{-- Mostrar los participantes con truncamiento si hay muchos --}}
                                        <div class="flex flex-wrap gap-1 max-h-12 overflow-hidden"> 
                                            @foreach ($conversacion->participantes as $p)
                                                <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-indigo-900 dark:text-indigo-300">
                                                    {{ $p->nombre }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 max-w-xs truncate text-sm text-gray-500 dark:text-gray-400">
                                        {{ $conversacion->ultimoMensaje ? $conversacion->ultimoMensaje->contenido : 'Sin mensajes' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $conversacion->updated_at->diffForHumans() }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                                        {{-- Botón VER con Ícono --}}
                                        <a href="{{ route('admin.conversaciones.show', $conversacion) }}" title="Ver Historial"
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 flex items-center">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{-- Se elimina el span vacío --}}
                                        </a>
                                        
                                        {{-- Botón ELIMINAR con Ícono --}}
                                        <form action="{{ route('admin.conversaciones.destroy', $conversacion) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres ELIMINAR TODA esta conversación? ¡Esta acción es irreversible y eliminará todos los mensajes asociados!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Eliminar Conversación"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 flex items-center">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                {{-- Se elimina el span vacío --}}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $conversaciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>