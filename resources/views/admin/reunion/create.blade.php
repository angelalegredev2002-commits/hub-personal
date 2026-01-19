<x-app-layout>
    <x-slot name="header">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Programar Nueva Reunión') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        {{-- CONTENEDOR PRINCIPAL: Usa ancho extendido con buen padding lateral --}}
        <div class="max-w-full mx-auto space-y-6 px-4 sm:px-6 lg:px-8"> 
            
            {{-- 1. NOTIFICACIONES (ÉXITO/ERROR) --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md dark:bg-red-900 dark:border-red-700 dark:text-red-200" role="alert">
                    <span class="font-bold">{{ __('¡Error de Validación!') }}</span> Por favor, corrige los errores marcados en el formulario.
                </div>
            @endif

            {{-- 2. CARD PRINCIPAL DEL FORMULARIO --}}
            <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700">
                <h1 class="text-xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b pb-4 border-gray-200 dark:border-gray-700">Detalles de la Reunión</h1>
                
                <form action="{{ route('admin.reuniones.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        {{-- Campo Creador --}}
                        <div class="col-span-1">
                            <label for="usuario_creador_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Creador de la Reunión</label>
                            <select id="usuario_creador_id" name="usuario_creador_id" required
                                    class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <option value="">-- Seleccionar Creador --</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('usuario_creador_id', auth()->id()) == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->nombre }} (ID: {{ $usuario->id }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Define quién es el responsable principal.</p>
                            @error('usuario_creador_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Campo Título --}}
                        <div class="col-span-1">
                            <label for="titulo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Título de la Reunión</label>
                            <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required
                                   class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            @error('titulo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Campo Proyecto (si aplica) --}}
                    <div class="mb-6">
                        <label for="proyecto_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Proyecto Asociado (Opcional)</label>
                        <select id="proyecto_id" name="proyecto_id"
                                class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            <option value="">-- Sin Proyecto --</option>
                            @foreach ($proyectos as $proyecto)
                                <option value="{{ $proyecto->id }}" {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                                    {{ $proyecto->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('proyecto_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    {{-- Campos Fecha y Duración --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="fecha_hora_inicio" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Fecha y Hora de Inicio</label>
                            <input type="datetime-local" id="fecha_hora_inicio" name="fecha_hora_inicio" value="{{ old('fecha_hora_inicio') }}" required
                                   class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            @error('fecha_hora_inicio') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="duracion_minutos" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Duración (minutos)</label>
                            <input type="number" id="duracion_minutos" name="duracion_minutos" value="{{ old('duracion_minutos', 60) }}" min="5" required
                                   class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            @error('duracion_minutos') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Campo Asistentes: Checkboxes mejorados --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Asistentes Invitados</label>
                        
                        <div class="p-4 border border-gray-300 dark:border-gray-700 rounded-lg shadow-inner bg-gray-50 dark:bg-gray-900/50 h-64 overflow-y-scroll">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-2">
                                @foreach ($usuarios as $usuario)
                                    @php
                                        // Marcar como seleccionado si está en el old('asistentes') O si es el creador (para que siempre esté invitado)
                                        $isSelected = in_array($usuario->id, old('asistentes', [])) || $usuario->id == old('usuario_creador_id', auth()->id());
                                    @endphp
                                    <label class="inline-flex items-center text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded transition duration-150 cursor-pointer">
                                        <input type="checkbox" 
                                               name="asistentes[]" 
                                               value="{{ $usuario->id }}" 
                                               {{ $isSelected ? 'checked' : '' }}
                                               class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:checked:bg-indigo-600 dark:checked:border-indigo-600">
                                        <span class="ml-2 truncate" title="{{ $usuario->email }}">{{ $usuario->nombre }}</span>
                                        @if ($usuario->id == old('usuario_creador_id', auth()->id()))
                                            <span class="text-[10px] text-indigo-500 font-bold ml-1">(Creador)</span>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Selecciona a los asistentes. El Creador ya está incluido por defecto.</p>
                        @error('asistentes') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo Lugar --}}
                    <div class="mb-6 border border-indigo-200 dark:border-indigo-700 p-4 rounded-lg bg-indigo-50 dark:bg-gray-900/50">
                        <label class="block text-sm font-bold text-indigo-700 dark:text-indigo-400 mb-3">Ubicación (Física o Virtual)</label>
                        
                        <input type="text" id="lugar_fisico" name="lugar_fisico" placeholder="Lugar Físico (ej. Sala 301)" value="{{ old('lugar_fisico') }}"
                               class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 mb-3 placeholder-gray-400 dark:placeholder-gray-500">
                        
                        <input type="url" id="enlace_videollamada" name="enlace_videollamada" placeholder="Enlace de Videollamada (ej. Zoom/Meet URL)" value="{{ old('enlace_videollamada') }}"
                               class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 placeholder-gray-400 dark:placeholder-gray-500">
                        
                        <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-2">La reunión se categorizará como virtual, física o híbrida según los campos completados.</p>
                    </div>

                    {{-- Campo Agenda --}}
                    <div class="mb-8">
                        <label for="agenda" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Agenda/Detalles</label>
                        <textarea id="agenda" name="agenda" rows="4"
                                  class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('agenda') }}</textarea>
                        @error('agenda') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>
                            Programar Reunión
                        </button>
                        <a href="{{ route('admin.reuniones.index') }}" class="inline-block align-baseline font-semibold text-sm text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition duration-150 ease-in-out">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>