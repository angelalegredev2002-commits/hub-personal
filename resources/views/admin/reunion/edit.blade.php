<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight px-4 sm:px-6 lg:px-8">
            {{ __('Editar Reunión: ') . $reunion->titulo }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        {{-- CONTENEDOR PRINCIPAL: Max-width 4xl centrado --}}
        <div class="max-w-4xl mx-auto space-y-4 px-4 sm:px-6"> 
            
            {{-- 1. NOTIFICACIONES (ÉXITO/ERROR/VALIDACIÓN) --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md dark:bg-green-900 dark:border-green-700 dark:text-green-200" role="alert">
                    <span class="font-semibold">{{ __('¡Éxito!') }}</span> {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md dark:bg-red-900 dark:border-red-700 dark:text-red-200" role="alert">
                    <span class="font-bold">{{ __('¡Error de Validación!') }}</span> Por favor, corrige los errores.
                </div>
            @endif

            {{-- 2. CARD PRINCIPAL DEL FORMULARIO --}}
            <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700">
                
                {{-- 🔥 CORRECCIÓN IMPORTANTE: La ruta se ha corregido a 'admin.reuniones.update' --}}
                <form action="{{ route('admin.reuniones.update', $reunion) }}" method="POST">
                    @csrf
                    @method('PUT') 

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Campo Título --}}
                        <div class="col-span-1">
                            <label for="titulo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Título de la Reunión</label>
                            <input type="text" id="titulo" name="titulo" 
                                   value="{{ old('titulo', $reunion->titulo) }}" required
                                   class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            @error('titulo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Campo Estado --}}
                        <div class="col-span-1">
                            <label for="estado" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Estado de la Reunión</label>
                            <select id="estado" name="estado" required
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @foreach (['por_iniciar', 'en_curso', 'finalizada', 'cancelada'] as $estado)
                                    <option value="{{ $estado }}" {{ old('estado', $reunion->estado) == $estado ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $estado)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Campo Proyecto (si aplica) --}}
                        <div class="col-span-2">
                            <label for="proyecto_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Proyecto Asociado (Opcional)</label>
                            <select id="proyecto_id" name="proyecto_id"
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <option value="">-- Sin Proyecto --</option>
                                {{-- Asegúrate de que $proyectos esté disponible en la vista desde tu controlador --}}
                                @if (isset($proyectos))
                                    @foreach ($proyectos as $proyecto)
                                        <option value="{{ $proyecto->id }}" {{ old('proyecto_id', $reunion->proyecto_id) == $proyecto->id ? 'selected' : '' }}>
                                            {{ $proyecto->nombre }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('proyecto_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    {{-- Campos Fecha y Duración --}}
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="fecha_hora_inicio" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Fecha y Hora de Inicio</label>
                            @php
                                // Formatear fecha para el campo datetime-local
                                $fecha_hora_inicio_value = old('fecha_hora_inicio', $reunion->fecha_hora_inicio->format('Y-m-d\TH:i'));
                            @endphp
                            <input type="datetime-local" id="fecha_hora_inicio" name="fecha_hora_inicio" value="{{ $fecha_hora_inicio_value }}" required
                                   class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            @error('fecha_hora_inicio') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="duracion_minutos" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Duración (minutos)</label>
                            <input type="number" id="duracion_minutos" name="duracion_minutos" value="{{ old('duracion_minutos', $reunion->duracion_minutos) }}" min="5" required
                                   class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            @error('duracion_minutos') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Campo Asistentes --}}
                    <div class="mb-6">
                        <label for="asistentes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Asistentes Invitados</label>
                        {{-- Obtener IDs de asistentes actuales para preseleccionar --}}
                        @php
                            // Asume que la variable $usuarios está disponible y $reunion->asistentes es la relación
                            $asistentes_actuales = $reunion->asistentes->pluck('id')->toArray();
                        @endphp
                        <select multiple id="asistentes" name="asistentes[]" size="5"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            {{-- Asegúrate de que $usuarios esté disponible en la vista desde tu controlador --}}
                            @if (isset($usuarios))
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" 
                                        {{ in_array($usuario->id, old('asistentes', $asistentes_actuales)) ? 'selected' : '' }}>
                                        {{ $usuario->nombre }} ({{ $usuario->email }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Mantén presionada Ctrl/Cmd para selección múltiple. Selecciona los usuarios que deben asistir.</p>
                        @error('asistentes') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo Lugar --}}
                    <div class="mb-6 border border-indigo-200 dark:border-indigo-700 p-4 rounded-lg bg-indigo-50 dark:bg-gray-900/50">
                        <label class="block text-sm font-bold text-indigo-700 dark:text-indigo-400 mb-3">Ubicación (Física o Virtual)</label>
                        
                        <input type="text" id="lugar_fisico" name="lugar_fisico" placeholder="Lugar Físico (ej. Sala 301)" 
                               value="{{ old('lugar_fisico', $reunion->lugar_fisico) }}"
                               class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 mb-3 placeholder-gray-400 dark:placeholder-gray-500">
                        
                        <input type="url" id="enlace_videollamada" name="enlace_videollamada" placeholder="Enlace de Videollamada (ej. Zoom/Meet URL)" 
                               value="{{ old('enlace_videollamada', $reunion->enlace_videollamada) }}"
                               class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 placeholder-gray-400 dark:placeholder-gray-500">
                        
                        <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-2">La reunión se categorizará como virtual, física o híbrida según los campos completados.</p>
                    </div>

                    {{-- Campo Agenda --}}
                    <div class="mb-8">
                        <label for="agenda" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Agenda/Detalles</label>
                        <textarea id="agenda" name="agenda" rows="5"
                                  class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('agenda', $reunion->agenda) }}</textarea>
                        @error('agenda') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M7 11h.01M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Guardar Cambios
                        </button>
                        <a href="{{ route('admin.reuniones.show', $reunion) }}" class="inline-block align-baseline font-semibold text-sm text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition duration-150 ease-in-out">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
