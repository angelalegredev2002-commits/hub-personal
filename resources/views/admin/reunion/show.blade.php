<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Detalles de Reunión: ') . $reunion->titulo }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        {{-- CONTENEDOR PRINCIPAL: Max-width 7xl para full-width con padding mínimo --}}
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6">

            {{-- 1. ENCABEZADO Y BOTONES DE ACCIÓN --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-4 mb-3 sm:mb-0">
                    <span class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">{{ $reunion->titulo }}</span>
                    
                    {{-- Etiqueta de Estado --}}
                    @php
                        $color = match($reunion->estado) {
                            'finalizada' => 'bg-green-600',
                            'en_curso' => 'bg-yellow-500',
                            'cancelada' => 'bg-red-600',
                            default => 'bg-indigo-600', // por_iniciar
                        };
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full text-white {{ $color }}">
                        {{ ucfirst(str_replace('_', ' ', $reunion->estado)) }}
                    </span>
                </div>

                <div class="flex space-x-3">
                    {{-- Botón Editar (Usando ruta Admin) --}}
                    <a href="{{ route('admin.reuniones.edit', $reunion) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7 1l4-4m-9-1h.01M15 2l3 3m-3-3l-3 3"></path></svg>
                        Editar Reunión
                    </a>
                    
                    {{-- Formulario para eliminar (Usando ruta Admin) --}}
                    <form action="{{ route('admin.reuniones.destroy', $reunion) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar permanentemente esta reunión?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
            
            {{-- 2. CUERPO DE DETALLES: Grid de 3 columnas --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Columna Principal de Detalles (2/3 de ancho) --}}
                <div class="md:col-span-2 space-y-6">
                    
                    {{-- Card: Información Principal y Agenda --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Detalles del Evento</h2>
                        
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-sm">
                            <div class="sm:col-span-1">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Fecha y Duración:</dt>
                                <dd class="font-semibold text-gray-900 dark:text-gray-200">{{ $reunion->fecha_hora_inicio->format('d/m/Y H:i') }} ({{ $reunion->duracion_minutos }} min)</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Creador:</dt>
                                <dd class="text-gray-900 dark:text-gray-200">{{ $reunion->creador->nombre }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Proyecto Asociado:</dt>
                                <dd class="text-gray-900 dark:text-gray-200">{{ $reunion->proyecto ? $reunion->proyecto->nombre : 'N/A' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="font-medium text-gray-500 dark:text-gray-400">Tipo de Ubicación:</dt>
                                <dd class="font-semibold text-indigo-600 dark:text-indigo-400">{{ ucfirst($reunion->tipo_ubicacion) }}</dd>
                            </div>
                            
                            @if ($reunion->lugar_fisico || $reunion->enlace_videollamada)
                            <div class="sm:col-span-2 pt-2 border-t border-dashed border-gray-300 dark:border-gray-600">
                                @if ($reunion->lugar_fisico)
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Lugar Físico:</dt>
                                    <dd class="text-gray-900 dark:text-gray-200">{{ $reunion->lugar_fisico }}</dd>
                                @endif
                                @if ($reunion->enlace_videollamada)
                                    <dt class="font-medium text-gray-500 dark:text-gray-400 mt-2">Enlace Virtual:</dt>
                                    <dd><a href="{{ $reunion->enlace_videollamada }}" target="_blank" class="text-indigo-500 hover:text-indigo-400 hover:underline break-all">{{ $reunion->enlace_videollamada }}</a></dd>
                                @endif
                            </div>
                            @endif
                        </dl>

                        <h3 class="text-lg font-semibold mt-6 mb-2 text-gray-800 dark:text-gray-100 border-t border-gray-200 dark:border-gray-700 pt-4">Agenda y Objetivos</h3>
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $reunion->agenda ?? 'No hay agenda definida.' }}</p>
                        </div>
                    </div>

                    {{-- Card: Minuta de la Reunión --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Minuta y Resumen</h2>
                        
                        @if ($reunion->minuta)
                            <div class="prose dark:prose-invert max-w-none p-4 border rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-800 dark:text-gray-200">
                                <p class="whitespace-pre-wrap">{{ $reunion->minuta }}</p>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Aún no se ha guardado la minuta de esta reunión.</p>

                            {{-- Formulario para guardar la minuta (solo visible para el creador/admin) --}}
                            @if (auth()->check() && (auth()->id() == $reunion->usuario_creador_id || (isset(auth()->user()->es_administrador) && auth()->user()->es_administrador)))
                                {{-- Si estás usando rutas de admin, cambia la ruta de minuta aquí --}}
                                <form action="{{ route('admin.reuniones.minuta', $reunion) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <textarea name="minuta" rows="6" required placeholder="Escribe aquí la minuta de la reunión..."
                                                  class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"></textarea>
                                    </div>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                        Guardar Minuta y Finalizar
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
                
                {{-- Columna Lateral (1/3 de ancho) --}}
                <div class="md:col-span-1 space-y-6">
                    
                    {{-- Card: Asistentes --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Asistentes Invitados</h3>
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($reunion->asistentes as $asistente)
                                <li class="flex justify-between items-center py-2">
                                    <span class="text-gray-800 dark:text-gray-200">{{ $asistente->nombre }}</span>
                                    @php
                                        $statusClass = match($asistente->pivot->estado_invitacion) {
                                            'aceptado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'rechazado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200', // invitado
                                        };
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($asistente->pivot->estado_invitacion) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        
                        {{-- Formulario para confirmar asistencia (visible solo si es asistente y no es el creador) --}}
                        @if (auth()->check() && auth()->id() != $reunion->usuario_creador_id)
                            @php
                                $currentUserPivot = $reunion->asistentes->firstWhere('id', auth()->id())->pivot ?? null;
                            @endphp
                            @if ($currentUserPivot)
                                <h4 class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-300 pt-3 border-t border-dashed border-gray-300 dark:border-gray-600">Tu Estado:</h4>
                                <form action="{{ route('reuniones.confirmar', $reunion) }}" method="POST" class="mt-2 flex items-center">
                                    @csrf
                                    <select name="estado" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                        <option value="invitado" {{ $currentUserPivot->estado_invitacion == 'invitado' ? 'selected' : '' }}>No he respondido</option>
                                        <option value="aceptado" {{ $currentUserPivot->estado_invitacion == 'aceptado' ? 'selected' : '' }}>Acepto</option>
                                        <option value="rechazado" {{ $currentUserPivot->estado_invitacion == 'rechazado' ? 'selected' : '' }}>Rechazo</option>
                                    </select>
                                    <button type="submit" class="ml-3 bg-indigo-500 text-white py-2 px-3 text-xs rounded-lg hover:bg-indigo-600 transition duration-150">
                                        Actualizar
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                    
                    {{-- Card: Chat de Reunión --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Conversación Asociada</h3>
                        @if ($reunion->conversacion)
                            <p class="text-gray-700 dark:text-gray-300 mb-3">Esta reunión tiene un canal de chat asociado para preguntas y seguimiento.</p>
                            <a href="{{ route('conversaciones.show', $reunion->conversacion) }}" class="inline-flex items-center px-4 py-2 bg-indigo-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z"></path></svg>
                                Acceder al Chat
                            </a>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">El chat asociado no está disponible.</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>