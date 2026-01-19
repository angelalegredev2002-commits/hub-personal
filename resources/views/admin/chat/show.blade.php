<x-app-layout>
    <x-slot name="header">
        {{-- Ícono en el Header --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center space-x-2">
            <span>{{ __('Ver Conversación #') . $conversacion->id }}</span>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8"> 
            
            {{-- Mensajes de Notificación --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow dark:bg-green-900/50 dark:border-green-800 dark:text-green-300" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 shadow dark:bg-red-900/50 dark:border-red-800 dark:text-red-300" role="alert">
                    {{ session('error') }}
                </div>
            @endif


            <div class="bg-white dark:bg-gray-800 shadow-2xl sm:rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                
                {{-- CABECERA DEL CHAT ADMIN --}}
                <div class="p-4 sm:p-6 border-b dark:border-gray-700 flex flex-col md:flex-row md:justify-between md:items-center bg-gray-50 dark:bg-gray-700/50">
                    
                    <div class="flex flex-col mb-4 md:mb-0">
                        {{-- ⬅️ Botón de Regreso --}}
                        <a href="{{ route('admin.conversaciones.index') }}" 
                           class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 mb-3 inline-flex items-center space-x-1 text-sm transition duration-150">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            <span>Volver a la lista de chats</span>
                        </a>

                        <h3 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-2">Participantes:</h3>
                        <div class="flex flex-wrap items-center gap-2">
                            @foreach ($conversacion->participantes as $p)
                                <span class="inline-flex items-center bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full dark:bg-indigo-900 dark:text-indigo-300 transition duration-150">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    {{ $p->nombre }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Botón de ELIMINAR CHAT COMPLETO --}}
                    <form action="{{ route('admin.conversaciones.destroy', $conversacion) }}" method="POST" onsubmit="return confirm('ATENCIÓN: ¿Estás seguro de ELIMINAR TODA esta conversación de forma PERMANENTE? ¡Esta acción es IRREVERSIBLE y elimina todos los mensajes y datos asociados!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition duration-150 shadow-lg flex items-center space-x-1 w-full md:w-auto"
                                title="Eliminar el chat y todos sus mensajes">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            <span>Eliminar Chat</span>
                        </button>
                    </form>
                </div>

                {{-- CONTENEDOR DE MENSAJES (con scroll) --}}
                <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto bg-gray-100 dark:bg-gray-900/50">
                    @forelse ($mensajes as $mensaje)
                        @php
                            // Lógica CORRECTA: Determinar si el emisor del mensaje es uno de los participantes.
                            // Si el emisor NO es el primer participante listado (en un chat 1:1), lo alineamos a la derecha.
                            // Para chats 1:1, esta heurística funciona: el primer participante (p1) va a la izquierda, el segundo (p2) a la derecha.
                            $emisorId = $mensaje->usuario_emisor_id;
                            $isParticipant2 = $conversacion->participantes->count() > 1 && $emisorId === $conversacion->participantes[1]->id;
                            
                            // Alineamos el mensaje del segundo participante a la derecha
                            $alignment = $isParticipant2 ? 'justify-end' : 'justify-start';

                            // Estilo especial si el emisor NO es uno de los participantes (ej. si fuera un sistema o un mensaje de un admin que no está en la conversacion)
                            $isExternal = !$conversacion->participantes->pluck('id')->contains($emisorId); 

                            // Estilos de la burbuja
                            $bubbleClasses = '';
                            if ($isExternal) {
                                // Mensaje externo (Sistema/Admin que no es participante)
                                $bubbleClasses = 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200 border border-yellow-300 dark:border-yellow-700 rounded-lg';
                            } elseif ($isParticipant2) {
                                // Segundo Participante (Derecha)
                                $bubbleClasses = 'bg-indigo-100 dark:bg-indigo-900/50 text-gray-900 dark:text-gray-100 rounded-br-none border-t border-indigo-200 dark:border-indigo-800';
                            } else {
                                // Primer Participante (Izquierda)
                                $bubbleClasses = 'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-tl-none border-t border-gray-300 dark:border-gray-600';
                            }
                        @endphp

                        <div class="flex {{ $alignment }}">
                            {{-- Contenedor principal de la burbuja --}}
                            <div class="group relative p-3 max-w-2xl rounded-xl shadow-md transition duration-150 ease-in-out {{ $bubbleClasses }}">

                                {{-- Nombre/Rol del Emisor --}}
                                <p class="text-xs font-bold mb-1 {{ $isExternal ? 'text-yellow-800 dark:text-yellow-300' : 'text-gray-600 dark:text-gray-300' }}">
                                    {{ $mensaje->emisor->nombre }} (ID: {{ $mensaje->usuario_emisor_id }})
                                    @if ($isExternal)
                                        <span class="text-xs text-red-500 font-normal italic">(Externo/Sistema)</span>
                                    @endif
                                </p>
                                
                                {{-- Contenido del Mensaje --}}
                                <p class="text-sm text-gray-800 dark:text-gray-100 whitespace-pre-wrap">
                                    {{ $mensaje->contenido }}
                                </p>
                                
                                {{-- Timestamp y Acción de Eliminar --}}
                                <div class="mt-1 text-xs text-right flex justify-between items-center space-x-4">
                                    <span class="text-gray-500 dark:text-gray-400">{{ $mensaje->created_at->format('M d, H:i:s') }}</span>
                                    
                                    {{-- Botón para eliminar mensaje individualmente (Oculto hasta hacer hover) --}}
                                    <form action="{{ route('admin.mensajes.destroy', $mensaje) }}" method="POST" onsubmit="return confirm('¿Estás seguro de ELIMINAR este mensaje? Se actualizará el estado de la conversación.');" class="inline opacity-0 group-hover:opacity-100 transition duration-300">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Eliminar mensaje" 
                                                class="text-red-500 hover:text-red-700 dark:hover:text-red-300 p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-900/50 transition">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                            <p class="mt-2 text-lg font-medium">Historial vacío</p>
                            <p class="text-sm">Esta conversación aún no contiene mensajes.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>